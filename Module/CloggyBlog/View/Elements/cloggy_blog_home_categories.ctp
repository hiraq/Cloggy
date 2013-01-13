<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
  <thead>	
    <tr>
      <th colspan="9">Categories - <?php echo $this->Html->link('Manage', '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_categories');
?></th>
    </tr>	
    <tr>						
      <th>Category Name</th>			
      <th>Total Posts</th>							
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($categories)) : ?>
      <?php foreach ($categories as $category) : ?>
        <tr>					
          <td>
            <?php echo $this->Html->link($category['CloggySubject']['subject'], '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_categories/edit/' . $category['CloggyNode']['id']);
            ?>
          </td>
          <td>
            <?php echo count($category['CloggyRelNode']); ?>
          </td>																	
          <td>
            <?php echo $this->Html->link('Edit', '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_categories/edit/' . $category['CloggyNode']['id']);
            ?>
            |
            <?php echo $this->Html->link('Remove', '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_categories/remove/' . $category['CloggyNode']['id'], array('class' => 'post_remove'));
            ?>																					
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr>
        <td colspan="7">No data available</td>
      </tr>
    <?php endif; ?>			
  </tbody>
</table>
<?php echo $this->Form->end(); ?>