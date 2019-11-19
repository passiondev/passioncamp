<?php

namespace App\Printer;

abstract class BasePrinter
{
    protected $title;
    protected $filename;

    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    public function filename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getFilename()
    {
        return $this->filenameIsValid() ? $this->filename : $this->getFormattedFilename();
    }

    public function filenameIsValid()
    {
        if (null === $this->filename) {
            return false;
        }

        if (!ends_with($this->filename, '.pdf')) {
            return false;
        }

        return true;
    }

    public function getFormattedFilename()
    {
        return str_slug($this->title).'.pdf';
    }
}
