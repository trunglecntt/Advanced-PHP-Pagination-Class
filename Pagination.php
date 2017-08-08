<?php
/**
*   Class: Pagination with limit display pages / include ellipsis
*   Author: TLH
*   @param  $currentPage | numeric | Current page
*   @param  $link | string | Current URI
*   @param  $maxPage | numeric | Number of page to display
*   @param  $totalPage | numeric | Total of pages
*   @return $html | HTML | Rendering HTML to pagination
*/

class Pagination
{
    private $currentPage = NULL;
    private $link = NULL;
    private $maxPage = NULL;
    private $totalPage = NULL;
    private $invalid = false;

    function __construct()
    {
        $this->link = $_SERVER['PHP_SELF'] . '?page=';
    }

    public function setCurrentPage($page)
    {
        if (is_numeric($page)) {
            $this->currentPage = $page;
        } else {
            $this->invalid = true;
        }
    }

    public function setTotalPage($total)
    {
        if (is_numeric($total)) {
            $this->totalPage = $total;
        } else {
            $this->invalid = true;
        }
    }

    public function setMaxPage($maxPage)
    {
        if (is_numeric($maxPage)) {
            $this->maxPage = $this->totalPage > $maxPage ? $maxPage : $this->totalPage;
        } else {
            $this->invalid = true;
        }
    }

    public function setLink($param, $value)
    {
        $this->link = $_SERVER['PHP_SELF'] . "?$param=$value&page=";
    }

    public function paginate()
    {
        $min = 1;
        $max = $this->maxPage;
        $middle = ceil(($min + $max) / 2);
        if ($this->currentPage > $middle) {
            $min = $this->currentPage - $middle + 1;
            $max = $this->currentPage + $middle - 1;
            if ($max > $this->totalPage) {
                $max = $this->totalPage;
                $min = $this->totalPage - $this->maxPage + 1;
            }
        }

        $html = '';
        if (! $this->invalid) {
            if ($this->totalPage > 1) {
                $prevPage = $this->currentPage == 1 ? 1 : $this->currentPage - 1;
                $nextPage = $this->currentPage == $this->totalPage ? $this->totalPage : $this->currentPage + 1;

                //Previous and first page
                if ($this->currentPage > 1) {
                    $html .= '<a href="' . $this->link . '1">First</a> ';
                    $html .= '<a href="' . $this->link . $prevPage . '">&larr;</a> ';
                }

                //Create all page indexes of pagination
                for ($i = $min; $i <= $max; $i++) {
                    if ($i == $min && $this->totalPage > $this->maxPage && $i > 3) {
                        $html .= '<a href="' . $this->link . '1">1</a> ';
                        $html .= '<a href="' . $this->link . '2">2</a> ';
                        $html .= '... ';
                    }

                    if ($this->currentPage == $i) {
                        $html .= '<a><b>'. $i .'</b></a> ';
                    } else {
                        $html .= '<a href="' . $this->link . $i . '">' . $i . '</a> ';
                    }

                    if ($i == $max && $this->totalPage > $this->maxPage && $i < $this->totalPage - 2) {
                        $html .= '... ';
                        $html .= '<a href="' . $this->link . ($this->totalPage - 1) . '">' . ($this->totalPage - 1) . '</a> ';
                        $html .= '<a href="' . $this->link . ($this->totalPage) . '">' . $this->totalPage . '</a> ';
                    }
                }

                //Next and last page
                if ($this->currentPage < $this->totalPage) {
                    $html .= '<a href="' . $this->link . $nextPage . '">&rarr;</a> ';
                    $html .= '<a href="' . $this->link . $this->totalPage . '">Last</a>';
                }
            } elseif ($this->totalPage == 1) {
                $html .= '<a><b>1</b></a>';
            }
        } else {
            $html = '<b>Wrong pagination parameter!</b>';
        }

        return $html;
    }
}
