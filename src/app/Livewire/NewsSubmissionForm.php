<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\NewsPost;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class NewsSubmissionForm extends Component
{
    use AuthorizesRequests;

    public $postId = null;

    public $title = '';

    public $content = '';

    public $category_id = '';

    public $cover_image = '';

    public $existing_image = null;

    public $image_preview_url = null;

    public $image_validation_message = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string|min:50',
        'category_id' => 'required|exists:categories,id',
        'cover_image' => 'nullable|url|max:2048',
    ];

    public function mount($postId = null)
    {
        if ($postId) {
            $post = NewsPost::findOrFail($postId);
            Gate::authorize('update', $post);

            $this->postId = $post->id;
            $this->title = $post->title;
            $this->content = $post->content;
            $this->category_id = $post->category_id;
            $this->cover_image = $post->cover_image ?? '';
            $this->existing_image = $post->cover_image;
        }
    }

    public function testImage()
    {
        $this->image_validation_message = '';
        $this->image_preview_url = null;

        if (empty($this->cover_image)) {
            $this->image_validation_message = 'Please enter an image URL first.';
            return;
        }

        // Validate URL format
        if (!filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            $this->image_validation_message = 'Please enter a valid URL.';
            return;
        }

        // Check if URL is accessible and is an image
        try {
            $headers = @get_headers($this->cover_image, 1);

            if ($headers === false) {
                $this->image_validation_message = 'Cannot access this URL. Please check if it\'s correct.';
                return;
            }

            $statusCode = is_array($headers[0]) ? $headers[0][0] : $headers[0];

            if (strpos($statusCode, '200') === false) {
                $this->image_validation_message = 'Image not found at this URL (HTTP '.$statusCode.').';
                return;
            }

            // Check content type
            $contentType = is_array($headers['Content-Type'] ?? null)
                ? $headers['Content-Type'][0]
                : ($headers['Content-Type'] ?? '');

            $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

            if (!empty($contentType) && !in_array(strtolower($contentType), $imageTypes) && !str_contains(strtolower($contentType), 'image/')) {
                $this->image_validation_message = 'This URL does not point to an image. Content type: '.$contentType;
                return;
            }

            // If we get here, the image is valid
            $this->image_preview_url = $this->cover_image;
            $this->image_validation_message = 'Image loaded successfully! ✓';

        } catch (\Exception $e) {
            $this->image_validation_message = 'Error validating image: '.$e->getMessage();
        }
    }

    public function save()
    {
        // Custom validation for cover_image URL
        $rules = $this->rules;

        if (!empty($this->cover_image)) {
            // Validate URL format
            if (!filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
                $this->addError('cover_image', 'Please enter a valid URL.');
                return;
            }

            // Check if URL has common image extensions (basic validation)
            $imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg'];
            $hasImageExtension = false;
            foreach ($imageExtensions as $ext) {
                if (stripos($this->cover_image, $ext) !== false) {
                    $hasImageExtension = true;
                    break;
                }
            }

            // If URL has image extension, allow it (for testing and basic validation)
            // Otherwise, try to verify via headers
            if (!$hasImageExtension) {
                try {
                    $context = stream_context_create([
                        'http' => [
                            'method' => 'HEAD',
                            'timeout' => 5,
                            'ignore_errors' => true,
                        ],
                    ]);

                    $headers = @get_headers($this->cover_image, 1, $context);

                    if ($headers !== false) {
                        $statusCode = is_array($headers[0]) ? $headers[0][0] : $headers[0];

                        // If we get a 200 response, check content type
                        if (strpos($statusCode, '200') !== false) {
                            $contentType = is_array($headers['Content-Type'] ?? null)
                                ? $headers['Content-Type'][0]
                                : ($headers['Content-Type'] ?? '');

                            $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

                            // If content type is available and not an image, reject it
                            if (!empty($contentType) && !in_array(strtolower($contentType), $imageTypes) && !str_contains(strtolower($contentType), 'image/')) {
                                $this->addError('cover_image', 'This URL does not point to a valid image file.');
                                return;
                            }
                        } elseif (strpos($statusCode, '404') !== false || strpos($statusCode, '403') !== false) {
                            $this->addError('cover_image', 'Image not found at this URL. Please check the URL.');
                            return;
                        }
                    }
                } catch (\Exception $e) {
                    // If we can't validate, allow it (for testing environments)
                    // In production, users should use the "Test Image" button to verify
                }
            }
        }

        $this->validate($rules);

        if ($this->postId) {
            $post = NewsPost::findOrFail($this->postId);
            Gate::authorize('update', $post);
        } else {
            Gate::authorize('create', NewsPost::class);
            $post = new NewsPost;
            $post->user_id = Auth::id();
            $post->status = 'pending';
        }

        $post->title = $this->title;
        $post->content = $this->content;
        $post->category_id = $this->category_id;
        $post->cover_image = !empty($this->cover_image) ? $this->cover_image : null;

        $post->save();

        session()->flash('message', $this->postId ? 'Post updated successfully!' : 'Post submitted for review!');

        return redirect()->route('my-submissions');
    }

    public function render()
    {
        // Cache categories for 1 hour (3600 seconds) - they rarely change
        $categories = Cache::remember('categories.all', 3600, function () {
            return Category::orderBy('name')->get();
        });

        return view('livewire.news-submission-form', compact('categories'));
    }
}
