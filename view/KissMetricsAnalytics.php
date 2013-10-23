<?php

class KissMetricsAnalytics extends Component {
    
     function __construct(ApplicationView $view) {
        parent::__construct($view);
    }
    
    public function display(){
	?>
	  <script>
	  	var _kmq = _kmq || [];
		var _kmk = _kmk || '<?=$this->getView()->getKissMetricsApiKey() ?>';
		function _kms(u){
			setTimeout(function(){
				var d = document, f = d.getElementsByTagName('script')[0],
				s = d.createElement('script');
				s.type = 'text/javascript'; s.async = true; s.src = u;
				f.parentNode.insertBefore(s, f);
				}, 1);
		}
		_kms('//i.kissmetrics.com/i.js');
		_kms('//doug1izaerwt3.cloudfront.net/' + _kmk + '.1.js');
		<?php if ($this->getView() instanceof Identifiable && !is_null($this->getView()->getPrincipal())){ ?>
			_kmq.push(['identify', '<?=$this->getView()->getPrincipal()->getEmail()?>']);
		<?php } ?>
		_kmq.push(['record', 'Accessed <?=$this->getView()->getId()?> view.']);
	  </script>
	<?php 	
    }
}

?>