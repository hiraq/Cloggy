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
    <body>

        <div id="cloggy-module">

            <div class="container">	

                <div class="navbar">
                    <div class="navbar-inner">
                        <a class="brand" href="<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/dashboard'); ?>">Cloggy</a>
                        <ul class="nav">	

                            <?php $menus = $this->CloggyMenus->menu('cloggy'); ?>
                            <?php if (!empty($menus)) : ?>						
                                <?php foreach ($menus as $menu => $link) : ?>
                                    <?php
                                    if (is_array($link)) : $liClass = 'class="dropdown"';
                                    else: $liClass = '';
                                    endif;
                                    ?>

                                    <li <?php echo $liClass; ?>>
                                        <?php if (!is_array($link)) : ?>
                                            <?php echo $this->CloggyMenus->getLink($menu, $link); ?>
                                        <?php else: ?>
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $menu; ?><b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($link as $key => $value) : ?>
                                                    <li><?php echo $this->CloggyMenus->getLink($key, $value); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>

                                <?php endforeach; ?>
                            <?php endif; ?>

                            <li class="divider-vertical"></li>

                            <?php $moduleMenus = $this->CloggyMenus->menu('module');  ?>
                            <?php if (!empty($moduleMenus)) : ?>						
                                <?php foreach ($moduleMenus as $menu => $link) : ?>
                                    <?php
                                    if (is_array($link)) : $liClass = 'class="dropdown"';
                                    else: $liClass = '';
                                    endif;
                                    ?>

                                    <li <?php echo $liClass; ?>>
                                        <?php if (!is_array($link)) : ?>
                                            <?php echo $this->CloggyMenus->getLink($menu, $link); ?>
                                        <?php else: ?>
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $menu; ?><b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($link as $key => $value) : ?>
                                                    <li><?php echo $this->CloggyMenus->getLink($key, $value); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>

                                <?php endforeach; ?>
                            <?php endif; ?>

                        </ul>									
                    </div>			
                </div>							

                <div class="row">

                    <div class="span2">

                        <?php $menus = $this->CloggyMenus->groups(); ?>
                        <?php if (!empty($menus)) : ?>

                            <?php foreach ($menus as $name => $data) : ?>
                                <ul class="nav nav-tabs nav-stacked">
                                    <li class="nav-header"><?php echo $name; ?></li>
                                    <?php foreach ($data as $anchor => $link) : ?>
                                        <li><?php echo $this->CloggyMenus->getLink($anchor, $link); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endforeach; ?>
                        <?php endif; ?>

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

        <?php echo $this->CloggyAsset->getVendorHtmlTag('jquery-1.8.3.js', 'js'); ?>        
        <?php echo $this->CloggyAsset->getVendorHtmlTag('underscore-1.4.2.js', 'js'); ?>    
        <?php echo $this->CloggyAsset->getVendorHtmlTag('bootstrap/js/bootstrap.min.js','js'); ?>
        
        <?php echo $this->fetch('cloggy_js_module_main'); ?>				
        <?php echo $this->fetch('cloggy_js_module_page'); ?>
        <!-- !js -->

    </body>
</html>