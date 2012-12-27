<?php echo $this->Html->doctype('html5'); ?>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php echo $title_for_layout; ?></title>
	</head>
	<body>
				
    	<?php echo $content_for_layout; ?>	    
		
		<!-- js -->
		<?php echo $this->ClogAsset->getVendorHtmlTag('yepnope/yepnope.1.5.4-min.js','js'); ?>
		<?php echo $this->ClogAsset->getJsHtmlTag('clog.global.js'); ?>
		<?php echo $this->fetch('clog_js_main'); ?>
		<!-- !js -->
		
	</body>
</html>