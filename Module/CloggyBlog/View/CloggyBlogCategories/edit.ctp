<?php
echo $this->Form->create('CloggyBlogCategories', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_categories/edit/' . $id,
    'class' => 'form-horizontal'
));
?>
<fieldset>
    <legend><?php echo __d('cloggy','Edit Category'); ?></legend>

    <?php if (isset($success)) : ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <div class="control-group <?php
    if (isset($errors['category_name'])) : echo 'error';
    endif;
    ?>">
        <label class="control-label"><?php echo __d('cloggy','Category Name'); ?></label>
        <div class="controls">
            <?php
            echo $this->Form->input('category_name', array(
                'label' => false,
                'placeholder' => __d('cloggy','category name'),
                'type' => 'text',
                'value' => $detail['CloggySubject']['subject'],
                'div' => false)
            );
            ?>
            <span class="help-inline"><?php
            if (isset($errors['category_name'])) : echo $errors['category_name'][0];
            endif;
            ?></span>
        </div>
    </div>

<?php if ($listCategories) : ?>
        <div class="control-group">
            <label class="control-label"><?php echo __d('cloggy','Category Parent'); ?></label>
            <div class="controls">
                <?php echo $this->Form->select('category_parent',$listCategories,array(
                    'value' => $detail['CloggyParentNode']['CloggyNode']['id']
                )); ?>
            </div>
        </div>
<?php endif; ?>

    <div class="control-group">
        <div class="controls">				
            <?php echo $this->Form->submit(__d('cloggy','Update'),array(
                'div' => false,
                'class' => 'btn btn-primary'
            )); ?>
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>