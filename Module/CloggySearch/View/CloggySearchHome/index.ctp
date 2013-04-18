<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th><?php echo __d('cloggy','Supported Engines'); ?></th>
            <th><?php echo __d('cloggy','Action'); ?></th>
        </tr>        
    </thead>
    <tbody>
        <?php if (isset($engines) && !empty($engines)) : ?>
            <?php foreach($engines['engines'] as $engine) : ?>
            <tr>
                <td><?php echo $engine; ?></td>
                <td>
                    <?php $url = CloggyCommon::urlModule('CloggySearch', 
                            strtolower(Inflector::underscore($engines['controllers'][$engine]))); ?>
                    <a href="<?php echo $url; ?>"><?php echo __d('cloggy','Manage'); ?></a> |
                    <a href="<?php echo $url.'update'; ?>"><?php echo __d('cloggy','Update'); ?></a> |
                    <a href="<?php echo $url.'test'; ?>"><?php echo __d('cloggy','Test'); ?></a> | 
                    <a href="<?php echo $url.'help'; ?>"><?php echo __d('cloggy','Help'); ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>