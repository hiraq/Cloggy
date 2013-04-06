<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <td colspan="7">
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <?php echo __d('cloggy','Sort By'); ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <?php echo $this->Html->link(__d('cloggy','Status ASC'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/index/sort_index:status_asc');
                            ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link(__d('cloggy','Status DESC'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/index/sort_index:status_desc');
                            ?>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <?php echo $this->Html->link(__d('cloggy','Role ASC'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/index/sort_index:role_asc');
                            ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link(__d('cloggy','Role DESC'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/index/sort_index:role_desc');
                            ?>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <?php echo $this->Html->link(__d('cloggy','Name ASC'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/index/sort_index:name_asc');
                            ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link(__d('cloggy','Name DESC'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/index/sort_index:name_desc');
                            ?>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        <tr>
            <th><input type="checkbox" name="checker" id="checker" /></th>
            <th><?php echo __d('cloggy','User Name'); ?></th>
            <th><?php echo __d('cloggy','Email'); ?></th>
            <th><?php echo __d('cloggy','Role'); ?></th>
            <th><?php echo __d('cloggy','Status'); ?></th>
            <th><?php echo __d('cloggy','Created'); ?></th>
            <th><?php echo __d('cloggy','Last Login'); ?></th>
            <th><?php echo __d('cloggy','Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)) : ?>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><input type="checkbox" name="user[]" value="<?php echo $user['CloggyUser']['id']; ?>" /></td>
                    <td>
                        <?php echo $this->Html->link($user['CloggyUser']['user_name'], '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/edit/' . $user['CloggyUser']['id']);
                        ?>
                    </td>
                    <td><?php echo $user['CloggyUser']['user_email']; ?></td>
                    <td><?php echo $user['CloggyUserRole']['role_name']; ?></td>
                    <td>
                        <?php if ($user['CloggyUser']['user_status'] == 1) : ?>
                            <span class="label label-success"><?php echo __d('cloggy','Activated'); ?></span>
                        <?php else: ?>
                            <span class="label label-important"><?php echo __d('cloggy','Not activated'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $this->Time->format('M jS, Y, H:i A', $user['CloggyUser']['user_created']); ?></td>
                    <td>
                        <?php if (intval($user['CloggyUser']['user_last_login']) == 0) : ?>
                            <?php echo __d('cloggy','No activity'); ?>
                        <?php else: ?>
                            <?php echo $this->Time->niceShort($user['CloggyUser']['user_last_login']); ?>
                        <?php endif; ?>

                    </td>
                    <td>
                        <?php echo $this->Html->link(__d('cloggy','Edit'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/edit/' . $user['CloggyUser']['id']);
                        ?>
                        |
                        <?php echo $this->Html->link(__d('cloggy','Remove'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/remove/' . $user['CloggyUser']['id'], array('class' => 'users_remove'));
                        ?>
                        |

                        <?php if ($user['CloggyUser']['user_status'] > 0) : ?>
                            <?php echo $this->Html->link(__d('cloggy','Disable'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/disable/' . $user['CloggyUser']['id'], array('class' => 'users_disable'));
                            ?>
                        <?php else: ?>
                            <?php echo $this->Html->link(__d('cloggy','Enable'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/enable/' . $user['CloggyUser']['id'], array('id' => 'users_enable'));
                            ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7"><?php echo __d('cloggy','No data available'); ?></td>
            </tr>
        <?php endif; ?>	
        <tr id="checkbox_all" style="display:none">
            <td colspan="8">
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <?php echo __d('cloggy','With Selected'); ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><?php echo $this->Html->link(__d('cloggy','Delete All'), '#', array('id' => 'action_delete_all', 'class' => 'action_js')); ?></li>
                        <li><?php echo $this->Html->link(__d('cloggy','Disable All'), '#', array('id' => 'action_disable_all', 'class' => 'action_js')); ?></li>
                        <li><?php echo $this->Html->link(__d('cloggy','Enable All'), '#', array('id' => 'action_enable_all', 'class' => 'action_js')); ?></li>
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
        $this->CloggyPaginator->paginatorBootstrap('/' . Configure::read('Cloggy.url_prefix') . '/module/users');
        ?>		
    </ul>
</div>

<?php $this->append('cloggy_js_module_page'); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
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
                    urlAjax = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_ajax/delete_all'); ?>';
                    confirmAction = confirm(<?php echo __d('cloggy','Are you sure want to delete all these users?') ?>);		
                    break;

                case 'action_disable_all':
                    urlAjax = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_ajax/disable_all'); ?>';
                    confirmAction = confirm(<?php echo __d('cloggy','Are you sure want to disable all these users?') ?>);
                    break;

                case 'action_enable_all':
                    urlAjax = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_ajax/enable_all'); ?>';
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
            jQuery('.users_remove').on('click',function(e) {
                e.preventDefault();
                var href = jQuery(this).attr('href');
                if(confirm(<?php echo __d('cloggy','Are you sure to remove this user?') ?>)) {
                    window.location = href;
                }	
            });
            jQuery('.users_disable').on('click',function(e) {
                e.preventDefault();
                var href = jQuery(this).attr('href');
                if(confirm(<?php echo __d('cloggy','Are you sure to disable this user?') ?>)) {
                    window.location = href;
                }
            });	
        });
</script>
<?php $this->end(); ?>