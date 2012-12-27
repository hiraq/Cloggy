<?php echo $this->Form->create('#',array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
	<thead>	
		<tr>
			<th colspan="8">Posts - <?php echo $this->Html->link('Manage',
								'/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_posts'); ?></th>
		</tr>	
		<tr>						
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
	</tbody>
</table>
<?php echo $this->Form->end(); ?>