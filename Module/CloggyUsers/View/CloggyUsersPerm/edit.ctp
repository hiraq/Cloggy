<?php
echo $this->Form->create('CloggyUserPerm', array(
    'url' => CloggyCommon::urlModule('cloggy_users', 'cloggy_users_perm/edit'.$id),
    'class' => 'form-horizontal'
));
?>
<fieldset>
    <legend>Edit Permission</legend>

    <?php $flash = $this->Session->flash('success'); ?>
    <?php if (!empty($flash)) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $flash; ?>
        </div>
    <?php endif; ?>

    <div class="control-group <?php
    if (isset($errors['role_id'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label">Role Name</label>
        <div class="controls">
            <select name="data[CloggyUserPerm][role_id]">
                <option value="0" <?php if($perm['CloggyUserPerm']['aro_object'] == '*') echo 'selected="selected"'; ?>>All</option>
                <?php foreach($roles as $roleId => $roleName) : ?>
                <option value="<?php echo $roleId; ?>" <?php if($perm['CloggyUserPerm']['aro_object_id'] == $roleId) echo 'selected="selected"'; ?>>                     
                    <?php echo $roleName; ?>
                </option>                
                <?php endforeach; ?>
            </select>
            <span class="help-inline"><?php
            if (isset($errors['role_id'])) : echo $errors['role_id'][0];
            endif;
            ?></span>
        </div>        
    </div>    
    <div class="control-group <?php
    if (isset($errors['aco_adapter'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label">Permission Type</label>
        <div class="controls">
            <select name="data[CloggyUserPerm][aco_adapter]" id="adapter">
                <option value="0">Select Types</option>
                <option value="module" <?php if($perm['CloggyUserPerm']['aco_adapter'] == 'module') echo 'selected="selected"'; ?>>Module</option>
                <option value="model" <?php if($perm['CloggyUserPerm']['aco_adapter'] == 'model') echo 'selected="selected"'; ?>>Model</option>
                <option value="url" <?php if($perm['CloggyUserPerm']['aco_adapter'] == 'url') echo 'selected="selected"'; ?>>Url</option>
            </select>
            <span class="help-inline"><?php
            if (isset($errors['aco_adapter'])) : echo $errors['aco_adapter'][0];
            endif;
            ?></span>
        </div>        
    </div>
    <div class="control-group <?php
    if (isset($errors['aco_object'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label">Object</label>
        <div class="controls">
            <div id="aco_object_form" style="display:inline">
                
                <?php if ($perm['CloggyUserPerm']['aco_adapter'] == 'model') : ?>
                    <select name="aco_object">
                        <option <?php if($perm['CloggyUserPerm']['aco_object'] == 'find') echo 'selected="selected"'; ?>>find</option>
                        <option <?php if($perm['CloggyUserPerm']['aco_object'] == 'save') echo 'selected="selected"'; ?>>save</option>
                        <option <?php if($perm['CloggyUserPerm']['aco_object'] == 'delete') echo 'selected="selected"'; ?>>delete</option>
                    </select>
                <?php else: ?>
                    <?php echo $this->Form->input('aco_object', array(
                        'label' => false, 
                        'placeholder' => 'ex: controller_name/action', 
                        'type' => 'text', 
                        'div' => false,
                        'value' => $perm['CloggyUserPerm']['aco_object'],
                        'id' => 'aco_object')); ?>
                <?php endif; ?>
            </div>            
            <span class="help-inline"><?php
            if (isset($errors['aco_object'])) : echo $errors['aco_object'][0];
            endif;
            ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Permission</label>
        <div class="controls">
            <label class="radio inline">
                <input type="radio" name="data[CloggyUserPerm][perm]" value="1" <?php if($perm['CloggyUserPerm']['allow'] == 1) echo 'checked="checked"'; ?>> Allow
            </label>
            <label class="radio inline">
                <input type="radio" name="data[CloggyUserPerm][perm]" value="0" <?php if($perm['CloggyUserPerm']['deny'] == 1) echo 'checked="checked"'; ?>> Deny
            </label>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">				
            <input type="submit" name="submit" value="Update" class="btn btn-primary" />
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>

<?php $this->append('cloggy_js_module_page'); ?>
<script type="text/javascript">
    cloggy.captureJQuery(function() {	
        
        var formModel = '<select name=data[CloggyUserPerm][aco_object]><option>find</option><option>save</option><option>delete</option></select>';
        var formString = jQuery('#aco_object_form').html();
        
        jQuery('#adapter').on('change',function(e) {
            var value = jQuery(this).val();
            if(value != 0) {
                
                switch(value) {
                    
                    case 'module':
                        jQuery('#aco_object_form').html(formString);
                        jQuery('#aco_object').attr('placeholder','ex: controller_name/action');
                        break;
                        
                    case 'model':                        
                        jQuery('#aco_object_form').html('');                        
                        jQuery('#aco_object_form').html(formModel);
                        break;
                        
                    case 'url':
                        jQuery('#aco_object_form').html(formString);
                        jQuery('#aco_object').attr('placeholder','ex: query/url');
                        break;
                    
                }
                
            }
        });
    });
</script>
<?php $this->end(); ?>