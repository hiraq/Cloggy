<?php echo $this->Form->create('#',array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
	<thead>	
		<tr>
			<th colspan="9">Categories - <?php echo $this->Html->link('Manage',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_categories'); ?></th>
		</tr>	
		<tr>						
			<th width="2%"><input type="checkbox" name="checker" id="checker" /></th>
			<th>Category Name</th>			
			<th>Total Posts</th>							
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($categories)) : ?>
			<?php foreach($categories as $category) :?>
				<tr>					
					<td><input type="checkbox" name="category[]" value="<?php echo $category['ClogNode']['id']; ?>" /></td>
					<td>
						<?php echo $this->Html->link($category['ClogSubject']['subject'],
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_categories/edit/'.$category['ClogNode']['id']); ?>
					</td>
					<td>
						<?php echo count($category['ClogRelNode']); ?>
					</td>																	
					<td>
						<?php echo $this->Html->link('Edit',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_categories/edit/'.$category['ClogNode']['id']); ?>
								|
						<?php echo $this->Html->link('Remove',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_categories/remove/'.$category['ClogNode']['id'],array('class' => 'post_remove')); ?>																					
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
				urlAjax = '<?php echo Router::url('/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_ajax/delete_all_categories'); ?>';
				confirmAction = confirm('Are you sure want to delete all these categories?');		
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
		if(confirm('Are you sure to remove this category?')) {
			window.location = href;
		}
			
	});	
	
});
</script>
<?php $this->end(); ?>