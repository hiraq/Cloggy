<?php echo $this->Form->create('CloggyUser',array(
	'url' => '/'.Configure::read('Cloggy.url_prefix').'/module/cloggy_users/cloggy_users_home/edit/'.$id,
	'class' => 'form-horizontal'
)); ?>
	<fieldset>
		<legend>Edit '<?php echo $user['CloggyUser']['user_name']; ?>'</legend>				
		
		<?php $flash = $this->Session->flash('success'); ?>
		<?php if(!empty($flash)) : ?>
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $flash; ?>
		</div>
		<?php endif; ?>
		
		<div class="control-group <?php if(isset($errors['user_name'])) : echo 'error'; endif; ?>">
			<label class="control-label">Username</label>
			<div class="controls">
				<?php echo $this->Form->input('user_name',array(
						'label' => false,
						'placeholder' => 'username',
						'type' => 'text',
						'value' => $user['CloggyUser']['user_name'],
						'div' => false)
				); ?>
				<span class="help-inline"><?php if(isset($errors['user_name'])) : echo  $errors['user_name'][0]; endif; ?></span>
			</div>
		</div>
		<div class="control-group <?php if(isset($errors['user_email'])) : echo  'error'; endif; ?>">
			<label class="control-label">Email</label>
			<div class="controls">
				<?php echo $this->Form->input('user_email',array(
						'label' => false,
						'placeholder' => 'email address',
						'type' => 'email',
						'value' => $user['CloggyUser']['user_email'],
						'div' => false)
				); ?>
				<span class="help-inline"><?php if(isset($errors['user_email'])) : echo  $errors['user_email'][0]; endif; ?></span>
			</div>
		</div>
		<div class="control-group <?php if(isset($errors['user_password'])) : echo  'error'; endif; ?>">
			<label class="control-label">New Password</label>
			<div class="controls">
				<?php echo $this->Form->input('user_password',array('label' => false,'placeholder' => 'password','type' => 'password','div' => false)); ?>
				<span class="help-inline"><?php if(isset($errors['user_password'])) : echo  $errors['user_password'][0]; endif; ?></span>
			</div>
		</div>
		<div class="control-group <?php if(isset($errors['user_password2'])) : echo  'error'; endif; ?>">
			<label class="control-label">Confirm Password</label>
			<div class="controls">
				<?php echo $this->Form->input('user_password2',array('label' => false,'placeholder' => 'confirm password','type' => 'password','div' => false)); ?>
				<span class="help-inline"><?php if(isset($errors['user_password2'])) : echo  $errors['user_password2'][0]; endif; ?></span>
			</div>
		</div>
		<div class="control-group <?php if(isset($errors['user_role'])) : echo  'error'; endif; ?>">
			<label class="control-label">Role</label>
			<div class="controls">
				<?php echo $this->Form->input('user_role',array(
						'label' => false,
						'placeholder' => 'role access name',
						'type' => 'text',
						'value' => $user['CloggyUser']['user_role'],
						'div' => false)
				); ?>
				<span class="help-inline"><?php if(isset($errors['user_role'])) : echo  $errors['user_role'][0]; endif; ?></span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Active Status</label>
			<div class="controls">
				<label class="radio inline">
					<input type="radio" name="data[CloggyUser][user_status]" value="1" <?php if($user['CloggyUser']['user_status']=='1') echo 'checked="checked"'?>> Yes
				</label>
				<label class="radio inline">					
					<input type="radio" name="data[CloggyUser][user_status]" value="0" <?php if($user['CloggyUser']['user_status']=='0') echo 'checked="checked"'?>> No
				</label>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">				
				<input type="submit" name="submit" value="Save" class="btn btn-primary" />
			</div>
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>

<?php $this->append('clog_js_module_page'); ?>
<script type="text/javascript">
clog.captureJQuery(function() {
	jQuery('.help-inline').hide();

	<?php if(isset($errors) && !empty($errors)) :?>
	jQuery('.help-inline').delay(800).fadeIn();			
	<?php endif; ?>
});
</script>
<?php $this->end(); ?>