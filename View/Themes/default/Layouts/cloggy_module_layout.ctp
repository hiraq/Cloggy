<?php echo $this->Html->doctype('html5'); ?>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php echo $title_for_layout; ?></title>
	</head>
	<body>
				
    	<div id="cloggy-module">
			
			<div class="container">								
				
				<div class="row">
					
					<div class="span2">
						<ul class="nav nav-tabs nav-stacked">
						 	<?php $menus = $this->CloggyMenus->menu('cloggy'); ?>
							<?php if(!empty($menus)) : ?>
								<li class="nav-header">Cloggy</li>
								<?php foreach($menus as $menu => $link) :?>
								<li><?php echo $this->CloggyMenus->getLink($menu,$link); ?></li>
								<?php endforeach; ?>
							<?php endif; ?>
						</ul>
						<ul class="nav nav-tabs nav-stacked">
							 <li class="nav-header"><?php echo ucfirst(strtolower($moduleName)); ?></li>
							 
							<?php if(isset($moduleKeyMenus) && !empty($moduleKeyMenus)) :?>
								<?php $this->start('module_menus'); ?>
								<?php echo $this->element('cloggy_module_menus'); ?>
								<?php $this->end(); ?>
							<?php endif; ?>							
							
							<?php echo $this->fetch('module_menus'); ?>
										 							 							 							
						</ul>
					</div>
					
					<div class="span10">						
						<?php echo $this->fetch('content'); ?>											
					</div>
					
				</div>
				
			</div>
			
    	</div>  
		
		<!-- js -->
		
		<?php $this->start('cloggy_js_module_main'); ?>
		<?php echo $this->element('cloggy_module_main'); ?>
		<?php $this->end(); ?>
		
		<?php echo $this->CloggyAsset->getVendorHtmlTag('yepnope/yepnope.1.5.4-min.js','js'); ?>
		<?php echo $this->CloggyAsset->getJsHtmlTag('cloggy.global.js'); ?>			
		<?php echo $this->fetch('cloggy_js_module_main'); ?>				
		<?php echo $this->fetch('cloggy_js_module_page'); ?>
		<!-- !js -->
		
	</body>
</html>