<div class="card bg-base-100 shadow-xl">
    <div class="card-body">
        <form wire:submit="save" class="space-y-6">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-lg">Title</span>
                </label>
                <input type="text" wire:model="title"
                    class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('title') input-error @enderror"
                    placeholder="Enter a compelling news title">
                @error('title')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-lg">Category</span>
                </label>
                <select wire:model="category_id"
                    class="select select-bordered w-full focus:outline-none focus:ring-2 focus:ring-primary @error('category_id') select-error @enderror">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-lg">Content</span>
                </label>
                <textarea wire:model="content" id="content-textarea"
                    class="textarea textarea-bordered w-full h-64 md:h-80 resize-y focus:outline-none focus:ring-2 focus:ring-primary @error('content') textarea-error @enderror"
                    placeholder="Write your news article content here..."></textarea>

                <!-- Word Counter -->
                <label class="label">
                    <span class="label-text-alt text-base-content/70">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span id="word-count">0</span> words
                    </span>
                    <span class="label-text-alt text-base-content/70">
                        <span id="char-count">0</span> characters
                    </span>
                </label>

                @error('content')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold text-lg">Cover Image URL</span>
                    <span class="label-text-alt">Optional</span>
                </label>

                @if($existing_image)
                    <div class="mb-4">
                        <div class="card bg-base-200 shadow-sm">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ str_starts_with($existing_image, 'http') ? $existing_image : Storage::url($existing_image) }}"
                                        alt="Current image" class="w-32 h-32 object-cover rounded-lg"
                                        onerror="this.src='https://via.placeholder.com/128x128?text=Image+Not+Found'">
                                    <div>
                                        <p class="font-medium">Current Image</p>
                                        <p class="text-sm text-base-content/70">Enter a new image URL to replace</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex gap-2">
                    <input type="url" wire:model.live="cover_image" placeholder="https://example.com/image.jpg"
                        class="input input-bordered flex-1 focus:outline-none focus:ring-2 focus:ring-primary @error('cover_image') input-error @enderror">
                    <button type="button" wire:click="testImage" wire:loading.attr="disabled"
                        class="btn btn-outline btn-primary" @if(empty($cover_image)) disabled @endif>
                        <span wire:loading.remove wire:target="testImage">Test Image</span>
                        <span wire:loading wire:target="testImage" class="flex items-center">
                            <span class="loading loading-spinner loading-sm"></span>
                        </span>
                    </button>
                </div>

                @error('cover_image')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror

                @if($image_validation_message)
                    <div class="mt-2">
                        <div
                            class="alert @if(str_contains($image_validation_message, 'successfully')) alert-success @else alert-warning @endif">
                            <span>{{ $image_validation_message }}</span>
                        </div>
                    </div>
                @endif

                @if($image_preview_url)
                    <div class="mt-4">
                        <div class="card bg-base-200 shadow-sm">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $image_preview_url }}" alt="Preview"
                                        class="w-32 h-32 object-cover rounded-lg"
                                        onerror="this.src='https://via.placeholder.com/128x128?text=Image+Not+Found'">
                                    <div>
                                        <p class="font-medium">Image Preview</p>
                                        <p class="text-sm text-base-content/70">This will be your cover image</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-control mt-8">
                @if($postId)
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('editor.submissions') }}" class="btn btn-ghost">Cancel</a>
                        <button type="submit" class="btn btn-warning btn-lg" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Update Article</span>
                                </span>
                            </span>
                            <span wire:loading wire:target="save" class="flex items-center gap-2">
                                <span class="loading loading-spinner loading-sm"></span>
                                <span>Saving...</span>
                            </span>
                        </button>
                    </div>
                @else
                    <button type="submit" class="btn btn-warning btn-lg btn-block" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Publish Article</span>
                            </span>
                        </span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <span class="loading loading-spinner loading-sm"></span>
                            <span>Publishing...</span>
                        </span>
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>