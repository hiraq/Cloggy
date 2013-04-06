<?php
echo $this->Form->create('CloggyUserPerm', array(
    'url' => CloggyCommon::urlModule('cloggy_users', 'cloggy_users_perm/create'),
    'class' => 'form-horizontal'
));
?>
<fieldset>
    <legend><?php echo __d('cloggy','Setup New Permission'); ?></legend>

    <?php if (isset($success)) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="control-group <?php
    if (isset($errors['role_id'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','Role Name'); ?></label>
        <div class="controls">
            <select name="data[CloggyUserPerm][role_id]">
                <option value="0">All</option>
                <?php foreach($roles as $roleId => $roleName) : ?>
                <option value="<?php echo $roleId; ?>">                     
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
        <label class="control-label"><?php echo __d('cloggy','Permission Type'); ?></label>
        <div class="controls">
            <select name="data[CloggyUserPerm][aco_adapter]" id="adapter">
                <option value="0"><?php echo __d('cloggy','Select Types'); ?></option>
                <option value="module"><?php echo __d('cloggy','Module'); ?></option>                
                <option value="controller"><?php echo __D('cloggy','Controller'); ?></option>
                <option value="url"><?php echo __d('cloggy','Url'); ?></option>
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
        <label class="control-label"><?php echo __d('cloggy','Object'); ?></label>
        <div class="controls">
            <div id="aco_object_form" style="display:inline">
                <?php echo $this->Form->input('aco_object', array(
                'label' => false, 'placeholder' => __d('cloggy','ex: controller_name/action'), 'type' => 'text', 'div' => false,'id' => 'aco_object')); ?>
            </div>            
            <span class="help-inline"><?php
            if (isset($errors['aco_object'])) : echo $errors['aco_object'][0];
            endif;
            ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label"><?php echo __d('cloggy','Permission'); ?></label>
        <div class="controls">
            <label class="radio inline">
                <input type="radio" name="data[CloggyUserPerm][perm]" value="1" checked="checked"> <?php echo __d('cloggy','Allow'); ?>
            </label>
            <label class="radio inline">
                <input type="radio" name="data[CloggyUserPerm][perm]" value="0"> <?php echo __d('cloggy','Deny'); ?>
            </label>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">				
            <input type="submit" name="submit" value="Create" class="btn btn-primary" />
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>

<?php $this->append('cloggy_js_module_page'); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
                
        var formString = jQuery('#aco_object_form').html();
        
        jQuery('#adapter').on('change',function(e) {
            var value = jQuery(this).val();
            if(value != 0) {
                
                switch(value) {
                    
                    case 'module':
                        jQuery('#aco_object_form').html(formString);
                        jQuery('#aco_object').attr('placeholder',<?php echo __d('cloggy','ex: ModuleName'); ?>);
                        break;   
                        
                    case 'controller':
                        jQuery('#aco_object_form').html(formString);
                        jQuery('#aco_object').attr('placeholder',<?php echo __d('cloggy','ex: controller_name/action'); ?>);
                        break;
                        
                    case 'url':
                        jQuery('#aco_object_form').html(formString);
                        jQuery('#aco_object').attr('placeholder',<?php echo __d('cloggy','ex: query/url'); ?>);
                        break;
                    
                }
                
            }
        });
    });
</script>
<?php $this->end(); ?>