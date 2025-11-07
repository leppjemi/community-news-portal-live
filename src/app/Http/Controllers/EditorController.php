<?php

namespace App\Http\Controllers;

class EditorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:editor,admin']);
    }

    public function reviewQueue()
    {
        return view('editor.review-queue');
    }
}
