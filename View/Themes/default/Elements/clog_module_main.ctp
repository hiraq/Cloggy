<script type="text/javascript">
	var clog = new ClogYepNope();  
	
	clog.setHost({
		bootstrap: '<?php echo $this->ClogAsset->getVendorUrl('bootstrap/css/bootstrap.min.css'); ?>',
		bootstrapJs: '<?php echo $this->ClogAsset->getVendorUrl('bootstrap/js/bootstrap.min.js'); ?>',
		jquery: '<?php echo $this->ClogAsset->getVendorUrl('jquery-1.8.3.js'); ?>',	
	});
	
	clog.main(function() {
		
		//set host
		var host = '<?php echo Router::url('/'.Configure::read('Clog.url_prefix').'/'.Configure::read('Clog.theme_used').'/',true); ?>';
		
		/*
		inject global + login css
		*/
		yepnope.injectCss(host+'app/css/style.global.css');							
		
		//manipulate dom
		jQuery(document).ready(function() {
			jQuery('#clog-module').css('margin-top','60px');																	
		});
		
	});			
</script>