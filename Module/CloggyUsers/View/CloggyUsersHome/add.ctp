<?php
echo $this->Form->create('CloggyUser', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/add',
    'class' => 'form-horizontal'
));
?>
<fieldset>
    <legend><?php echo __d('cloggy','Add New User'); ?></legend>

    <?php if (isset($success)) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="control-group <?php
    if (isset($errors['user_name'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','Username'); ?></label>
        <div class="controls">
            <?php echo $this->Form->input('user_name', array('label' => false, 'placeholder' => __d('cloggy','username'), 'type' => 'text', 'div' => false)); ?>
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
            <?php echo $this->Form->input('user_email', array('label' => false, 'placeholder' => __d('cloggy','email address'), 'type' => 'email', 'div' => false)); ?>
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
        <label class="control-label"><?php echo __d('cloggy','Password'); ?></label>
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
        <label class="control-label"><?php echo __d('cloggy','Activated Now'); ?></label>
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
            <?php echo $this->Form->submit(__d('cloggy','Add'),array(
                'class' => 'btn btn-primary',
                'div' => false,                        
            )); ?>    
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>