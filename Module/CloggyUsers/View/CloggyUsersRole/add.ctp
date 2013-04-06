<?php
echo $this->Form->create('CloggyUserRole', array(
    'url' => CloggyCommon::urlModule('cloggy_users', 'cloggy_users_role/add'),
    'class' => 'form-horizontal'
));
?>
<fieldset>
    <legend><?php echo __d('cloggy','Add New Role'); ?></legend>

    <?php if (isset($success)) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="control-group <?php
    if (isset($errors['role_name'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','Role Name'); ?></label>
        <div class="controls">
            <?php echo $this->Form->input('role_name', array(
                'label' => false, 'placeholder' => __d('cloggy','role name'), 'type' => 'text', 'div' => false)); ?>
            <span class="help-inline"><?php
            if (isset($errors['role_name'])) : echo $errors['role_name'][0];
            endif;
            ?></span>
        </div>
    </div>    
    <div class="control-group">
        <div class="controls">				
            <input type="submit" name="submit" value="Add" class="btn btn-primary" />
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>