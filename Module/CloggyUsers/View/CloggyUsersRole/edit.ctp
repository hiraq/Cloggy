<?php
echo $this->Form->create('CloggyUserRole', array(
    'url' => CloggyCommon::urlModule('cloggy_users', 'cloggy_users_role/edit/'.$id),
    'class' => 'form-horizontal'
));
?>
<fieldset>
    <legend><?php echo __d('cloggy','Edit Role'); ?></legend>

    <?php $flash = $this->Session->flash('success'); ?>
    <?php if (!empty($flash)) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $flash; ?>
        </div>
    <?php endif; ?>

    <div class="control-group <?php
    if (isset($errors['role_name'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','Role Name'); ?></label>
        <div class="controls">
            <?php echo $this->Form->input('role_name', array(
                'value' => $role['CloggyUserRole']['role_name'],
                'label' => false, 
                'placeholder' => __d('cloggy','role name'), 'type' => 'text', 'div' => false)); ?>
            <span class="help-inline"><?php
            if (isset($errors['role_name'])) : echo $errors['role_name'][0];
            endif;
            ?></span>
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