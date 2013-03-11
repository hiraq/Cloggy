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
        
        /////////////////////INSERT INTO DATABASE////////////////////////////////
        
        if (!empty($this->__categories)) {
            $this->__convert_taxonomies();
        }
        
        if (!empty($this->__tags)) {
            $this->__convert_taxonomies('tag');
        }
        
        if (!empty($this->__posts)) {
            $this->__convert_posts();
        }
        
        if (!empty($this->__attachments)) {
            $this->__download_attachments();
        }
        
        /////////////////////END////////////////////////////////
        
        return true;
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
            if (!empty($postCategory)) {
                
                foreach($postCategory as $taxIndex => $tax) {
                    
                    if (is_numeric($taxIndex)) {
                     
                        if ($tax['@domain'] == 'post_tag' 
                                && $this->__options['disable_tags'] == 0) {
                            $tags[] = $tax['@'];
                        }

                        if ($tax['@domain'] == 'category' 
                                && $this->__options['disable_categories'] == 0) {
                            $categories[] = $tax['@'];
                        }    
                        
                    } else {
                        
                        if ($taxIndex == '@domain' && $tax == 'post_tag' 
                                && $this->__options['disable_tags'] == 0) {
                            $tags[] = $postCategory['@'];
                        }
                        
                        if ($taxIndex == '@domain' && $tax == 'category' 
                                && $this->__options['disable_categories'] == 0) {
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
    
    private function __convert_posts() {
        
        
        
    }
    
    private function __convert_taxonomies($taxo='category') {
        
        /*
         * taxonomies: category
         */
        if ($taxo == 'category') {
            
            $needParents = array();
            $categories = array();
            
            if (!empty($this->__categories)) {

                foreach($this->__categories as $category) {

                    /*
                     * setup parents
                     */
                    if (!empty($category['category_parent'])) {
                        $needParents[$category['category_name']] = $category['category_parent'];
                    }

                    $categories[] = $category['category_name'];

                }

            }
            
            $CategoryModel = ClassRegistry::init('CloggyBlogCategory');
            $user = AuthComponent::user();
            
            if (!empty($categories)) {
                
                //save categories
                $CategoryModel->proceedCategories($categories,$user['id']);
                
                /*
                 * save category parent
                 */
                if (!empty($needParents)) {
                    
                    foreach($needParents as $child => $parent) {
                        
                        $childId = $CategoryModel->getCategoryIdByName($child);
                        $parentId = $CategoryModel->getCategoryIdByName($parent);
                        
                        /*
                         * update category parent
                         */
                        if ($childId && $parentId) {
                            $CategoryModel->updateCategoryParent($childId,$parentId);
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
    }
    
    private function __download_attachments() {
        
    }
    
    /**
     * Import wordpress categories
     */
    private function __import_categories() {
        
        if (isset($this->__data['rss']['channel']['wp:category'])) {
            
            $categories = $this->__data['rss']['channel']['wp:category'];
            if (!empty($categories)) {
                
                foreach($categories as $category) {
                    
                    $this->__categories[] = array(
                        'term_id' => $category['wp:term_id'],
                        'category_name' => $category['wp:cat_name'],
                        'category_parent' => $category['wp:category_parent']
                    );
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * Import wordpress tags
     */
    private function __import_tags() {
        
        if (isset($this->__data['rss']['channel']['wp:tag'])) {
            
            $tags = $this->__data['rss']['channel']['wp:tag'];
            if (!empty($tags)) {
                
                foreach($tags as $tag) {
                    
                    $this->__tags[] = array(
                        'term_id' => $tag['wp:term_id'],
                        'tag_name' => $tag['wp:tag_name'],                        
                    );
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * Import wordpress featured image
     */
    private function __import_attachments() {
        
        $items = $this->__data['rss']['channel']['item'];
        
        for ($i=0;$i < count($items); $i++) {
            
            $item = $items[$i];                        
            $postType = $item['wp:post_type'];
            
            if ($postType == 'attachment') {
                
                $this->__attachments[] = array(
                    'attachment_id' => $item['wp:post_id'],
                    'post_id' => $item['wp:post_parent'],
                    'title' => $item['title'],
                    'url' => $item['wp:attachment_url']
                );
                
            }
            
        }
        
    }        
    
}