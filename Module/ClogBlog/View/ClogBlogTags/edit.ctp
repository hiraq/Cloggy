<?php echo $this->Form->create('ClogBlogTags',array(
	'url' => '/'.Configure::read('Clog.url_prefix').'/module/clog_blog/clog_blog_tags/edit/'.$id,
	'class' => 'form-horizontal'
)); ?>
	<fieldset>
		<legend>Edit Tag</legend>
		
		<?php if(isset($success)) :?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $success; ?>
		</div>
		<?php endif; ?>
		
		<div class="control-group <?php if(isset($errors['tag_name'])) : echo 'error'; endif; ?>">
			<label class="control-label">Tag Name</label>
			<div class="controls">
				<?php echo $this->Form->input('tag_name',
						array('label' => false,
								'placeholder' => 'tag name',
								'type' => 'text',
								'value' => $tag['ClogSubject']['subject'],
								'div' => false)); ?>
				<span class="help-inline"><?php if(isset($errors['tag_name'])) : echo  $errors['tag_name'][0]; endif; ?></span>
			</div>
		</div>					
					
		<div class="control-group">
			<div class="controls">				
				<input type="submit" name="submit" value="Update" class="btn btn-primary" />
			</div>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>