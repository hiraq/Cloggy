<?php
echo $this->Form->create('CloggyUser', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_users/cloggy_users_home/add',
    'class' => 'form-horizontal'
));
?>
<fieldset>
  <legend>Add New User</legend>

  <?php if (isset($success)) : ?>
    <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <?php echo $success; ?>
    </div>
  <?php endif; ?>

  <div class="control-group <?php if (isset($errors['user_name'])) : echo 'error';
  endif;
  ?>">
    <label class="control-label">Username</label>
    <div class="controls">
        <?php echo $this->Form->input('user_name', array('label' => false, 'placeholder' => 'username', 'type' => 'text', 'div' => false)); ?>
      <span class="help-inline"><?php if (isset($errors['user_name'])) : echo $errors['user_name'][0];
        endif;
        ?></span>
    </div>
  </div>
  <div class="control-group <?php if (isset($errors['user_email'])) : echo 'error';
        endif;
        ?>">
    <label class="control-label">Email</label>
    <div class="controls">
<?php echo $this->Form->input('user_email', array('label' => false, 'placeholder' => 'email address', 'type' => 'email', 'div' => false)); ?>
      <span class="help-inline"><?php if (isset($errors['user_email'])) : echo $errors['user_email'][0];
endif;
?></span>
    </div>
  </div>
  <div class="control-group <?php if (isset($errors['user_password'])) : echo 'error';
endif;
?>">
    <label class="control-label">Password</label>
    <div class="controls">
       <?php echo $this->Form->input('user_password', array('label' => false, 'placeholder' => 'password', 'type' => 'password', 'div' => false)); ?>
      <span class="help-inline"><?php if (isset($errors['user_password'])) : echo $errors['user_password'][0];
       endif;
       ?></span>
    </div>
  </div>
  <div class="control-group <?php if (isset($errors['user_password2'])) : echo 'error';
       endif;
       ?>">
    <label class="control-label">Confirm Password</label>
    <div class="controls">
<?php echo $this->Form->input('user_password2', array('label' => false, 'placeholder' => 'confirm password', 'type' => 'password', 'div' => false)); ?>
      <span class="help-inline"><?php if (isset($errors['user_password2'])) : echo $errors['user_password2'][0];
endif;
?></span>
    </div>
  </div>
  <div class="control-group <?php if (isset($errors['user_role'])) : echo 'error';
      endif;
?>">
    <label class="control-label">Role</label>
    <div class="controls">
<?php echo $this->Form->input('user_role', array('label' => false, 'placeholder' => 'role access name', 'type' => 'text', 'div' => false)); ?>
      <span class="help-inline"><?php if (isset($errors['user_role'])) : echo $errors['user_role'][0];
endif;
?></span>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Activated Now</label>
    <div class="controls">
      <label class="radio inline">
        <input type="radio" name="data[CloggyUser][user_status]" value="1"> Yes
      </label>
      <label class="radio inline">
        <input type="radio" name="data[CloggyUser][user_status]" value="0"> No
      </label>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">				
      <input type="submit" name="submit" value="Add" class="btn btn-primary" />
    </div>
  </div>
</fieldset>
<?php echo $this->Form->end(); ?>