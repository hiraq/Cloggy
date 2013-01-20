<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
    <thead>        
        <tr>
            <th width="5%"><input type="checkbox" name="checker" id="checker" /></th>
            <th>Role Name</th>  
            <th>Users</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($roles)) : ?>
            <?php foreach ($roles as $role) : ?>
                <tr>
                    <td><input type="checkbox" name="role[]" value="<?php echo $role['CloggyUserRole']['id']; ?>" /></td>
                    <td>
                        <?php echo $this->Html->link(
                                $role['CloggyUserRole']['role_name'], 
                                CloggyCommon::urlModule('cloggy_users', 'cloggy_users_role/edit/'.$role['CloggyUserRole']['id']));
                        ?>
                    </td>
                    <td><?php echo count($role['CloggyUser']); ?></td>                    
                    <td>
                        <?php echo $this->Html->link('Edit', CloggyCommon::urlModule('cloggy_users', 'cloggy_users_role/edit/'.$role['CloggyUserRole']['id']));
                        ?>
                        |
                        <?php echo $this->Html->link('Remove', 
                                CloggyCommon::urlModule('cloggy_users', 'cloggy_users_role/remove/'.$role['CloggyUserRole']['id']),
                                array(
                                    'class' => 'role_remove'
                                ));
                        ?>                                                
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No data available</td>
            </tr>
        <?php endif; ?>	
        <tr id="checkbox_all" style="display:none">
            <td colspan="8">
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        With Selected <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><?php echo $this->Html->link('Delete All', '#', array('id' => 'action_delete_all', 'class' => 'action_js')); ?></li>                        
                    </ul>
                </div>	
            </td>
        </tr>	
    </tbody>
</table>
<?php echo $this->Form->end(); ?>

<div class="pagination pagination-centered">
    <ul>
        <?php
        $this->CloggyPaginator->paginatorBootstrap(CloggyCommon::urlModule('cloggy_users'));
        ?>		
    </ul>
</div>

<?php $this->append('cloggy_js_module_page'); ?>
<script type="text/javascript">
    cloggy.captureJQuery(function() {	
        jQuery('#checker').on('click',function(e) {
            jQuery('tbody').find(':checkbox').attr('checked',this.checked);
            if(this.checked) {
                jQuery('#checkbox_all').fadeIn('slow');
            }else{
                jQuery('#checkbox_all').fadeOut('slow');
            }
        });
        jQuery('.action_js').on('click',function(e) {

            e.preventDefault();
            var id = jQuery(this).attr('id');
            var urlAjax;
            var formData = jQuery('input[type="checkbox"]').serializeArray();
            formData.shift();
            var confirmAction = true;
		
            switch(id) {

                case 'action_delete_all':
                    urlAjax = '<?php echo Router::url(CloggyCommon::urlModule('cloggy_users', 'cloggy_users_ajax/delete_all_roles')); ?>';
                    confirmAction = confirm('Are you sure want to delete all these roles?');		
                    break;                		
                }			

                if(confirmAction) {

                    jQuery.ajax({
                        url: urlAjax,
                        data: formData,
                        dataType: 'json',
                        type: 'POST',
                        cache: false,
                        success: function(data,status,jqxhr) {
					
                            if(data.msg && data.msg == 'success') {
                                window.location = window.location.href;
                            }
					
                        }
                    });
				
                }		
		
            });
            jQuery('.role_remove').on('click',function(e) {
                e.preventDefault();
                var href = jQuery(this).attr('href');
                if(confirm('Are you sure to remove this role?')) {
                    window.location = href;
                }	
            });            
        });
</script>
<?php $this->end(); ?>