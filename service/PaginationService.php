<?php

class PaginationService extends BaseService {

	protected $serviceName = "PaginationService";
	
	function __construct($serviceManager) {
		parent::__construct($serviceManager);
	}

	function __destruct(){
		parent::__destruct();
	}
	
	protected function getServiceName(){
		return $this->serviceName;
	}
	
	// $limit is the same as "maxItemsPerPage"
	public function getPagination($total, $limit, $offset, $limitPagesPerGroup){
		
		$pagination = new Pagination($total, $limit, $offset, $limitPagesPerGroup);
		
		$page = $this->getPage($limit, $offset);
		$pagination->setCurrentPage($page);
		
		$firstItemInPage = $this->getFirstItemInPage($total, $offset);
		$pagination->setFirstItemInCurrentPage($firstItemInPage);
		
		$lastItemInPage = $this->getLastItemInPage($total, $limit, $offset);
		$pagination->setLastItemInCurrentPage($lastItemInPage);
		
		$maxItemsPerGroup = $this->getMaxItemsPerGroup($limitPagesPerGroup, $limit);
		$group = $this->getGroup($firstItemInPage, $maxItemsPerGroup);
		$pagination->setCurrentGroup($group);
		
		$hasPreviousGroup = $this->getHasPreviousGroup($group);
		$pagination->setHasPreviousGroup($hasPreviousGroup);
		
		$maxGroup = $this->getMaxGroup($total, $maxItemsPerGroup);
		$hasNextGroup = $this->getHasNextGroup($group, $maxGroup);
		$pagination->setHasNextGroup($hasNextGroup);
		
		$minItemInGroup = $this->getMinItemInGroup($group, $maxItemsPerGroup);
		$pagination->setMinItemInCurrentGroup($minItemInGroup);
		
		$maxItemInGroup = $this->getMaxItemInGroup($group, $maxItemsPerGroup);
		$pagination->setMaxItemInCurrentGroup($maxItemInGroup);
				
		$minPageInGroup = $this->getMinPageInGroup($minItemInGroup, $limit);
		$pagination->setMinPageInCurrentGroup($minPageInGroup);
		
		$maxPageInGroup = $this->getMaxPageInGroup($maxItemInGroup, $limit);	
		$pagination->setMaxPageInCurrentGroup($maxPageInGroup);
		
		$lastItemInGroup = $this->getLastItemInGroup($maxItemInGroup, $total);
		$pagination->setLastItemInCurrentGroup($lastItemInGroup);
		
		$lastPage = $this->getLastPage($total, $limit);
		$pagination->setLastPage($lastPage);
		
		$lastPageInGroup = $this->getLastPageInGroup($maxPageInGroup, $lastPage);
		$pagination->setLastPageInCurrentGroup($lastPageInGroup);
		
		$hasPreviousPage = $this->getHasPreviousPage($page);
		$pagination->setHasPreviousPage($hasPreviousPage);
		
		$hasNextPage = $this->getHasNextPage($page, $lastPage);
		$pagination->setHasNextPage($hasNextPage);
		
		$lastGroup = $this->getLastGroup($total, $maxItemsPerGroup);
		$pagination->setLastGroup($lastGroup);
		
		$pages = $this->getPages($minPageInGroup, $lastPageInGroup, $page, $total, $limit, $offset);
		$pagination->setPages($pages);
		
		$previousGroupOffset = $this->getPreviousGroupOffset($hasPreviousGroup, $group, $maxItemsPerGroup);
		$pagination->setPreviousGroupOffset($previousGroupOffset);
		
		$nextGroupOffset = $this->getNextGroupOffset($hasNextGroup, $group, $maxItemsPerGroup);
		$pagination->setNextGroupOffset($nextGroupOffset);
		
		return $pagination;
		
	}
	
	
/*
    [lastPage:Pagination:private] => 8
    [currentPage:Pagination:private] => 8
    [hasPreviousPage:Pagination:private] => 1
    [hasNextPage:Pagination:private] => 
    [firstItemInCurrentPage:Pagination:private] => 106
    [lastItemInCurrentPage:Pagination:private] => 113
    [groups:Pagination:private] => 
    [lastGroup:Pagination:private] => 1
    [currentGroup:Pagination:private] => 1
    [hasPreviousGroup:Pagination:private] => 
    [hasNextGroup:Pagination:private] => 
    [minItemInCurrentGroup:Pagination:private] => 1
    [maxItemInCurrentGroup:Pagination:private] => 225
    [lastItemInCurrentGroup:Pagination:private] => 113
    [minPageInCurrentGroup:Pagination:private] => 1
    [maxPageInCurrentGroup:Pagination:private] => 15
    [lastPageInCurrentGroup:Pagination:private] => 8
*/

