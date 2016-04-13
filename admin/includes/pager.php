<?php
/**
 * Pager
 *
 * @author Ethan Liu <ethan@creativecrap.com>
 * @copyright Creativecrap.com, 26 April, 2012
 **/

class Pager {
	public $total = 0;
	public $delta = 5;
	public $currentPage = 0;
	public $style = '';
	public $path = '';
    public $prevArrow = '<<';
    public $nextArrow = '>>';

	public function __construct($total = 0, $currentPage = 1) {
		$this->total = $total;
		$this->currentPage = $currentPage;
	}

	public function paginate() {
		$html = "";
		if ($this->total <= 1) {
			return $html;
		}

		$start = $this->currentPage - $this->delta;
		$start = ($start < 1) ? 1 : $start;
		$end = $this->currentPage + $this->delta;
		$end = ($end >= $this->total) ? $this->total : $end;

		$query = array();
		parse_str($_SERVER['QUERY_STRING'], $query);
		//unset($query['page']);

		$html .= '<ul class="pagination">';

		if ($this->currentPage != 1 && $this->total > $this->delta) {
			$query['page'] = ($this->currentPage - 1);
			$html .= sprintf('<li><a href="%s?%s">%s</a></li>', $this->path, http_build_query($query), $this->prevArrow);
		}

		if ($start > 1) {
			// add first page
			$query['page'] = 1;
			$html .= sprintf('<li><a href="%s?%s">%d</a></li>', $this->path, http_build_query($query), $query['page']);
			if ($start - 1 > 1) {
				$html .= '<li class="disabled"><a href="javascript:void(0);">...</a></li>';
			}
		}


		for ($i=$start; $i <= $end; $i++) {
			$style = ($i == $this->currentPage) ? ' class="active"' : '';
			$query['page'] = $i;
			$item = sprintf('<li%s><a href="%s?%s">%d</a></li>', $style, $this->path, http_build_query($query), $query['page']);
			$html .= $item;
		}

		if ($end < $this->total) {
			// add last page
			if ($this->total - $end > 1) {
				$html .= '<li class="disabled"><a href="javascript:void(0);">...</a></li>';
			}
			$query['page'] = $this->total;
			$html .= sprintf('<li><a href="%s?%s">%d</a></li>', $this->path, http_build_query($query), $query['page']);
		}

		if ($this->currentPage != $this->total && $this->total > $this->delta) {
			$query['page'] = ($this->currentPage + 1);
			$html .= sprintf('<li><a href="%s?%s">%s</a></li>', $this->path, http_build_query($query), $this->nextArrow);
		}

		$html .= '</ul>';

		return $html;
	}

}
