<?php echo $this->Form->create('#',array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
	<thead>		
		<tr>
			<th><input type="checkbox" name="checker" id="checker" /></th>
			<th>Title</th>			
			<th>Author</th>
			<th>Status</th>
			<th>Created</th>			
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($posts)) : ?>
			<?php foreach($posts as $post) :?>
				<tr>
					<td><input type="checkbox" name="post[]" value="<?php echo $post['ClogNode']['id']; ?>" /></td>
					<td>
						<?php echo $this->Html->link($post['ClogSubject']['subject'],
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_posts/edit/'.$post['ClogNode']['id']); ?>
					</td>
					<td>
						<?php echo $this->Html->link($post['ClogUser']['user_name'],
								'/'.Configure::read('Clog.url_prefix').'/module/users/users_home/edit/'.$post['ClogUser']['id']); ?>
					</td>					
					<td>
						<?php if($post['ClogNode']['node_status'] == 1) :?>
						<span class="label label-success">Published</span>
						<?php else: ?>
						<span class="label label-important">Draft</span>
						<?php endif; ?>
					</td>
					<td><?php echo $this->Time->format('M jS, Y, H:i A',$post['ClogNode']['node_created']); ?></td>					
					<td>
						<?php echo $this->Html->link('Edit',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_posts/edit/'.$post['ClogNode']['id']); ?>
								|
						<?php echo $this->Html->link('Remove',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_posts/remove/'.$post['ClogNode']['id'],array('class' => 'post_remove')); ?>
								|
								
						<?php if($post['ClogNode']['node_status'] > 0) :?>
						<?php echo $this->Html->link('Make a draft',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_posts/draft/'.$post['ClogNode']['id'],array('class' => 'post_disable')); ?>
						<?php else: ?>
						<?php echo $this->Html->link('Publish',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_posts/publish/'.$post['ClogNode']['id'],array('id' => 'post_enable')); ?>
						<?php endif; ?>
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
						<li><?php echo $this->Html->link('Delete All','#',array('id' => 'action_delete_all','class' => 'action_js')); ?></li>
						<li><?php echo $this->Html->link('Draft All','#',array('id' => 'action_disable_all','class' => 'action_js')); ?></li>
						<li><?php echo $this->Html->link('Publish All','#',array('id' => 'action_enable_all','class' => 'action_js')); ?></li>
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
		$this->ClogPaginator->paginatorBootstrap('/'.Configure::read('Clog.url_prefix').'/module/clog_blog');
		?>		
	</ul>
</div>

<?php $this->append('clog_js_module_page'); ?>
<script type="text/javascript">
clog.captureJQuery(function() {	
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
				urlAjax = '<?php echo Router::url('/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_ajax/delete_all_posts'); ?>';
				confirmAction = confirm('Are you sure want to delete all these posts?');		
			break;

			case 'action_disable_all':
				urlAjax = '<?php echo Router::url('/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_ajax/draft_all_posts'); ?>';
				confirmAction = confirm('Are you sure want to make draft all these posts?');
			break;

			case 'action_enable_all':
				urlAjax = '<?php echo Router::url('/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_ajax/publish_all_posts'); ?>';
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
		if(confirm('Are you sure to remove this post?')) {
			window.location = href;
		}	
	});
	jQuery('.post_disable').on('click',function(e) {
		e.preventDefault();
		var href = jQuery(this).attr('href');
		if(confirm('Are you sure to make draft this post?')) {
			window.location = href;
		}
	});
	jQuery('.post_enable').on('click',function(e) {
		e.preventDefault();
		var href = jQuery(this).attr('href');
		if(confirm('Are you sure to publish this post?')) {
			window.location = href;
		}
	});		
});
</script>
<?php $this->end(); ?>