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
    <?php echo $this->CloggyAsset->getVendorHtmlTag('yepnope/yepnope.1.5.4-min.js', 'js'); ?>
    <?php echo $this->CloggyAsset->getJsHtmlTag('cloggy.global.js'); ?>
    <?php echo $this->fetch('cloggy_js_main'); ?>
    <!-- !js -->

  </body>
</html>