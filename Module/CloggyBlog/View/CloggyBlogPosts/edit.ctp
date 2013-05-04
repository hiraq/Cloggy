<?php
echo $this->Form->create('CloggyBlogPost', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/edit/' . $id
));
?>

<fieldset>
    <legend><?php echo __d('cloggy','Edit'); ?>: '<?php echo $detail['CloggySubject']['subject']; ?>'</legend>
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

                    <?php $flashMessage = $this->Session->flash('success'); ?>
                    <?php if (isset($flashMessage) && !empty($flashMessage)) : ?>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?php echo $flashMessage; ?>						
                        </div>
                    <?php endif; ?>
                    
                     <?php echo $this->Form->input('title',array(
                        'label' => __d('cloggy','Title'),
                        'div' => false,
                        'class' => 'span6',
                        'placeholder' => __d('cloggy','title'),
                        'type' => 'text',
                        'value' => $detail['CloggySubject']['subject']
                    )); ?>
                    
                    <span class="help-block"><?php if (isset($errors['title'][0])) echo $errors['title'][0]; ?></span><br  /> 		                    
                    <label><?php echo __d('cloggy','Content'); ?></label>
                    <a href="#" title="upload images" id="cloggy_blog_add_image"><i class="icon-picture"></i></a>                    
                    <?php echo $this->Form->input('content',array(
                        'label' => false,
                        'div' => false,
                        'type' => 'textarea',
                        'id' => 'editor',
                        'value' => str_replace('<p>\n</p>', '', $detail['CloggyContent']['content'])
                    )); ?>
                    <span class="help-block"><?php if (isset($errors['content'][0])) echo $errors['content'][0]; ?></span>	
                </div>
                <div class="span2">
                    <label><?php echo __d('cloggy','Categories'); ?></label> 

                    <?php if (empty($categories)) : ?>
                        <?php echo $this->Form->input('categories',array(
                            'label' => __d('cloggy','Categories'),
                            'div' => false,
                            'type' => 'text',
                            'class' => 'span2'
                        )); ?>
                    <?php else: ?>
                        <label><?php echo __d('cloggy','Categories'); ?></label>
                        <?php echo $this->Form->select('categories',$listCategories,array(
                            'label' => false,
                            'multiple' => 'true',
                            'style' => 'width: 140px',                            
                            'value' => $postCategories
                        )); ?>
                    <?php endif; ?>
                     					
                    <?php echo $this->Form->input('tags',array(
                        'type' => 'text',
                        'div' => false,
                        'label' => __d('cloggy','Tags'),
                        'class' => 'span2',
                        'placeholder' => __d('cloggy','tags'),
                        'value' => (!empty($postTags)) ? join(',', $postTags) : ''
                    )); ?>
                    
                    <?php echo $this->Form->select('status',array(
                        'publish' => __d('cloggy','Publish'),
                        'draft' => __d('cloggy','Draft')),array(
                        'style' => 'width: 140px',
                        'value' => 'publish',                        
                    )); ?>
                    
                    <hr />
                    <?php echo $this->Form->submit(__d('cloggy','Update'),array(
                        'class' => 'btn btn-primary',
                        'div' => false,                        
                    )); ?>   
                </div>
            </div>
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>

<?php $postNodeId = $id; echo $this->element('cloggy_blog_post_image_dialog',compact('postNodeId','image')); //load image dialog ?>

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