<?php echo $this->element('noscript'); ?>

<!-- login form -->
<div id="cloggy-form" style="display: none">

  <div class="container">
    <div class="row">
      <div class="span3"></div>
      <div class="span4">

        <?php
        echo $this->Form->create('CloggyUser', array(
            'class' => 'form-horizontal',
            'url' => '/' . Configure::read('Cloggy.url_prefix') . '/login'));
        ?>

        <fieldset>
          <div class="control-group">
            <div class="controls">
              <legend>
                <strong>Cloggy Administrator</strong>
              </legend>
              <span>
<?php echo $this->Session->flash('install_success'); ?>
<?php echo $this->Session->flash('auth'); ?>
              </span>
            </div>
          </div>
          <div class="control-group" id="user_name">
            <label class="control-label" for="inputEmail">Username</label>
            <div class="controls">								
<?php echo $this->Form->input('user_name', array('type' => 'text', 'label' => false, 'placeholder' => 'username')); ?>
              <span class="help-inline" id="user_name_help" style="display:none"></span>							
            </div>
          </div>						
          <div class="control-group" id="user_password">
            <label class="control-label" for="inputPassword">Password</label>
            <div class="controls">								
<?php echo $this->Form->input('user_password', array('type' => 'password', 'label' => false, 'placeholder' => 'password')); ?>
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
      <div class="span5"></div>
    </div>
  </div>

</div>
<!-- !login form -->

<?php echo $this->start('cloggy_js_main'); ?>
<script type="text/javascript">
  var cloggygy = new CloggyYepNope();  
	
  cloggygy.setHost({
    bootstrap: '<?php echo $this->CloggyAsset->getVendorUrl('bootstrap/css/bootstrap.min.css'); ?>',
    bootstrapJs: '<?php echo $this->CloggyAsset->getVendorUrl('bootstrap/js/bootstrap.min.js'); ?>',
    jquery: '<?php echo $this->CloggyAsset->getVendorUrl('jquery-1.8.3.js'); ?>',	
  });
	
  cloggygy.main(function() {
		
    //set host
    var host = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/' . Configure::read('Cloggy.theme_used') . '/', true); ?>';
		
    /*
        inject global + login css
     */
    yepnope.injectCss(host+'app/css/style.global.css');							
		
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
		
              });			
</script>
<?php echo $this->end(); ?>
