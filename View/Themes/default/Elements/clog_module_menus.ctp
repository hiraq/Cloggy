<?php $menus = $this->ClogMenus->menu($moduleKeyMenus); ?>
<?php if(!empty($menus)) : ?>	
	<?php foreach($menus as $menu => $link) :?>
	<li><?php echo $this->ClogMenus->getLink($menu,$link); ?></li>
	<?php endforeach; ?>
<?php endif; ?>