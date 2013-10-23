<?php

class GoogleAnalytics extends Component {
    
     function __construct(ApplicationView $view) {
        parent::__construct($view);
    }
    
    public function display(){
	?>
	  <script>
	    var _gaq=[["_setAccount","<?=$this->getView()->getGoogleWebPropertyId() ?>"],["_trackPageview"]];  
	    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
	    g.src=("https:"==location.protocol?"//ssl":"//www")+".google-analytics.com/ga.js";
	    s.parentNode.insertBefore(g,s)}(document,"script"));
	  </script>
	<?php 	
    }
}

?>