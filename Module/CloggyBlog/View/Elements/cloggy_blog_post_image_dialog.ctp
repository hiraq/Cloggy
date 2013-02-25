<div class="modal hide fade" id="cloggy_modal_image">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Image Management</h3>
    </div>
    <div class="modal-body">
        
        <ul id="cloggyImageTab" class="nav nav-tabs">
            <li class="active"><a href="#cloggyImageTabUpload" data-toggle="tab">Upload</a></li>
            <li><a href="#cloggyImageTabImages" data-toggle="tab"id="imageTab">Images</a></li>
        </ul>
        
        <div id="cloggyImageTab" class="tab-content">
            <div id="cloggyImageTabUpload" class="tab-pane fade active in">
                <div>
                    <label>Crop Image</label>
                    <input type="text" class="input-small" name="width" placeholder="width" id="imageWidth" />    
                    <input type="text" class="input-small" name="height" placeholder="height" id="imageHeight" />                    
                </div>                
                <a href="#" class="btn" id="upload">Select Image</a><br />
                <div id="filename"></div>
            </div>
            <div id="cloggyImageTabImages" class="tab-pane fade">
                <div id="imageFile">
                    <?php if (isset($image)) : echo '<img src="'.$this->CloggyBlogAsset->getImage($image).'" width="530px" height="300px" />'; ?>                    
                    <?php else: ?>
                    No image uploaded
                    <?php endif; ?>
                </div>
            </div>
        </div>                
        
    </div>    
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary" id="uploadButton">Upload Now</button>
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
                
                var imageWidth = jQuery('#imageWidth').val();
                var imageHeight = jQuery('#imageHeight').val();
                var notifResponse;
                var filename;
                
                /*
                 * setup ocupload
                 */
                var cloggyImageUpload = jQuery('#upload').upload({
                    action: '<?php echo CloggyCommon::urlModule('cloggy_blog', 'cloggy_blog_posts/upload_image') ?>',
                    name: 'image',
                    autoSubmit: false,
                    enctype: 'multipart/form-data',                    
                    onSubmit: function() {
                        this.params({
                            width: jQuery('#imageWidth').val(),
                            height: jQuery('#imageHeight').val(),
                            postId: '<?php echo $postNodeId; ?>'
                        });                        
                    },
                    onSelect: function() {
                        
                        filename = this.filename();
                        jQuery('#filename').html('');
                        jQuery('#filename').append(filename);
                        
                    },
                    onComplete: function(response) {
                        
                        if (response == 'failed') {
                            
                            var notif = '<div class="alert alert-error">\n\
                                <strong>Upload failed!</strong>\n\
                                </div>';                                                                                        
                            
                        } else if(response == 'success') {
                            var notif = '<div class="alert alert-success">\n\
                                <strong>Upload success</strong>.\n\
                                </div>';  
                        } else {
                            var notif = '<div class="alert">\n\
                                <strong>'+response+'</strong>.\n\
                                </div>';  
                        }
                        
                        jQuery('#filename').html('');                        
                        jQuery('#filename').append(notif); 
                        
                        notifResponse = response;                                             
                        
                        /*
                         * set timeout to click image tab
                         */
                        window.setTimeout(function() {
                            jQuery('#imageTab').trigger('click');
                            
                            if (notifResponse == 'Upload success') {
                                jQuery('#imageFile').html('');
                                jQuery('#imageFile').append(
                                '<img src="<?php echo $this->CloggyBlogAsset->getImageUploadPath().$postNodeId.'/'; ?>'+filename+'" width="530px" height="300px" />');
                            }
                            
                        },1500);
                        
                    }
                });                                
                
                /*
                 * trigger input file
                 */
                jQuery('#upload').on('click',function(e) {
                    jQuery('input[name="image"]').val('');
                    jQuery('input[name="image"]').trigger('click');
                });           
                
                /*
                 * submit image
                 */
                jQuery('#uploadButton').on('click',function(e) {
                    cloggyImageUpload.submit();
                });
                
            });
           
       }
    });

</script>

<?php $this->end(); ?>