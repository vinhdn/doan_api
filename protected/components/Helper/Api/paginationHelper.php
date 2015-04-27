<?php
	/**
	 * Pagination HELPER FUNCTIONS
	 * @author Do Cong Bang docongbang1993@gmail.com
	 */
	class paginationHelper {
		
		function __construct() {
			
		}
		public function paging($total_rows, $cur_page, $per_page=10, $max_page = 5)
		{	
			$total_pages	=	ceil($total_rows/$per_page);
			
			$start			=	(ceil($cur_page/$max_page) - 1) * $max_page + 1;
			
			$prev 			= 	($start > $max_page) ? ($start - 1) : 0 ;
			
			$end;
			
			if ($total_pages < $max_page) 
			{
				$end	=	$total_pages;	
			} 
			else 
			{
				$end 	= 	( ($start + $max_page - 1) > $total_pages) ? $total_pages : ($start + $max_page - 1) ;
			}
			
			$next 	= 	( $end < $total_pages ) ? $end + 1 : 0;
			$paging	=	array();
			
			$paging['first'] 	= 	1;
			$paging['prev']		=	$prev;
			$paging['pages'] 	= 	array();
			for ($i=$start; $i < $end + 1; $i++) 
			{ 
				$paging['pages'][]	=	$i;
			}
			$paging['next']		=	$next;
			$paging['current']	=	$cur_page;
			$paging['last']		=	$total_pages;
			return $paging;
		}
	}
	
?>