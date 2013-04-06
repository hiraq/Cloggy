<?php echo $this->Form->create('#', array('id' => 'users_index')); ?>
<table class="table table-hover table-bordered">
    <thead>	
        <tr>
            <th colspan="8">Posts - <?php echo $this->Html->link(__d('cloggy','Manage'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts');
?></th>
        </tr>	
        <tr>						
            <th><?php echo __d('cloggy','Title'); ?></th>			
            <th><?php echo __d('cloggy','Author'); ?></th>
            <th><?php echo __d('cloggy','Status'); ?></th>
            <th><?php echo __d('cloggy','Created'); ?></th>			
            <th><?php echo __d('cloggy','Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($posts)) : ?>
            <?php foreach ($posts as $post) : ?>
                <tr>					
                    <td>
                        <?php echo $this->Html->link($post['CloggySubject']['subject'], '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/edit/' . $post['CloggyNode']['id']);
                        ?>
                    </td>
                    <td>
                        <?php echo $this->Html->link($post['CloggyUser']['user_name'], '/' . Configure::read('Cloggy.url_prefix') . '/module/users/users_home/edit/' . $post['CloggyUser']['id']);
                        ?>
                    </td>					
                    <td>
                        <?php if ($post['CloggyNode']['node_status'] == 1) : ?>
                            <span class="label label-success"><?php echo __d('cloggy','Published'); ?></span>
                        <?php else: ?>
                            <span class="label label-important"><?php echo __d('cloggy','Draft'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $this->Time->format('M jS, Y, H:i A', $post['CloggyNode']['node_created']); ?></td>					
                    <td>
                        <?php echo $this->Html->link(__d('cloggy','Edit'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/edit/' . $post['CloggyNode']['id']);
                        ?>
                        |
                        <?php echo $this->Html->link(__d('cloggy','Remove'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/remove/' . $post['CloggyNode']['id'], array('class' => 'post_remove'));
                        ?>
                        |

                        <?php if ($post['CloggyNode']['node_status'] > 0) : ?>
                            <?php echo $this->Html->link(__d('cloggy','Make a draft'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/draft/' . $post['CloggyNode']['id'], array('class' => 'post_disable'));
                            ?>
                        <?php else: ?>
                            <?php echo $this->Html->link(__d('cloggy','Publish'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/publish/' . $post['CloggyNode']['id'], array('id' => 'post_enable'));
                            ?>
                        <?php endif; ?>
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