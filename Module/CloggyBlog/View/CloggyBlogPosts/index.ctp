<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
    <thead>		
        <tr>
            <th><input type="checkbox" name="checker" id="checker" /></th>
            <th><?php echo __d('cloggy','Title'); ?></th>			
            <th><?php echo __d('cloggy','Author'); ?></th>
            <th><?php echo __d('cloggy','Status'); ?></th>
            <th><?php echo __d('cloggy','Created'); ?></th>			
            <th><?php echo __d('cloggy','Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($posts)) : ?>
            <?php foreach ($posts as $post) : ?>
                <tr>
                    <td><input type="checkbox" name="post[]" value="<?php echo $post['CloggyNode']['id']; ?>" /></td>
                    <td>
                        <?php echo $this->Html->link($post['CloggySubject']['subject'], '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/edit/' . $post['CloggyNode']['id']);
                        ?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($post['CloggyUser']['user_name'], '/' . Configure::read('Cloggy.url_prefix') . '/module/users/users_home/edit/' . $post['CloggyUser']['id']);
                        ?>
                    </td>					
                    <td>
                        <?php if ($post['CloggyNode']['node_status'] == 1) : ?>
                            <span class="label label-success">Published</span>
                        <?php else: ?>
                            <span class="label label-important">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $this->Time->format('M jS, Y, H:i A', $post['CloggyNode']['node_created']); ?></td>					
                    <td>
                        <?php echo $this->Html->link(__d('cloggy','Edit'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/edit/' . $post['CloggyNode']['id']);
                        ?>
                        |
                        <?php echo $this->Html->link(__d('cloggy','Remove'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/remove/' . $post['CloggyNode']['id'], array('class' => 'post_remove'));
                        ?>
                        |

                        <?php if ($post['CloggyNode']['node_status'] > 0) : ?>
                            <?php echo $this->Html->link(__d('cloggy','Make a draft'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/draft/' . $post['CloggyNode']['id'], array('class' => 'post_disable'));
                            ?>
                        <?php else: ?>
                            <?php echo $this->Html->link(__d('cloggy','Publish'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/publish/' . $post['CloggyNode']['id'], array('id' => 'post_enable'));
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
                        <li><?php echo $this->Html->link(__d('cloggy','Draft All'), '#', array('id' => 'action_disable_all', 'class' => 'action_js')); ?></li>
                        <li><?php echo $this->Html->link(__d('cloggy','Publish All'), '#', array('id' => 'action_enable_all', 'class' => 'action_js')); ?></li>
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
        $this->CloggyPaginator->paginatorBootstrap('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog');
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
                    urlAjax = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_ajax/delete_all_posts'); ?>';
                    confirmAction = confirm(<?php echo __d('cloggy','Are you sure want to delete all these posts?') ?>);		
                    break;

                case 'action_disable_all':
                    urlAjax = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_ajax/draft_all_posts'); ?>';
                    confirmAction = confirm(<?php echo __d('cloggy','Are you sure want to make draft all these posts?') ?>);
                    break;

                case 'action_enable_all':
                    urlAjax = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_ajax/publish_all_posts'); ?>';
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
            jQuery('.post_remove').on('click',function(e) {
                e.preventDefault();
                var href = jQuery(this).attr('href');
                if(confirm(<?php echo __d('cloggy','Are you sure to remove this post?') ?>)) {
                    window.location = href;
                }	
            });
            jQuery('.post_disable').on('click',function(e) {
                e.preventDefault();
                var href = jQuery(this).attr('href');
                if(confirm(<?php echo __d('cloggy','Are you sure to make draft this post?') ?>)) {
                    window.location = href;
                }
            });
            jQuery('.post_enable').on('click',function(e) {
                e.preventDefault();
                var href = jQuery(this).attr('href');
                if(confirm(<?php echo __d('cloggy','Are you sure to publish this post?') ?>)) {
                    window.location = href;
                }
            });		
        });
</script>
<?php $this->end(); ?>