	public function getPaginationAsArray($total, $limit, $offset, $limitPagesPerGroup){
		$pagination = $this->getPagination($total, $limit, $offset, $limitPagesPerGroup);
		$paginationArray = array(
			"total"=>$pagination->getTotal(),
			"hasPreviousGroup"=>$pagination->hasPreviousGroup(),
			"previousGroupOffset"=>$pagination->getPreviousGroupOffset(),
			"hasNextGroup"=>$pagination->hasNextGroup(),
			"nextGroupOffset"=>$pagination->getNextGroupOffset(),
			"currentGroup"=>$pagination->getCurrentGroup(),
			"pages"=>$pagination->getPages(),
			"currentPage"=>$pagination->getCurrentPage(),
			"firstItemInCurrentPage"=>$pagination->getFirstItemInCurrentPage(),
			"lastItemInCurrentPage"=>$pagination->getLastItemInCurrentPage()			
		);
		return $paginationArray;
	}
	
	public function getPages($minPageInGroup, $lastPageInGroup, $currentPage, $total, $limit, $offset){
		$pages = array();
		$lastPage = $this->getLastPage($total, $limit);		
		for($pageNumber = $minPageInGroup; $pageNumber <= $lastPageInGroup; $pageNumber++){
			$pageOffset = $this->getOffset($pageNumber, $limit);
			array_push($pages, 
				array(
					"limit"=>$limit,
					"offset"=>$pageOffset,
					"pageNumber"=>$pageNumber,
					"currentPage"=>($pageNumber == $currentPage),
					"firstItem"=>$this->getFirstItemInPage($total, $pageOffset),
					"lastItem"=>$this->getLastItemInPage($total, $limit, $pageOffset),
					"hasPreviousPage"=>$this->getHasPreviousPage($pageNumber),
					"hasNextPage"=>$this->getHasNextPage($pageNumber, $lastPage)
					)
				);
				
		}
		return $pages;
	}	
	

	public function getPreviousGroupOffset($hasPreviousGroup, $group, $maxItemsPerGroup){
		if ($hasPreviousGroup){
			return $maxItemsPerGroup * ($group - 2);
		} else {
			return null; // not possible
		}
	}
	
	public function getNextGroupOffset($hasNextGroup, $group, $maxItemsPerGroup){
		if ($hasNextGroup){
			return $maxItemsPerGroup * $group;
		} else {
			return null; // not possible
		}
	}
	
	public function getOffset($page, $limit){
		return ($page-1)*$limit;
	}
	
	public function getPage($limit, $offset){
		return ($offset / $limit) + 1;
	}
	
	public function getFirstItemInPage($total, $offset){
		if ($offset + 1  < $total){
			return $offset + 1;
		} else {
			//return null; // not possible
			return 1;
		}
	}
	
	public function getLastItemInPage($total, $limit, $offset){
		if ($offset + $limit < $total){
			return $offset + $limit;
		} else {
			if ($offset < $total){
				return $total;
			} else {
				return null; // not possible
			}
		}
	}
	
	public function getMaxItemsPerGroup($limitPagesPerGroup, $limit){
		return $limitPagesPerGroup * $limit;
	}
	
	public function getGroup($firstItemInPage, $maxItemsPerGroup){
		return ceil($firstItemInPage / $maxItemsPerGroup);
	}
	
	public function getMaxGroup($total, $maxItemsPerGroup){
		return ceil($total / $maxItemsPerGroup);
	}
	
	public function getHasPreviousGroup($group){
		return ($group > 1);
	}
	
	public function getHasNextGroup($group, $maxGroup){
		return ($group < $maxGroup);
	}
	
	public function getMinItemInGroup($group, $maxItemsPerGroup){
		return ($group * $maxItemsPerGroup - $maxItemsPerGroup + 1);
	}
	
	public function getMaxItemInGroup($group, $maxItemsPerGroup){
		return ($group * $maxItemsPerGroup);
	}
	
