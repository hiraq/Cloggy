<?php echo $this->element('clog_blog_home_posts',array('posts' => $posts)); ?>
<?php echo $this->element('clog_blog_home_categories',array('categories' => $categories)); ?>
<?php echo $this->element('clog_blog_home_tags',array('tags' => $tags)); ?>

<?php $this->append('clog_js_module_page'); ?>
<script type="text/javascript">
clog.captureJQuery(function() {
	jQuery('.post_remove').on('click',function(e) {
		e.preventDefault();
		var href = jQuery(this).attr('href');
		if(confirm('Are you sure want to remove this item?')) {
			window.location = href;
		}
	});
	jQuery('.post_disable').on('click',function(e) {
		e.preventDefault();
		var href = jQuery(this).attr('href');
		if(confirm('Are you sure want to disable this item?')) {
			window.location = href;
		}
	});
});
</script>
<?php $this->end(); ?>