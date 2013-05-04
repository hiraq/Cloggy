<?php
echo $this->Form->create('CloggyBlogImport', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_import/wordpress',
    'class' => 'form-horizontal',
    'type' => 'file'
));
?>

<?php $flashMessage = $this->Session->flash('success'); ?>
<?php if (isset($flashMessage) && !empty($flashMessage)) : ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php echo $flashMessage; ?>						
    </div>
<?php endif; ?>

<fieldset>
    <legend><?php echo __d('cloggy','Import WordPress Posts'); ?></legend>

    <?php if (isset($success)) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $success; ?>
        </div>
    <?php elseif (isset($error)) : ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="control-group <?php
    if (isset($errors['wordpress_xml'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','WordPress Xml'); ?></label>
        <div class="controls">
                <?php echo $this->Form->input('wordpress_xml', array('label' => false, 'type' => 'file', 'div' => false)); ?>
            <span class="help-inline"><?php
                if (isset($errors['wordpress_xml'])) : echo $errors['wordpress_xml'][0];
                endif;
                ?></span>
        </div>
        
    </div>
    
    <div class="control-group <?php
    if (isset($errors['wordpress_import_options'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','Import Options'); ?></label>
        <div class="controls">
            <label class="checkbox">                
                <?php echo $this->Form->checkbox('CloggyBlogImport.wordpress_import_options.import_featured_image',array('value' => 1)); ?> <?php echo __d('cloggy','Download Featured Image'); ?>
            </label>
            <label class="checkbox">                
                <?php echo $this->Form->checkbox('CloggyBlogImport.wordpress_import_options.make_draft',array('value' => 1)); ?> <?php echo __d('cloggy','Make Draft'); ?>
            </label>
            <label class="checkbox">                
                <?php echo $this->Form->checkbox('CloggyBlogImport.wordpress_import_options.only_published_posts',array('value' => 1)); ?> <?php echo __d('cloggy','Only Published Posts'); ?>
            </label>
            <label class="checkbox">                
                <?php echo $this->Form->checkbox('CloggyBlogImport.wordpress_import_options.only_drafted_posts',array('value' => 1)); ?> <?php echo __d('cloggy','Only Drafted Posts'); ?>                
            </label>
            <label class="checkbox">                
                <?php echo $this->Form->checkbox('CloggyBlogImport.wordpress_import_options.disable_categories',array('value' => 1)); ?> <?php echo __d('cloggy','Not Import Categories'); ?>
            </label>
            <label class="checkbox">                
                <?php echo $this->Form->checkbox('CloggyBlogImport.wordpress_import_options.disable_tags',array('value' => 1)); ?> <?php echo __d('cloggy','Not Import Tags'); ?>
            </label>
            <label class="checkbox">                
                <?php echo $this->Form->checkbox('CloggyBlogImport.wordpress_import_options.include_custom_post_types',array('value' => 1)); ?> <?php echo __d('cloggy','Include Custom Post Types'); ?>
            </label>
            
            <span class="help-inline">
                <?php
                if (isset($errors['wordpress_import_options'])) : echo $errors['wordpress_import_options'][0];
                endif;
                ?>
            </span>
        </div>
        
    </div>

    <div class="control-group">
        <div class="controls">				            
            <?php echo $this->Form->submit(__d('cloggy','Import'),array(
                'div' => false,
                'class' => 'btn btn-primary'
            )); ?>
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>