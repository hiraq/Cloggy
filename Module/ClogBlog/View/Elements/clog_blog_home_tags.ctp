<?php echo $this->Form->create('#',array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
	<thead>	
		<tr>
			<th colspan="9">Tags - <?php echo $this->Html->link('Manage',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_tags'); ?></th>
		</tr>	
		<tr>						
			<th>Tag</th>			
			<th>Total Posts</th>							
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($tags)) : ?>
			<?php foreach($tags as $tag) :?>
				<tr>					
					<td>
						<?php echo $this->Html->link($tag['ClogSubject']['subject'],
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_tags/edit/'.$tag['ClogNode']['id']); ?>
					</td>
					<td>
						<?php echo count($tag['ClogRelNode']); ?>
					</td>																	
					<td>
						<?php echo $this->Html->link('Edit',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_tags/edit/'.$tag['ClogNode']['id']); ?>
								|
						<?php echo $this->Html->link('Remove',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_tags/remove/'.$tag['ClogNode']['id'],array('class' => 'post_remove')); ?>																					
					</td>
				</tr>
			<?php endforeach; ?>
		<?php else: ?>
		<tr>
			<td colspan="7">No data available</td>
		</tr>
		<?php endif; ?>			
	</tbody>
</table>
<?php echo $this->Form->end(); ?>