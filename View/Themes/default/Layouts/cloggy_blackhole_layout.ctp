<?php echo $this->Html->doctype('html5'); ?>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo $title_for_layout; ?></title>        
        <?php echo $this->CloggyAsset->getVendorHtmlTag('bootstrap/css/bootstrap.min.css','css'); ?>
        <?php echo $this->CloggyAsset->getCssHtmlTag('style.css'); ?>
        <?php echo $this->fetch('styles_top'); ?>
        <?php echo $this->fetch('scripts_top'); ?>
    </head>
    <body style="margin-top: 40px">

        <div class="container">
            <div class="row">
                    <div class="hero-unit">
                        <h1><?php echo __d('cloggy','Security Breach'); ?></h1>                        
                        <?php echo $content_for_layout; ?>	    
                    </div>
            </div>
        </div>                

        <!-- js -->
        <?php echo $this->CloggyAsset->getVendorHtmlTag('jquery.min.js', 'js'); ?>        
        <?php echo $this->fetch('cloggy_js_main'); ?>
        <!-- !js -->

    </body>
</html>