	public function getMinPageInGroup($minItemInGroup, $limit){
		$minItemInGroup = ($minItemInGroup < 1) ? 1 : $minItemInGroup;
		return ceil($minItemInGroup / $limit);
	}
	
	public function getMaxPageInGroup($maxItemInGroup, $limit){
		return ($maxItemInGroup / $limit);
	}
	
	public function getLastItemInGroup($maxItemInGroup, $total){
		if ($maxItemInGroup < $total){ 
			return $maxItemInGroup;
		} else {
			return $total;
		}
	}
	
	public function getLastPage($total, $limit){
		return ceil($total / $limit);
	}
	
	public function getLastPageInGroup($maxPageInGroup, $lastPage){
		if ($maxPageInGroup < $lastPage){
			return $maxPageInGroup;
		} else {
			return $lastPage;
		}
	}
	
	public function getHasPreviousPage($page){
		return ($page > 1);
	}
	
	public function getHasNextPage($page, $lastPage){
		return ($page < $lastPage);		
	}
	
	public function getLastGroup($total, $maxItemsPerGroup){
		return ceil($total / $maxItemsPerGroup);
	}
	
}

class Pagination {
	
	private $total;
	private $limit;
	private $offset;
	
	private $limitPagesPerGroup;
	
	private $pages; 
	private $lastPage; // first page is 1
	
	private $currentPage;
	private $hasPreviousPage; 
	private $hasNextPage; 

	private $firstItemInCurrentPage;
	private $lastItemInCurrentPage; // actual last item
	
	private $groups;
	private $lastGroup; // first group is 1
	
	private $currentGroup;
	private $hasPreviousGroup;
	private $hasNextGroup;
	
	private $previousGroupOffset;
	private $nextGroupOffset;

	private $minItemInCurrentGroup;
	private $maxItemInCurrentGroup; // maximum last item (not the actual last item, which could be less if $total is taken into account -- see $lastItemInCurrentGroup)
	private $lastItemInCurrentGroup;
	
	private $minPageInCurrentGroup;
	private $maxPageInCurrentGroup; // maxium last page (not the actual last page, which could be less if $total is taken into account -- see $lastPageInCurrentGroup)
	private $lastPageInCurrentGroup;
	
	
	public function __construct($total, $limit, $offset, $limitPagesPerGroup){
		$this->total = $total;
		$this->limit = $limit;
		$this->offset = $offset;
		$this->limitPagesPerGroup = $limitPagesPerGroup;
	}
	
	public function getTotal(){
		return $this->total;
	}
	
	public function getLimit(){
		return $this->limit;
	}
	
	public function getOffset(){
		return $this->offset;
	}
	
	public function getLimitPagesPerGroup(){
		return $this->limitPagesPerGroup;
	}
	
	public function setHasPreviousGroup($hasPreviousGroup){
		$this->hasPreviousGroup = $hasPreviousGroup;
	}
	
	public function hasPreviousGroup(){
		return $this->hasPreviousGroup;
	}
	
	public function setHasNextGroup($hasNextGroup){
		$this->hasNextGroup = $hasNextGroup;
	}
	
	public function hasNextGroup(){
		return $this->hasNextGroup;
	}
	
	public function setPreviousGroupOffset($previousGroupOffset){
		$this->previousGroupOffset = $previousGroupOffset;
	}
	
	public function getPreviousGroupOffset(){
		return $this->previousGroupOffset;
	}

	public function setNextGroupOffset($nextGroupOffset){
		$this->nextGroupOffset = $nextGroupOffset;
	}
	
	public function getNextGroupOffset(){
		return $this->nextGroupOffset;
	}
	
	public function setPages($pages){
		$this->pages = $pages;
	}
	
	public function getPages(){
		return $this->pages;
	}
	
	public function setCurrentPage($currentPage){
		$this->currentPage = $currentPage;
	}
	
	public function getCurrentPage(){
		return $this->currentPage;
	}
	
	public function setLastPage($lastPage){
		$this->lastPage = $lastPage;
	}
	
	public function getLastPage(){
		return $this->lastPage;
	}
	
	public function setFirstItemInCurrentPage($firstItemInCurrentPage){
		$this->firstItemInCurrentPage = $firstItemInCurrentPage;
	}
	
