<?php
echo $this->Form->create('CloggyBlogPost', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/edit/' . $id
));
?>
<fieldset>
  <legend>Edit: '<?php echo $detail['CloggySubject']['subject']; ?>'</legend>
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

          <label>Title</label> 
          <input type="text" placeholder="title" class="span6" name="data[CloggyBlogPost][title]" value="<?php echo $detail['CloggySubject']['subject']; ?>" />
          <span class="help-block"><?php if (isset($errors['title'][0])) echo $errors['title'][0]; ?></span><br  /> 		
          <label>Content</label>
          <textarea id="editor" name="data[CloggyBlogPost][content]"><?php echo str_replace('<p>\n</p>', '', $detail['CloggyContent']['content']); ?></textarea>
          <span class="help-block"><?php if (isset($errors['content'][0])) echo $errors['content'][0]; ?></span>	
        </div>
        <div class="span2">
          <label>Categories</label> 

          <?php if (empty($categories)) : ?>
            <input type="text" name="data[CloggyBlogPost][categories]" placeholder="categories" class="span2" /><br  />
          <?php else: ?>
            <select multiple="multiple" class="span2" name="data[CloggyBlogPost][categories][]">						
              <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category['CloggySubject']['subject']; ?>" 
                        <?php if (array_key_exists($category['CloggyNode']['id'], $postCategories)) echo 'selected="selected"' ?>>
                          <?php echo $category['CloggySubject']['subject']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          <?php endif; ?>

          <label>Tags</label> 					
          <input type="text" name="data[CloggyBlogPost][tags]" placeholder="tags" class="span2" value="<?php if (!empty($postTags)) echo join(',', $postTags); ?>" /><br  />					
          <hr />
          <input type="submit" name="submit" value="Publish Now" class="btn btn-primary" />
          <input type="submit" name="submit" value="Draft" class="btn" /> 
        </div>
      </div>
    </div>
  </div>
</fieldset>
<?php echo $this->Form->end(); ?>

<?php $this->append('cloggy_js_module_page'); ?>
<script type="text/javascript">
  //set host
  var host = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/' . Configure::read('Cloggy.theme_used') . '/', true); ?>';

  yepnope({
    load: [host+'vendor/ckeditor/ckeditor.js'],
    complete: function() {
			
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
			
    }
  });

</script>
<?php $this->end(); ?>