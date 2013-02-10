<div class="modal hide fade" id="cloggy_modal_image">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Image Management</h3>
    </div>
    <div class="modal-body">
        
        <ul id="cloggyImageTab" class="nav nav-tabs">
            <li class="active"><a href="#cloggyImageTabUpload" data-toggle="tab">Upload</a></li>
            <li><a href="#cloggyImageTabImages" data-toggle="tab">Images</a></li>
        </ul>
        
        <div id="cloggyImageTab" class="tab-content">
            <div id="cloggyImageTabUpload" class="tab-pane fade active in">
                <a href="#" class="btn" id="upload">Upload Image</a><br />
                <div id="filename"></div>
            </div>
            <div id="cloggyImageTabImages" class="tab-pane fade">images</div>
        </div>                
        
    </div>    
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary">Save changes</button>
    </div>
</div>

<?php $this->append('cloggy_js_module_page'); ?>

<script type="text/javascript">
    
    //set host
    var host = '<?php echo Router::url('/' . Configure::read('Cloggy.url_prefix') . '/' . Configure::read('Cloggy.theme_used') . '/', true); ?>';

    /*
     * Twitter Bootstrap modal & tabbing
     */
    cloggy.captureJQuery(function() {
        
        //bootstrap modal event hook > shown
        jQuery('#cloggy_modal_image').on('shown',function(e) {
            jQuery('input[name="image"]').val('');
            jQuery('#filename').html('');
        });
        
        //bootstrap modal event hook > hide
        jQuery('#cloggy_modal_image').on('hide',function(e) {
            jQuery('input[name="image"]').val('');
            jQuery('#filename').html('');
        });
        
        //image tabbing management
        jQuery('#cloggyImageTab a').on('click',function(e) {
            e.preventDefault();
            jQuery('#filename').html('');
            jQuery(this).tab('show');
        });
        
        
    });        
    
    /*
     * jquery.ocuplod
     * process
     */
    yepnope({
       load: [host+'vendor/jquery.ocupload.js'],
       complete: function() {
           
            cloggy.captureJQuery(function() {                                
                
                /*
                 * setup ocupload
                 */
                var cloggyImageUpload = jQuery('#upload').upload({
                    name: 'image',
                    autoSubmit: false,
                    enctype: 'multipart/form-data',
                    onSelect: function() {
                        
                        var filename = this.filename();
                        jQuery('#filename').html('');
                        jQuery('#filename').append(filename);
                        
                    }
                });                                
                
                /*
                 * trigger input file
                 */
                jQuery('#upload').on('click',function(e) {
                    jQuery('input[name="image"]').val('');
                    jQuery('input[name="image"]').trigger('click');
                });                                
                
            });
           
       }
    });

</script>

<?php $this->end(); ?>