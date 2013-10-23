<?php

abstract class EditableComponent extends Component implements Editable {
    
	private $overlayHref;
	protected $editable = true;
	
    public function __construct($view){
	    parent::__construct($view);   
    }
    
    public function getOverlayHref(){ 
    	return $this->overlayHref;
    }
    
    public function setOverlayHref($overlayHref){
    	$this->overlayHref = $overlayHref;
    }
    
    public function displayOpeningWrapperDiv($classes){
		?>
    	<div id="<?=$this->getId()?>_wrapper" class="<? if ($this->editable) { ?>editable<? } ?> <?=$classes?>" <?php if ($this->getView()->loggedIn()) { ?>rel="<?=$this->getId()?>_overlay" href="<?=$this->getOverlayHref()?>"<?php } ?>>
    	<?php 
    }
	
}