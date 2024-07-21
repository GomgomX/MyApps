<?php
namespace App\Classes;

class PageViewsCounter {
    private $file;
    private $views;

    public function __construct($path = false) {
        if($path && strtolower(basename(getcwd()) == "public"))
        {
            $this->file = $path;
            if($this->fileExists()) {
                $this->updateCounter();
            } else {
                $this->createCounterFile();
            }
        }
    }

    public function fileExists() {
        return file_exists($this->file);
    }

    public function updateCounter() {
        $actie = fopen($this->file, "r+"); 
        $page_views = fgets($actie, 9); 
        $page_views++; 
        rewind($actie); 
        fputs($actie, $page_views, 9); 
        fclose($actie);
        $this->views = $page_views;
    }

    public function createCounterFile() {
        $actie = fopen($this->file, "w"); 
        $page_views = 1; 
        fputs($actie, $page_views, 9); 
        fclose($actie);
        $this->views = $page_views;
    }

    public function getViews() {
        return $this->views;
    }
}