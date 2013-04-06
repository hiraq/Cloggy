<?php
echo $this->Form->create('CloggyBlogPost', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/add'
));
?>
<input type="hidden" name="data[CloggyBlogPost][post_id]" value="<?php echo $postNodeId; ?>" />
<fieldset>
    <legend><?php echo __d('cloggy','Add New Post'); ?></legend>
    <div class="row">
        <div class="span10">
            <div class="row">
                <div class="span8">

                    <?php if (isset($success) && !empty($success)) : ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?php echo $success; ?>						
                        </div>
                    <?php endif; ?>

                    <label><?php echo __d('cloggy','Title'); ?></label> 
                    <input type="text" placeholder="<?php echo __d('cloggy','title'); ?>" class="span6" name="data[CloggyBlogPost][title]">
                    <span class="help-block"><?php if (isset($errors['title'][0])) echo $errors['title'][0]; ?></span><br  /> 		
                    <label><?php echo __d('cloggy','Content'); ?></label>
                    <a href="#" title="upload images" id="cloggy_blog_add_image"><i class="icon-picture"></i></a>                    
                    <textarea id="editor" name="data[CloggyBlogPost][content]"></textarea>
                    <span class="help-block"><?php if (isset($errors['content'][0])) echo $errors['content'][0]; ?></span>	
                </div>
                <div class="span2">
                    <label><?php echo __d('cloggy','Categories'); ?></label> 
                    <?php if (empty($categories)) : ?>
                        <input type="text" name="data[CloggyBlogPost][categories]" placeholder="categories" class="span2" /><br  />
                    <?php else: ?>
                        <select multiple="multiple" class="span2" name="data[CloggyBlogPost][categories][]">						
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo $category['CloggySubject']['subject']; ?>">
                                    <?php echo $category['CloggySubject']['subject']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?> 
                    <label><?php echo __d('cloggy','Tags'); ?></label> 					
                    <input type="text" name="data[CloggyBlogPost][tags]" placeholder="tags" class="span2" /><br  />					
                    <hr />
                    <input type="submit" name="submit" value="Publish Now" class="btn btn-primary" />
                    <input type="submit" name="submit" value="Draft" class="btn" /> 
                </div>
            </div>
        </div>
    </div>
</fieldset>

<?php echo $this->Form->end(); ?>

<?php echo $this->element('cloggy_blog_post_image_dialog',compact('postNodeId')); //load image dialog ?>

<?php $this->append('cloggy_js_module_page'); ?>
<?php echo $this->CloggyAsset->getVendorHtmlTag('ckeditor/ckeditor.js', 'js'); ?>  

<script type="text/javascript">        
    
    CKEDITOR.replace('editor',{				
        toolbar: [
            { name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] },
            { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },		           
            { name: 'basicstyles', items: [ 'Bold', 'Italic','Underline','Strike' ] },
            { name: 'insert', items: ['PageBreak'] },
            { name: 'links', items: ['Link','Unlink','Anchor'] },
            { name: 'paragraph', items: ['NumberedList','BulletedList','Outdent','Indent',
                    'Blockquote','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']}
        ]						
    });		
            
    jQuery(document).ready(function() {
        
        jQuery('#cloggy_blog_add_image').on('click',function(e) {
            
            e.preventDefault();
            
            //toggle modal image
            jQuery('#cloggy_modal_image').modal('toggle');                        
            
        });
        
    });
</script>
<?php $this->end(); ?>
