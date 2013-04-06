<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
    <thead>	
        <tr>
            <th colspan="9"><?php echo __d('cloggy','Categories'); ?> - <?php echo $this->Html->link(__d('cloggy','Manage'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_categories');
?></th>
        </tr>	
        <tr>						
            <th><?php echo __d('cloggy','Category Name'); ?></th>			
            <th><?php echo __d('cloggy','Total Posts'); ?></th>							
            <th><?php echo __d('cloggy','Actions'); ?></th>
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
                        <?php echo $this->Html->link(__d('cloggy','Edit'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_categories/edit/' . $category['CloggyNode']['id']);
                        ?>
                        |
                        <?php echo $this->Html->link(__d('cloggy','Remove'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_categories/remove/' . $category['CloggyNode']['id'], array('class' => 'post_remove'));
                        ?>																					
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7"><?php echo __d('cloggy','No data available'); ?></td>
            </tr>
        <?php endif; ?>			
    </tbody>
</table>
<?php echo $this->Form->end(); ?>