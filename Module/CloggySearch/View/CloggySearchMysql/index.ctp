<?php echo $this->Form->create('#', array('id' => 'search_mysql')); ?>
<div class="container">
    <p>
        <?php echo __d('cloggy','Total indexed data : ').'<strong>'.$totalIndexed.'</strong>'; ?>
    </p>
    <p>
        <?php echo __d('cloggy','Last updated data : ').'<strong>'.$latestUpdate.'</strong>'; ?>
    </p>
</div>
<table class="table table-hover table-bordered">
    <thead>			
        <tr>						
            <th width="2%"><input type="checkbox" name="checker" id="checker" /></th>
            <th><?php echo __d('cloggy','Table Name'); ?></th>			
            <th><?php echo __d('cloggy','Table Key'); ?></th>							
            <th><?php echo __d('cloggy','Table Field'); ?></th>
            <th><?php echo __d('cloggy','Actions'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($indexedTables)) : ?>
            <?php foreach ($indexedTables as $table) : ?>
                <tr>					
                    <td><input type="checkbox" name="category[]" value="<?php echo $table['CloggySearchFullText']['id']; ?>" /></td>
                    <td>
                        <?php echo $table['CloggySearchFullText']['source_table_name']; ?>
                    </td>
                    <td>
                        <?php echo $table['CloggySearchFullText']['source_table_key']; ?>
                    </td>																	
                    <td>
                        <?php echo $table['CloggySearchFullText']['source_table_field']; ?>
                    </td>
                    <td>                        
                        <?php echo $this->Html->link(__d('cloggy','Remove'), '/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_search/cloggy_search_mysql/remove_table/' . $table['CloggySearchFullText']['id'], array('class' => 'post_remove'));
                        ?>																					
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7"><?php echo __d('cloggy','No data available'); ?></td>
            </tr>
        <?php endif; ?>	
        <tr id="checkbox_all" style="display:none">
            <td colspan="8">
                <div class="btn-group">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                        <?php echo __d('cloggy','With Selected'); ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><?php echo $this->Html->link(__d('cloggy','Delete All'), '#', array('id' => 'action_delete_all', 'class' => 'action_js')); ?></li>						
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
        $this->CloggyPaginator->paginatorBootstrap('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_search/');
        ?>		
    </ul>
</div>

<?php $this->append('cloggy_js_module_page'); ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
	
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
                    urlAjax = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_search/cloggy_search_mysql/delete_all_tables'); ?>';
                    confirmAction = confirm('<?php echo __d('cloggy','Are you sure want to delete all selected indexed tables?'); ?>');		
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
                if(confirm('<?php echo __d('cloggy','Are you sure to remove this indexed table?') ?>')) {
                    window.location = href;
                }
			
            });	
	
        });
</script>
<?php $this->end(); ?>