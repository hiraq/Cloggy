<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
    <thead>	
        <tr>
            <th colspan="9">Tags - <?php echo $this->Html->link('Manage', '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_tags');
?></th>
        </tr>	
        <tr>						
            <th>Tag</th>			
            <th>Total Posts</th>							
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($tags)) : ?>
            <?php foreach ($tags as $tag) : ?>
                <tr>					
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
    </tbody>
</table>
<?php echo $this->Form->end(); ?>