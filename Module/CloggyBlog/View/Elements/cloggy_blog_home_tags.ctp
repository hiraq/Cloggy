<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
    <thead>	
        <tr>
            <th colspan="9">Tags - <?php echo $this->Html->link(__d('cloggy','Manage'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_tags');
?></th>
        </tr>	
        <tr>						
            <th><?php echo __d('cloggy','Tag'); ?></th>			
            <th><?php echo __d('cloggy','Total Posts'); ?></th>							
            <th><?php echo __d('cloggy','Actions'); ?></th>
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
                        <?php echo $this->Html->link(__d('cloggy','Edit'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_tags/edit/' . $tag['CloggyNode']['id']);
                        ?>
                        |
                        <?php echo $this->Html->link(__d('cloggy','Remove'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_tags/remove/' . $tag['CloggyNode']['id'], array('class' => 'post_remove'));
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