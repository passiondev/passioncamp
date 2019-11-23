<?php

namespace App\Pagination;

use Illuminate\Support\HtmlString;
use Illuminate\Pagination\UrlWindow;
use Illuminate\Pagination\UrlWindowPresenterTrait;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Contracts\Pagination\Presenter as PresenterContract;

class Semantic implements PresenterContract
{
    use UrlWindowPresenterTrait;

    public function __construct(PaginatorContract $paginator, UrlWindow $window = null)
    {
        $this->paginator = $paginator;
        $this->window = null === $window ? UrlWindow::make($paginator) : $window->get();
    }

    public function render()
    {
        if ($this->hasPages()) {
            return new HtmlString(sprintf(
                '<div class="ui pagination menu">%s <div class="pages">%s</div> %s</div>',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            ));
        }

        return '';
    }

    public function hasPages()
    {
        return $this->paginator->hasPages() && \count($this->paginator->items()) > 0;
    }

    /**
     * Get the previous page pagination element.
     *
     * @param string $text
     *
     * @return string
     */
    public function getPreviousButton($text = '<i class="left arrow icon"></i>')
    {
        // If the current page is less than or equal to one, it means we can't go any
        // further back in the pages, so we will render a disabled previous button
        // when that is the case. Otherwise, we will give it an active "status".
        if ($this->paginator->currentPage() <= 1) {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->paginator->url(
            $this->paginator->currentPage() - 1
        );

        return $this->getPageLinkWrapper($url, $text, 'prev');
    }

    /**
     * Get the next page pagination element.
     *
     * @param string $text
     *
     * @return string
     */
    public function getNextButton($text = '<i class="right arrow icon"></i>')
    {
        // If the current page is greater than or equal to the last page, it means we
        // can't go any further into the pages, as we're already on this last page
        // that is available, so we will make it the "next" link style disabled.
        if (!$this->paginator->hasMorePages()) {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->paginator->url($this->paginator->currentPage() + 1);

        return $this->getPageLinkWrapper($url, $text, 'next');
    }

    /**
     * Get HTML wrapper for an available page link.
     *
     * @param string      $url
     * @param int         $page
     * @param string|null $rel
     *
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page, $rel = null)
    {
        $rel = null === $rel ? '' : ' rel="'.$rel.'"';

        return '<a class="slider item" href="'.htmlentities($url).'"'.$rel.'>'.$page.'</a>';
    }

    /**
     * Get HTML wrapper for disabled text.
     *
     * @param string $text
     *
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<div class="disabled item">'.$text.'</div>';
    }

    /**
     * Get HTML wrapper for active text.
     *
     * @param string $text
     *
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<span class="active item">'.$text.'</span>';
    }

    /**
     * Get a pagination "dot" element.
     *
     * @return string
     */
    protected function getDots()
    {
        return $this->getDisabledTextWrapper('<i class="ellipsis horizontal icon"></i>');
    }

    /**
     * Get the current page from the paginator.
     *
     * @return int
     */
    protected function currentPage()
    {
        return $this->paginator->currentPage();
    }

    /**
     * Get the last page from the paginator.
     *
     * @return int
     */
    protected function lastPage()
    {
        return $this->paginator->lastPage();
    }
}