	public function getFirstItemInCurrentPage(){
		return $this->firstItemInCurrentPage;
	}
	
	public function setLastItemInCurrentPage($lastItemInCurrentPage){
		$this->lastItemInCurrentPage = $lastItemInCurrentPage;
	}
	
	public function getLastItemInCurrentPage(){
		return $this->lastItemInCurrentPage;
	}

	public function setCurrentGroup($currentGroup){
		$this->currentGroup = $currentGroup;
	}
	
	public function getCurrentGroup(){
		return $this->currentGroup;
	}
	
	public function setLastGroup($lastGroup){
		$this->lastGroup = $lastGroup;
	}
	
	public function getLastGroup(){
		return $this->lastGroup;
	}
	
	
	public function setMinItemInCurrentGroup($minItemInCurrentGroup){
		$this->minItemInCurrentGroup = $minItemInCurrentGroup;
	}
	
	public function getMinItemInCurrentGroup(){
		return $this->minItemInCurrentGroup;
	}
	
	public function setMaxItemInCurrentGroup($maxItemInCurrentGroup){
		$this->maxItemInCurrentGroup = $maxItemInCurrentGroup;
	}
	
	public function getMaxItemInCurrentGroup(){
		return $this->maxItemInCurrentGroup;
	}
	
	public function setMinPageInCurrentGroup($minPageInCurrentGroup){
		$this->minPageInCurrentGroup = $minPageInCurrentGroup;
	}
	
	public function getMinPageInCurrentGroup(){
		return $this->minPageInCurrentGroup;
	}
	
	public function setMaxPageInCurrentGroup($maxPageInCurrentGroup){
		$this->maxPageInCurrentGroup = $maxPageInCurrentGroup;
	}
	
	public function getMaxPageInCurrentGroup(){
		return $this->maxPageInCurrentGroup;
	}
	
	public function setLastItemInCurrentGroup($lastItemInCurrentGroup){
		$this->lastItemInCurrentGroup = $lastItemInCurrentGroup;
	}
	
	public function getLastItemInCurrentGroup(){
		return $this->lastItemInCurrentGroup;
	}
	
	public function setLastPageInCurrentGroup($lastPageInCurrentGroup){
		$this->lastPageInCurrentGroup = $lastPageInCurrentGroup;
	}
	
	public function getLastPageInCurrentGroup(){
		return $this->lastPageInCurrentGroup;
	}
	
	public function setHasPreviousPage($hasPreviousPage){
		$this->hasPreviousPage = $hasPreviousPage;
	}
	
	public function hasPreviousPage(){
		return $this->hasPreviousPage;
	}
	
	public function setHasNextPage($hasNextPage){
		$this->hasNextPage = $hasNextPage;
	}
	
	public function hasNextPage(){
		return $this->hasNextPage;
	}

	
}

// // $limit is the same as "maxItemsPerPage"
//	public function getPagination($total, $limit, $offset, $limitPagesPerGroup){
/*

	public function getPaginationAsArray($total, $limit, $offset, $limitPagesPerGroup){
		$pagination = $this->getPagination($total, $limit, $offset, $limitPagesPerGroup);
		$paginationArray = array(
			"total"=>$pagination->getTotal(),
			"hasPreviousGroup"=>$pagination->hasPreviousGroup(),
			"previousGroupOffset"=>$pagination->getPreviousGroupOffset(),
			"hasNextGroup"=>$pagination->hasNextGroup(),
			"nextGroupOffset"=>$pagination->getNextGroupOffset(),
			"currentGroup"=>$pagination->getCurrentGroup(),
			"pages"=>$pagination->getPages(),
			"currentPage"=>$pagination->getCurrentPage(),
			"firstItemInCurrentPage"=>$pagination->getFirstItemInCurrentPage(),
			"lastItemInCurrentPage"=>$pagination->getLastItemInCurrentPage()			
		);
		return $paginationArray;
	}
	*/
/*
$paginationService = new PaginationService();
$total = 1;
$limit = 15;
$offset = 0;
$limitPagesPerGroup = 1;
#$pagination = $paginationService->getPagination($total, $limit, $offset, $limitPagesPerGroup);
$paginationAsArray = $paginationService->getPaginationAsArray($total, $limit, $offset, $limitPagesPerGroup);
print_r($paginationAsArray);
*/

?>