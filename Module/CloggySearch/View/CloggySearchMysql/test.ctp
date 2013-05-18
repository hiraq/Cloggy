<?php
echo $this->Form->create('CloggySearch', array(
    'url' => '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_search/cloggy_search_mysql/test',
    'class' => 'form-inline'
));
?>

<?php echo $this->Form->input('query',array(
    'type' => 'text',
    'label' => false,
    'placeholder' => __d('cloggy','your query'),
    'div' => false,
    'class' => 'input-big'
)); ?>

<?php echo $this->Form->submit(__d('cloggy','Search'),array(
    'class' => 'btn',
    'div' => false,                        
)); ?>  

<?php echo $this->Form->end(); ?>

<?php if (isset($results) && isset($query)) : ?>

<p>
    <?php echo __d('cloggy','Search term : '); ?><strong><?php echo $query; ?></strong>
</p>
<p>
    <?php echo __d('cloggy','Search mode : '); ?><strong><?php echo $results['mode']; ?></strong>
</p>    
    
<table class="table table-bordered">
    <thead>
        <tr>
            <th><?php echo __d('cloggy','Table Name'); ?></th>
            <th><?php echo __d('cloggy','Field'); ?></th>
            <th><?php echo __d('cloggy','Snippet'); ?></th>
            <th><?php echo __d('cloggy','Rating'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($results['results'])) : ?>
            <?php foreach($results['results'] as $result) : ?>
                <tr>
                    <td><?php echo $result['CloggySearchFullText']['source_table_name']; ?></td>
                    <td><?php echo $result['CloggySearchFullText']['source_table_field']; ?></td>
                    <td>
                        <?php
                            if (!empty($result['CloggySearchFullText']['source_text'])) {
                                echo $this->Text->truncate($result['CloggySearchFullText']['source_text'],50);
                            } elseif (!empty($result['CloggySearchFullText']['source_sentences'])) {
                                echo $this->Text->truncate($result['CloggySearchFullText']['source_sentences'],50);
                            } else {
                                echo __d('cloggy','no snippet');
                            }                                                        
                        ?>
                    </td>
                    <td><?php echo $this->Number->precision($result[0]['rating'],2) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
                <tr>
                    <td colspan="6"><?php echo __d('cloggy','No results found, maybe you can try to disable "mysqlFullText stopwords" in your mysql config file. '); ?></td>
                </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php endif; ?>
