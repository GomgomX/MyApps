<?php

namespace App\Http\Controllers;

class LibraryController extends Controller {
    public function experienceTable() {
        return view('library.experiencetable', ['pageTitle' => 'Experience Table', 'subtopic' => 'experiencetable']);
    }
}