<?php

namespace App\Livewire;

use Livewire\Component;

class SocialShareButtons extends Component
{
    public $pageUrl;

    public $pageTitle;

    public $pageType;

    public $newsPostId = null;

    public function mount($pageUrl, $pageTitle, $pageType = null, $newsPostId = null)
    {
        $this->pageUrl = $pageUrl;
        $this->pageTitle = $pageTitle;
        $this->pageType = $pageType;
        $this->newsPostId = $newsPostId;
    }

    public function render()
    {
        return view('livewire.social-share-buttons');
    }
}
