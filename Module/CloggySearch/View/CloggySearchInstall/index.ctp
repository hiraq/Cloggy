<?php if (isset($createdTables) && !empty($createdTables)) : ?>
    <?php foreach($createdTables as $table => $status) : ?>
        
        <?php if($status) : ?>
                <div class="alert alert-success">                
                    <strong><?php echo $table; ?></strong> <?php echo __d('cloggy','has been installed'); ?>
                </div>
        <?php else: ?>
                <div class="alert alert-error">                
                    <strong><?php echo $table; ?></strong> <?php echo __d('cloggy','failed to install'); ?>
                </div>
        <?php endif; ?>

    <?php endforeach; ?>
<?php else: ?>
<div class="alert">                
    <?php echo __d('cloggy','This module has been installed.'); ?>
</div>
<?php endif; ?>
