<?php

class Pagination {
    public $total = 0;
    public $page = 1;
    public $limit = 20;
    public $num_links = 8;
    public $url = '';
    public $text_first = '|&lt;';
    public $text_last = '&gt;|';
    public $text_next = '&gt;';
    public $text_prev = '&lt;';
    public $next_hide = true;
    public $prev_hide = true;
    public $url_prev = '';
    public $url_next = '';

    public function render() {
        $total = $this->total;

        if ($this->page < 1) {
            $page = 1;
        } else {
            $page = $this->page;
        }

        if (!(int)$this->limit) {
            $limit = 10;
        } else {
            $limit = $this->limit;
        }

        $num_links = $this->num_links;
        $num_pages = ceil($total / $limit);

        $this->url = str_replace('%7Bpage%7D', '{page}', $this->url);

        $output = '<ul class="pagination">';

        if ($page > 1) {
            $output .= '<li class="page-item first"><a class="page-link fa fa-angle-double-left" href="' . str_replace(array(
                    '&amp;page={page}',
                    '?page={page}',
                    '&page={page}' ), '', $this->url) . '">' . $this->text_first . '</a></li>';


            if ($page - 1 === 1) {
                $this->url_prev = str_replace(array(
                    '&amp;page={page}',
                    '?page={page}',
                    '&page={page}'
                ), '', $this->url);
            } else {
                $this->url_prev = str_replace('{page}', $page - 1, $this->url);
            }
            $output .= !$this->prev_hide ? '<li class="page-item"><a class="page-link fa fa-angle-left" href="' . $this->url_prev . '">' . $this->text_prev . '</a></li>' : false;
        }

        if ($num_pages > 1) {
            if ($num_pages <= $num_links) {
                $start = 1;
                $end = $num_pages;
            } else {
                $start = $page - floor($num_links / 2);
                $end = $page + floor($num_links / 2);

                if ($start < 1) {
                    $end += abs($start) + 1;
                    $start = 1;
                }

                if ($end > $num_pages) {
                    $start -= ($end - $num_pages);
                    $end = $num_pages;
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $output .= '<li class="page-item active"><a class="page-link" href="#">'.$i.' <span class="sr-only">(current)</span></a></li>';
                } else {
                    if ($i === 1) {

                        $output .= '<li class="page-item"><a class="page-link" href="' . str_replace(array( '&amp;page={page}', '&page={page}' ), '', $this->url) . '">' . $i . '</a></li>';
                    } else {
                        $output .= '<li class="page-item"><a class="page-link" href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a></li>';
                    }
                }
            }
        }

        if ($page < $num_pages) {
            $this->url_next =str_replace('{page}', $page + 1, $this->url);
            $output .=!$this->next_hide ? '<li class="page-item"><a class="page-link fa fa-angle-right" href="'.$this->url_next.'">' . $this->text_next . '</a></li>' : false;
            $output .= '<li class="page-item last"><a class="page-link fa fa-angle-double-right" href="' . str_replace('{page}', $num_pages, $this->url) . '">' . $this->text_last . '</a></li>';
        }

        $output .= '</ul>';

        if ($num_pages > 1) {
            return $output;
        } else {
            return '';
        }
    }

}
