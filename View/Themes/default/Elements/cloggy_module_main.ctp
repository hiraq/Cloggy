<script type="text/javascript">
  var cloggy = new CloggyYepNope();  
	
  cloggy.setHost({
    bootstrap: '<?php echo $this->CloggyAsset->getVendorUrl('bootstrap/css/bootstrap.min.css'); ?>',
    bootstrapJs: '<?php echo $this->CloggyAsset->getVendorUrl('bootstrap/js/bootstrap.min.js'); ?>',
    jquery: '<?php echo $this->CloggyAsset->getVendorUrl('jquery-1.8.3.js'); ?>',	
  });
	
  cloggy.main(function() {
		
    //set host
    var host = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/' . Configure::read('Cloggy.theme_used') . '/', true); ?>';
		
    /*
        inject global + login css
     */
    yepnope.injectCss(host+'app/css/style.global.css');							
		
    //manipulate dom
    jQuery(document).ready(function() {
      jQuery('#cloggy-module').css('margin-top','60px');																	
    });
		
  });			
</script>