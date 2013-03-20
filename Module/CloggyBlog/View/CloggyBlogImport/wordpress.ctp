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
    <legend>Import WordPress Posts</legend>

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
        <label class="control-label">WordPress Xml</label>
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
        <label class="control-label">Import Options</label>
        <div class="controls">
            <label class="checkbox">
                <input type="checkbox" name="data[CloggyBlogImport][wordpress_import_options][import_featured_image]" value="1" /> Download Featured Image                
            </label>
            <label class="checkbox">
                <input type="checkbox" name="data[CloggyBlogImport][wordpress_import_options][make_draft]" value="1" /> Make Draft
            </label>
            <label class="checkbox">
                <input type="checkbox" name="data[CloggyBlogImport][wordpress_import_options][only_published_posts]" value="1" /> Only Published Posts
            </label>
            <label class="checkbox">
                <input type="checkbox" name="data[CloggyBlogImport][wordpress_import_options][only_drafted_posts]" value="1" /> Only Drafted Posts
            </label>
            <label class="checkbox">
                <input type="checkbox" name="data[CloggyBlogImport][wordpress_import_options][disable_categories]" value="1" /> Not Import Categories
            </label>
            <label class="checkbox">
                <input type="checkbox" name="data[CloggyBlogImport][wordpress_import_options][disable_tags]" value="1" /> Not Import Tags
            </label>
            <label class="checkbox">
                <input type="checkbox" name="data[CloggyBlogImport][wordpress_import_options][disable_metas]" value="1" /> Not Import Meta
            </label>
            <label class="checkbox">
                <input type="checkbox" name="data[CloggyBlogImport][wordpress_import_options][include_custom_post_types]" value="1" /> Include Custom Post Types
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
            <input type="submit" name="submit" value="Import" class="btn btn-primary" />
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>