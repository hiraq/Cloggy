<div id="cloggy-dashboard">

    <div class="container">

        <div class="navbar">
            <div class="navbar-inner">
                <a class="brand" href="<?php echo $this->request->here; ?>">Cloggy</a>
                <ul class="nav">					
                    <?php $menus = $this->CloggyMenus->menu('cloggy'); ?>
                    <?php if (!empty($menus)) : ?>						
                        <?php foreach ($menus as $menu => $link) : ?>
                            <li><?php echo $this->CloggyMenus->getLink($menu, $link); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>			
                <form class="navbar-search pull-right" id="form-search">
                    <input type="text" id="module_q" class="search-query" placeholder="<?php echo __d('cloggy','Search'); ?>">
                </form>
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

                <?php echo $this->Session->flash('dashNotif'); ?>
                
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo __d('cloggy','Module Name'); ?></th>
                            <th><?php echo __d('cloggy','Description'); ?></th>
                            <th><?php echo __d('cloggy','Author'); ?></th>							
                            <th><?php echo __d('cloggy','Dependency'); ?></th>                            
                            <th><?php echo __d('cloggy','Install'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($modules) && !empty($modules)) : ?>
                            <?php foreach ($modules as $module) : ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/' . Inflector::underscore($module['name'])); ?>">
                                            <?php echo $module['name']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo $module['desc']; ?></td>								
                                    <td>
                                        <?php if (filter_var($module['url'], FILTER_VALIDATE_URL)) : ?>
                                            <?php echo $this->Html->link($module['author'], $module['url'], array('target' => '__blank')); ?>
                                        <?php else: ?>
                                            <?php echo $module['author']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo $module['dep']; ?> 
                                        <?php
                                            if(isset($brokenModules) && in_array($module['name'],$brokenModules)) {
                                                echo  '<span class="label label-important">'.__d('cloggy','Broken').'</span>';
                                            }
                                        ?>
                                    </td>         
                                    <td>
                                        <?php
                                        if ($module['installed']) {
                                            echo  '<span class="label">'.__d('cloggy','Installed').'</span>';
                                        } else {
                                            
                                            if ($module['install']) {                                            
                                                echo $this->Html->link(__d('cloggy','Install'),$module['install_link']);                                            
                                            } else {
                                                echo '-';
                                            }
                                            
                                        }
                                        ?>
                                    </td>
                                </tr>						
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5"><?php echo __d('cloggy','No modules registered'); ?></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

</div>

<?php echo $this->start('cloggy_js_main'); ?>
<script type="text/javascript">
    //manipulate dom
    jQuery(document).ready(function() {
        jQuery('body').css('margin-top','20px');			

        /*
        search on page
         */
        jQuery('#form-search').on('submit',function(e) {

            e.preventDefault();
            var q = jQuery('#module_q').val();
            jQuery('td').removeAttr('style');
            jQuery('td:contains("'+q+'")').css('background-color','#F2F0F0');

        });		

    });	
</script>
<?php echo $this->end(); ?>