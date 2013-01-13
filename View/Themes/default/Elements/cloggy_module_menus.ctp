<?php $menus = $this->CloggyMenus->menu($moduleKeyMenus); ?>
<?php if (!empty($menus)) : ?>	
  <?php foreach ($menus as $menu => $link) : ?>
    <li><?php echo $this->CloggyMenus->getLink($menu, $link); ?></li>
  <?php endforeach; ?>
<?php endif; ?>