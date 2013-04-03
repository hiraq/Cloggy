<?php echo $this->element('noscript'); ?>

<!-- install form -->
<div id="cloggy-form" style="display: none">

    <div class="container">
        <div class="row">
            <div class="span3"></div>
            <div class="span4">

                <?php
                echo $this->Form->create('CloggyUser', array(
                    'class' => 'form-horizontal',
                    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/install'));
                ?>

                <fieldset>
                    <div class="control-group">
                        <div class="controls">
                            <legend>
                                <strong>Setup Administrator</strong>
                            </legend>
                        </div>
                    </div>
                    <div class="control-group" id="user_name">
                        <label class="control-label" for="inputEmail">Username</label>
                        <div class="controls">
                            <input type="text" id="inputEmail" placeholder="Username"
                                   name="data[CloggyUser][user_name]">	
                            <span class="help-inline" id="user_name_help" style="display:none"></span>							
                        </div>
                    </div>
                    <div class="control-group" id="user_email">
                        <label class="control-label" for="inputEmail">Email</label>
                        <div class="controls">
                            <input type="text" id="inputEmail" placeholder="email@address.com"
                                   name="data[CloggyUser][user_email]">
                            <span class="help-inline" id="user_email_help" style="display:none"></span>
                        </div>
                    </div>
                    <div class="control-group" id="user_password">
                        <label class="control-label" for="inputPassword">Password</label>
                        <div class="controls">
                            <input type="password" id="inputPassword" placeholder="Password"
                                   name="data[CloggyUser][user_password]">
                            <span class="help-inline" id="user_password_help" style="display:none"></span>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">								
                            <input type="submit" class="btn btn-primary" value="Setup" name="submit" />
                        </div>
                    </div>
                </fieldset>
                <?php echo $this->Form->end(); ?>

            </div>
            <div class="span5"></div>
        </div>
    </div>

</div>
<!-- !install form -->

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
