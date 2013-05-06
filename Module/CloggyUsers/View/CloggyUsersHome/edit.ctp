<?php
echo $this->Form->create('CloggyUser', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/edit/' . $id,
    'class' => 'form-horizontal'
));
?>
<fieldset>
    <legend><?php echo __d('cloggy','Edit'); ?> '<?php echo $user['CloggyUser']['user_name']; ?>'</legend>				

    <?php $flash = $this->Session->flash('success'); ?>
    <?php if (!empty($flash)) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $flash; ?>
        </div>
    <?php endif; ?>

    <div class="control-group <?php
    if (isset($errors['user_name'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','Username'); ?></label>
        <div class="controls">
            <?php
            echo $this->Form->input('user_name', array(
                'label' => false,
                'placeholder' => __d('cloggy','username'),
                'type' => 'text',
                'value' => $user['CloggyUser']['user_name'],
                'div' => false)
            );
            ?>
            <span class="help-inline"><?php
            if (isset($errors['user_name'])) : echo $errors['user_name'][0];
            endif;
            ?></span>
        </div>
    </div>
    <div class="control-group <?php
            if (isset($errors['user_email'])) : echo 'error';
            endif;
            ?>">
        <label class="control-label"><?php echo __d('cloggy','Email'); ?></label>
        <div class="controls">
            <?php
            echo $this->Form->input('user_email', array(
                'label' => false,
                'placeholder' => __d('cloggy','email address'),
                'type' => 'email',
                'value' => $user['CloggyUser']['user_email'],
                'div' => false)
            );
            ?>
            <span class="help-inline"><?php
                if (isset($errors['user_email'])) : echo $errors['user_email'][0];
                endif;
            ?></span>
        </div>
    </div>
    <div class="control-group <?php
    if (isset($errors['user_password'])) : echo 'error';
    endif;
            ?>">
        <label class="control-label"><?php echo __d('cloggy','New Password'); ?></label>
        <div class="controls">
<?php echo $this->Form->input('user_password', array('label' => false, 'placeholder' => __d('cloggy','password'), 'type' => 'password', 'div' => false)); ?>
            <span class="help-inline"><?php
if (isset($errors['user_password'])) : echo $errors['user_password'][0];
endif;
?></span>
        </div>
    </div>
    <div class="control-group <?php
if (isset($errors['user_password2'])) : echo 'error';
endif;
?>">
        <label class="control-label"><?php echo __d('cloggy','Confirm Password'); ?></label>
        <div class="controls">
    <?php echo $this->Form->input('user_password2', array('label' => false, 'placeholder' => __d('cloggy','confirm password'), 'type' => 'password', 'div' => false)); ?>
            <span class="help-inline"><?php
    if (isset($errors['user_password2'])) : echo $errors['user_password2'][0];
    endif;
    ?></span>
        </div>
    </div>
    <div class="control-group <?php
            if (isset($errors['user_role'])) : echo 'error';
            endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','Role'); ?></label>
        <div class="controls">
            <?php echo $this->Form->select('user_role',$roles,array('hiddenField' => false)); ?>
            <span class="help-inline"><?php
                if (isset($errors['user_role'])) : echo $errors['user_role'][0];
                endif;
                ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __d('cloggy','Active Status'); ?></label>
        <div class="controls">
            <label class="radio inline">
                <?php echo $this->Form->radio('user_status',array(1 => __d('cloggy','Yes')),array('label' => false,'value' => 1,'legend' => false)); ?>
            </label>
            <label class="radio inline">					
                <?php echo $this->Form->radio('user_status',array(0 => __d('cloggy','No')),array('label' => false,'value' => 1,'legend' => false)); ?>
            </label>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">				
            <?php echo $this->Form->submit(__d('cloggy','Update'),array(
                'class' => 'btn btn-primary',
                'div' => false,                        
            )); ?>  
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>

<?php $this->append('cloggy_js_module_page'); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('.help-inline').hide();
        <?php if (isset($errors) && !empty($errors)) : ?>
            jQuery('.help-inline').delay(800).fadeIn();			
        <?php endif; ?>
    });
</script>
<?php $this->end(); ?>