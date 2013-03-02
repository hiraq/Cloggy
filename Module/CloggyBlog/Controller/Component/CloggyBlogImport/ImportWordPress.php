<?php

class ImportWordPress {
    
    private $__data;    
    
    private $__posts;
    private $__categories;
    private $__tags;
    private $__attachments;
    
    private $__options = array(
        'import_featured_image' => 0,
        'make_draft' => 0,
        'only_published_posts' => 0,
        'only_drafted_posts' => 0,
        'disable_categories' => 0,
        'disable_tags' => 0,
        'disable_metas' => 0,
        'include_custom_post_types' => 0
    );
    
    /**
     * Initialize class
     * @param array $data
     * @param array $options
     */
    public function __construct($data,$options) {
        $this->__data = $data;
        $this->__options = array_merge($this->__options,$options);
    }
    
    /**
     * Check if given data is valid wordpress xml data
     * @return boolean
     */
    public function isValid() {
        
        if (is_array($this->__data) && !empty($this->__data)) {
            
            if (isset($this->__data['rss']['channel']['wp:wxr_version']))  {
                return true;
            } else {
                return false;
            }
            
        }
        
        return false;
        
    }
    
    /**
     * Setup data to import
     */
    public function import() {
        
        //import posts
        $this->__import_posts();
        
        /*
         * if featured image need to import
         */
        if ($this->__options['import_featured_image'] == 0) {
         
            //import attachments
            $this->__import_attachments();
            
        }        
        
        /*
         * if categories need to import
         */
        if ($this->__options['disable_categories'] == 0) {
         
            //import categories
            $this->__import_categories();
            
        }   
        
        /*
         * if tags need to import
         */
        if ($this->__options['disable_tags'] == 0) {
         
            //import tags
            $this->__import_tags();
            
        }                
                
        /*
         * import custom post types
         */
        if ($this->__options['include_custom_post_types'] == 1) {
            $this->__import_posts(true);
        }                
        
    }
    
    /**
     * Import all wordpress posts
     * @param boolean $includeCustomPostType [optional]
     */
    private function __import_posts($includeCustomPostType=false) {
        
        $items = $this->__data['rss']['channel']['item'];
        
        /*
         * parse data
         */
        for($i = 0;$i < count($items);$i++) {
            
            $item = $items[$i];
            
            $postStatus = $item['wp:status'];
            $postType = $item['wp:post_type'];
            $postCategory = isset($item['category']) ? $item['category'] : '';
            
            $categories = array();
            $tags = array();
            
            /*
             * get post categories or tags
             * if disable_categories == 0
             */
            if (!empty($postCategory) && $this->__options['disable_categories'] == 0) {
                
                foreach($postCategory as $taxIndex => $tax) {
                    
                    if (is_numeric($taxIndex)) {
                     
                        if ($tax['@domain'] == 'post_tag') {
                            $tags[] = $tax['@'];
                        }

                        if ($tax['@domain'] == 'category') {
                            $categories[] = $tax['@'];
                        }    
                        
                    } else {
                        
                        if ($taxIndex == '@domain' && $tax == 'post_tag') {
                            $tags[] = $postCategory['@'];
                        }
                        
                        if ($taxIndex == '@domain' && $tax == 'category') {
                            $categories[] = $postCategory['@'];
                        }
                        
                    }                                                   

                }                            
                
            }
            
            /*
             * post type conditions
             */
            if (!$includeCustomPostType) {
                $postTypeConditions = $postType == 'post';
            } else {
                $postTypeConditions = $postType != 'post' && $postType != 'page' && $postType != 'attachment';
            }
                  
            /*
             * fetch based on post type conditions
             */
            if ($postTypeConditions) {
                
                if ($this->__options['only_published_posts'] == 0 
                        && $this->__options['only_drafted_posts'] == 0) {
                    
                    $this->__posts[] = array(
                        'post_id' => $item['wp:post_id'],
                        'title' => $item['title'],
                        'content' => $item['content:encoded'],
                        'permalink' => $item['wp:post_name'],
                        'categories' => $categories,
                        'tags' => $tags
                    );
                    
                } elseif ($this->__options['only_published_posts'] == 1) {
                    
                    if ($postStatus == 'publish') {
                        
                        $this->__posts[] = array(
                            'title' => $item['title'],
                            'content' => $item['content:encoded'],
                            'permalink' => $item['wp:post_name'],
                            'categories' => $categories,
                            'tags' => $tags
                        );
                        
                    }
                    
                } elseif ($this->__options['only_drafted_posts'] == 1) {
                    
                    if ($postStatus == 'draft') {
                        
                        $this->__posts[] = array(
                            'title' => $item['title'],
                            'content' => $item['content:encoded'],
                            'permalink' => $item['wp:post_name'],
                            'categories' => $categories,
                            'tags' => $tags
                        );
                        
                    }
                    
                }
                
            }
            
        }
        
    }
    
    private function __import_categories() {
        
    }
    
    private function __import_tags() {
        
    }
    
    private function __import_attachments() {
        
    }        
    
}