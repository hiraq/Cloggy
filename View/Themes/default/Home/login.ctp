<?php echo $this->element('noscript'); ?>

<!-- login form -->
<div id="cloggy-form" style="display: none">

    <div class="container">
        <div class="row">
<!--            <div class="span3"></div>-->
            <div class="span4 offset2">

                <?php
                echo $this->Form->create('CloggyUser', array(
                    'class' => 'form-horizontal',
                    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/login'));
                ?>

                <fieldset>
                    <div class="control-group">
                        <div class="controls">
                            <legend>
                                <strong><?php echo __d('cloggy','Cloggy Administrator'); ?></strong>
                            </legend>
                            <span>
                                <?php echo $this->Session->flash('install_success'); ?>
                                <?php echo $this->Session->flash('auth'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="control-group" id="user_name">
                        <label class="control-label" for="inputEmail"><?php echo __d('cloggy','Username'); ?></label>
                        <div class="controls">								
                            <?php echo $this->Form->input('user_name', array('type' => 'text', 'label' => false, 'placeholder' => __d('cloggy','username'))); ?>
                            <span class="help-inline" id="user_name_help" style="display:none"></span>							
                        </div>
                    </div>						
                    <div class="control-group" id="user_password">
                        <label class="control-label" for="inputPassword"><?php echo __d('cloggy','Password'); ?></label>
                        <div class="controls">								
                            <?php echo $this->Form->input('user_password', array('type' => 'password', 'label' => false, 'placeholder' => __d('cloggy','password'))); ?>
                            <span class="help-inline" id="user_password_help" style="display:none"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">								
                            <input type="submit" class="btn btn-primary" value="Sign In" name="submit" />
                        </div>
                    </div>
                </fieldset>
                <?php echo $this->Form->end(); ?>

            </div>
<!--            <div class="span6"></div>-->
        </div>
    </div>

</div>
<!-- !login form -->

<?php echo $this->start('cloggy_js_main'); ?>
<script type="text/javascript">
    //manipulate dom
    jQuery(document).ready(function() {
        jQuery('#cloggy-form').delay(200).fadeIn(1000).css('margin-top','150px');

        <?php if (isset($errors) && !empty($errors)) : ?>
                        var errors = '<?php echo json_encode($errors); ?>';
                        errors = jQuery.parseJSON(errors);
                        jQuery.each(errors,function(k,v) {
                            jQuery('#'+k).addClass('error');
                            jQuery('#'+k+'_help').show().html('');
                            jQuery('#'+k+'_help').html(v.toString());
                            window.setTimeout(function() {					
                                jQuery('#'+k+'_help').fadeOut('slow');
                                jQuery('#'+k+'_help').html('');
                                jQuery('#'+k).removeClass('error');
                            },3500);
                        }); 
        <?php endif; ?>

    });	
</script>
<?php echo $this->end(); ?>
