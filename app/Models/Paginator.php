<?php

namespace PiotrKu\CustomTablesCrud\Models;

use PiotrKu\CustomTablesCrud\Plugin;
use PiotrKu\CustomTablesCrud\Models\TableDataGetter;


/*******************************************************************************
* Pagination class *
* Created: 12th January 2009 *
* Updated: 9th September 2009 *
* ©Copyright Jay Gilford 2009 *
* http://www.jaygilford.com *
* email: jay [at] jaygilford.com *
*******************************************************************************/

class Paginator
{
	################################
	# PRIVATE VARS - DO NOT ALTER #
	################################
	private $_db = '';
	private $_query = '';
	private $_current_page = 1;
	private $_padding = 2;
	private $_output;

	################################
	# RESULTS VARS #
	################################
	public $results_per_page = 5; #Number of results to display at a time
	public $total_results = 0; #Total number of records
	public $total_pages = 0; #Total number of pages

	public $paging_var 	= 'paged';	#String for link to go before the page number
	public $link_prefix	= '?paged='; #String for link to go before the page number
	public $link_suffix	= ''; #String for link to go after the page number
	public $page_nums_separator = ' | '; #String to go between the page number links

	################################
	# ERROR HOLDING VAR #
	################################
	public $error = null;

	################################
	# PAGINATION TEMPLATE DEFAULTS #
	################################
	public $tpl_first = '<a href="{link}">&laquo;</a> | ';
	public $tpl_last = ' | <a href="{link}">&raquo;</a> ';

	public $tpl_prev = '<a href="{link}">&lsaquo;</a> | ';
	public $tpl_next = ' | <a href="{link}">&rsaquo;</a> ';

	public $tpl_page_nums = '<span><a href="{link}">{page}</a></span>';
	public $tpl_cur_page_num = '<span>{page}</span>';

	/**
	 * In the above templates {link} is where the link will be inserted and {page} is
	 * where the page numbers will be inserted. Other than that, you can modify them
	 * as you please
	 *
	 * NOTE: You should have a separator of some sort at the right of $tpl_first and
	 * $tpl_prev as above in the defaults, and also have a separator of some sort
	 * before the $tpl_next and $tpl_last templates
	 **/


	##################################################################################

	public function __construct($table, $page = null, $query = '')
	{
		if (empty($table)) return false;

		if (null === $page) {
			$page = intval($_GET[$this->paging_var]) ?? 1;
		}

		#Check page number is a positive integer greater than 0 and assign it to $this->_current_page
		if ((int)$page > 0) $this->_current_page = (int)$page;

		$this->_table = $table;
		$this->_query = $query;

		$this->_db = Plugin::getConfig('connection');
	}

	/**
	 * pagination::paginate()
	 *
	 * Processes all values and query strings and if successful
	 * returns a string of html text for use with pagination bar
	 *
	 * @return string;
	 */
	public function paginate()
	{
		$output = '';

		$this->total_results = TableDataGetter::countElems($this->_table);
		$this->total_pages = ceil($this->total_results / $this->results_per_page);

		################################
		# IF TOTAL PAGES <= 1 RETURN 1 #
		################################
		if ($this->total_pages <= 1)
		{
			$this->_output = '1';
			return $this->_output;
		}

		###################################################
		# CHECK CURRENT PAGE ISN'T GREATER THAN MAX PAGES #
		###################################################
		if ($this->_current_page > $this->total_pages)
				$this->_current_page = $this->total_pages;

		######################################
		# SET FIRST AND LAST PAGE VALUES AND #
		# ERROR CHECK AGAINST INVALID VALUES #
		######################################
		$start = ($this->_current_page - $this->_padding > 0) ? $this->_current_page - $this->
				_padding : '1';
		$finish = ($this->_current_page + $this->_padding <= $this->total_pages) ? $this->
				_current_page + $this->_padding : $this->total_pages;

		###########################################
		# ADD FIRST TO OUTPUT IF CURRENT PAGE > 1 #
		###########################################
		if ($this->_current_page > 1) {
				$output .= preg_replace('/\{link\}/i', $this->link_prefix . '1' . $this->
					link_suffix, $this->tpl_first);
		}

		##########################################
		# ADD PREV TO OUTPUT IF CURRENT PAGE > 1 #
		##########################################
		if ($this->_current_page > 1) {
				$output .= preg_replace('/\{link\}/i', $this->link_prefix . ($this->
					_current_page - 1) . $this->link_suffix, $this->tpl_prev);
		}

		################################################
		# GET LIST OF LINKED NUMBERS AND ADD TO OUTPUT #
		################################################
		$nums = array();
		for ($i = $start; $i <= $finish; $i++)
		{
			if ($i == $this->_current_page) {
				$nums[] = preg_replace('/\{page\}/i', $i, $this->tpl_cur_page_num);
			} else {
				$get_vars = $_GET;
				$get_vars[$this->paging_var] = $i;

				$patterns = array('/\{link\}/i', '/\{page\}/i');
				// $replaces = array($this->link_prefix . $i . $this->link_suffix, $i);

				$paging_var = $this->paging_var;
				$new_get_vars = array_map(function($k, $v) use ($paging_var, $i) {
					// echo "tset";
					if ($k !== $paging_var) {
						return "{$k}={$v}";
					} else {
						return "{$k}={$i}";
					}
				}, array_keys($get_vars), $get_vars);

				$replaces = array('?' . implode('&', $new_get_vars), $i);

				$nums[] = preg_replace($patterns, $replaces, $this->tpl_page_nums);
			}
		}
		$output .= implode($this->page_nums_separator, $nums);

		##################################################
		# ADD NEXT TO OUTPUT IF CURRENT PAGE < MAX PAGES #
		##################################################
		if ($this->_current_page < $this->total_pages) {
				$output .= preg_replace('/\{link\}/i', $this->link_prefix . ($this->
					_current_page + 1) . $this->link_suffix, $this->tpl_next);
		}

		############################################
		# ADD LAST TO OUTPUT IF FINISH < MAX PAGES #
		############################################
		if ($this->_current_page < $finish) {
				$output .= preg_replace('/\{link\}/i', $this->link_prefix . $this->total_pages, $this->
					tpl_last);
		}

		$this->_output = $output;
		return $output;
	}
}