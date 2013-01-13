<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
  <thead>			
    <tr>						
      <th width="2%"><input type="checkbox" name="checker" id="checker" /></th>
      <th>Tag Name</th>			
      <th>Total Posts</th>							
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($tags)) : ?>
      <?php foreach ($tags as $tag) : ?>
        <tr>					
          <td><input type="checkbox" name="tag[]" value="<?php echo $tag['CloggyNode']['id']; ?>" /></td>
          <td>
            <?php echo $this->Html->link($tag['CloggySubject']['subject'], '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_tags/edit/' . $tag['CloggyNode']['id']);
            ?>
          </td>
          <td>
            <?php echo count($tag['CloggyRelNode']); ?>
          </td>																	
          <td>
            <?php echo $this->Html->link('Edit', '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_tags/edit/' . $tag['CloggyNode']['id']);
            ?>
            |
            <?php echo $this->Html->link('Remove', '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_tags/remove/' . $tag['CloggyNode']['id'], array('class' => 'post_remove'));
            ?>																					
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7">No data available</td>
      </tr>
    <?php endif; ?>	
    <tr id="checkbox_all" style="display:none">
      <td colspan="8">
        <div class="btn-group">
          <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
            With Selected <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            <li><?php echo $this->Html->link('Delete All', '#', array('id' => 'action_delete_all', 'class' => 'action_js')); ?></li>						
          </ul>
        </div>	
      </td>
    </tr>			
  </tbody>
</table>
<?php echo $this->Form->end(); ?>

<div class="pagination pagination-centered">
  <ul>
    <?php
    $this->CloggyPaginator->paginatorBootstrap('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog');
    ?>		
  </ul>
</div>

<?php $this->append('cloggy_js_module_page'); ?>
<script type="text/javascript">
  cloggy.captureJQuery(function() {	
	
    jQuery('#checker').on('click',function(e) {
      jQuery('tbody').find(':checkbox').attr('checked',this.checked);
      if(this.checked) {
        jQuery('#checkbox_all').fadeIn('slow');
      }else{
        jQuery('#checkbox_all').fadeOut('slow');
      }
    });
	
    jQuery('.action_js').on('click',function(e) {

      e.preventDefault();
      var id = jQuery(this).attr('id');
      var urlAjax;
      var formData = jQuery('input[type="checkbox"]').serializeArray();
      formData.shift();
      var confirmAction = true;
		
      switch(id) {

        case 'action_delete_all':
          urlAjax = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_ajax/delete_all_tags'); ?>';
          confirmAction = confirm('Are you sure want to delete all these tags?');		
          break;			
		
        }			

        if(confirmAction) {

          jQuery.ajax({
            url: urlAjax,
            data: formData,
            dataType: 'json',
            type: 'POST',
            cache: false,
            success: function(data,status,jqxhr) {
					
              if(data.msg && data.msg == 'success') {
                window.location = window.location.href;
              }
					
            }
          });
				
        }		
		
      });
	
      jQuery('.post_remove').on('click',function(e) {
		
        e.preventDefault();
        var href = jQuery(this).attr('href');
        if(confirm('Are you sure to remove this tag?')) {
          window.location = href;
        }
			
      });	
	
    });
</script>
<?php $this->end(); ?>