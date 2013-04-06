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
                'value' => $category['CloggySubject']['subject'],
                'div' => false)
            );
            ?>
            <span class="help-inline"><?php
            if (isset($errors['category_name'])) : echo $errors['category_name'][0];
            endif;
            ?></span>
        </div>
    </div>

<?php if ($categories) : ?>
        <div class="control-group">
            <label class="control-label"><?php echo __d('cloggy','Category Parent'); ?></label>
            <div class="controls">
                <select name="data[CloggyBlogCategories][category_parent]">
                    <option value="0"><?php echo __d('cloggy','Choose Parent Category'); ?></option>
                    <?php foreach ($categories as $key) : ?>
                        <option value="<?php echo $key['CloggyNode']['id']; ?>" <?php
                if (
                        $key['CloggyNode']['id'] == $category['CloggyParentNode']['CloggyNode']['id'])
                    echo 'selected="selected"'
                    ?>>
                        <?php echo $key['CloggySubject']['subject']; ?>
                        </option>
    <?php endforeach; ?>
                </select>
            </div>
        </div>
<?php endif; ?>

    <div class="control-group">
        <div class="controls">				
            <input type="submit" name="submit" value="Add" class="btn btn-primary" />
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>