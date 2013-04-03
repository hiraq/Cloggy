<?php echo $this->Html->doctype('html5'); ?>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $title_for_layout; ?></title>        
        <?php echo $this->CloggyAsset->getVendorHtmlTag('bootstrap/css/bootstrap.min.css','css'); ?>
        <?php echo $this->CloggyAsset->getCssHtmlTag('style.css'); ?>
    </head>
    <body>

        <?php echo $content_for_layout; ?>	    

        <!-- js -->
        <?php echo $this->CloggyAsset->getVendorHtmlTag('jquery-1.8.3.js', 'js'); ?>        
        <?php echo $this->fetch('cloggy_js_main'); ?>
        <!-- !js -->

    </body>
</html>