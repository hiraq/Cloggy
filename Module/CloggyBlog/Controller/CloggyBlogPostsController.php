<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyBlogPostsController extends CloggyAppController {

    public $uses = array(
        'CloggyBlogPost',
        'CloggyBlogCategory',
        'CloggyBlogTag',
        'CloggyBlogMedia',
        'Cloggy.CloggyValidation'
    );        

    public function beforeFilter() {
        parent::beforeFilter();  
        $this->helpers[] = 'CloggyBlogAsset';
    }

    public function index() {

        $this->paginate = array(
            'CloggyBlogPost' => array(
                'limit' => 10,
                'contain' => false,
                'fields' => array(
                    'CloggyNode.id',
                    'CloggyNode.node_created',
                    'CloggyNode.node_status'
                )
            )
        );

        $posts = $this->paginate('CloggyBlogPost');
        $this->set(compact('posts'));
    }

    public function add() {

        $categories = $this->CloggyBlogCategory->getAllCategories();
        $tags = $this->CloggyBlogTag->getAllTags();
        
        /*
         * only if form not submitted
         */
        if (!$this->request->is('post')) {
            $postNodeId = $this->CloggyBlogPost->generatePost(array('userId' => $this->_user['id']));
        }        

        if ($this->request->is('post')) {

            $dataValidate = $this->request->data['CloggyBlogPost'];

            /*
             * custom validation post title
             */
            $checkPostSubject = $this->CloggyBlogPost->isTitleExists($this->request->data['CloggyBlogPost']['title'], $this->_user['id']);

            /*
             * setup validation
             */
            $this->CloggyValidation->set($dataValidate);
            $this->CloggyValidation->validate = array(
                'title' => array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => 'Title field required'
                    ),
                    'exists' => array(
                        'rule' => array('isValueEqual', $checkPostSubject, false),
                        'message' => 'The title, <strong>"' . $this->request->data['CloggyBlogPost']['title'] . '"</strong> has been exists'
                    )
                ),
                'content' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => 'Content field required'
                )
            );

            /*
             * validates
             */
            if ($this->CloggyValidation->validates()) {

                $cats = null;
                $tags = null;

                /*
                 * if user need categories
                 * then setup relation between post with these categories
                 */
                if (!empty($this->request->data['CloggyBlogPost']['categories'])) {

                    if (!is_array($this->request->data['CloggyBlogPost']['categories'])) {
                        $exp = explode(',', $this->request->data['CloggyBlogPost']['categories']);
                    } else {
                        $exp = $this->request->data['CloggyBlogPost']['categories'];
                    }

                    if (!empty($exp)) {
                        $cats = $this->CloggyBlogCategory->proceedCategories($exp, $this->_user['id']);
                    }
                }

                /*
                 * if user need tags
                 * then setup relation between post with these tags
                 */
                if (!empty($this->request->data['CloggyBlogPost']['tags'])) {

                    $exp = explode(',', $this->request->data['CloggyBlogPost']['tags']);
                    if (!empty($exp)) {
                        $tags = $this->CloggyBlogTag->proceedTags($exp, $this->_user['id']);
                    }
                }

                /*
                 * save post
                 */
                $postId = $this->CloggyBlogPost->generatePost(array(
                    'postNodeId' => $this->request->data['CloggyBlogPost']['post_id'],
                    'stat' => $this->request->data['submit'] == 'Draft' ? 0 : 1,
                    'userId' => $this->_user['id'],
                    'title' => $this->request->data['CloggyBlogPost']['title'],
                    'content' => $this->request->data['CloggyBlogPost']['content'],
                    'cats' => $cats,
                    'tags' => $tags
                ));

                /*
                 * redirect
                 */
                $this->Session->setFlash('Your post has been added', 'default', array(), 'success');
                $this->redirect(Router::url('/' . Configure::read('Cloggy.url_prefix') . '/module/cloggy_blog/cloggy_blog_posts/edit/' . $postId));
                exit();
            } else {
                $this->set('errors', $this->CloggyValidation->validationErrors);
            }
        }

        $this->set('title_for_layout', 'Cloggy - CloggyBlogPost - Add New Post');
        $this->set(compact('categories', 'tags','postNodeId'));
    }

    public function edit($id = null) {

        if (is_null($id)) {
            $this->redirect($this->referer());
            exit();
        }

        //change cacheQueries setting
        $this->CloggyBlogPost->cacheQueries = false;

        /*
         * setup data
         */
        $detail = $this->CloggyBlogPost->getSinglePostById($id);
        $categories = $this->CloggyBlogCategory->getAllCategories();
        $tags = $this->CloggyBlogTag->getAllTags();
        $postCategories = $this->CloggyBlogPost->getSinglePostTaxonomies($id);
        $postTags = $this->CloggyBlogPost->getSinglePostTaxonomies($id, 'cloggy_blog_tags', 'cloggy_blog_tag_post');
        $image = $this->CloggyBlogMedia->getImage($id);        
        
        /*
         * form submitted
         */
        if ($this->request->is('post')) {
            
            $dataValidate = array();
            $dataToSave = array();

            //remove new line
            $this->request->data['CloggyBlogPost']['content'] = str_replace('\n', '', $this->request->data['CloggyBlogPost']['content']);

            $title = $this->request->data['CloggyBlogPost']['title'];
            $content = $this->request->data['CloggyBlogPost']['content'];
            
            /*
             * if subject need to update
             */
            if ($detail['CloggySubject']['subject'] != $title) {
                $dataValidate['title'] = $title;
                $dataToSave['title'] = $title;
            }

            /*
             * if content need to update
             */
            if ($detail['CloggyContent']['content'] != $content) {
                $dataValidate['content'] = $content;
                $dataToSave['content'] = $content;
            }

            if (!empty($dataValidate)) {

                if ($this->CloggyValidation->validates()) {
                    
                    //update main data title & content				
                    $this->CloggyBlogPost->updatePost($id, $dataToSave);
                } else {
                    $this->set('errors', $this->CloggyValidation->validationErrors);
                }
            }

            /*
             * proceed taxonomies
             */
            $cats = null;
            $tags = null;

            if (!empty($this->request->data['CloggyBlogPost']['categories'])) {

                if (!is_array($this->request->data['CloggyBlogPost']['categories'])) {
                    $exp = explode(',', $this->request->data['CloggyBlogPost']['categories']);
                } else {
                    $exp = $this->request->data['CloggyBlogPost']['categories'];
                }

                if (!empty($exp)) {
                    $cats = $this->CloggyBlogCategory->proceedCategories($exp, $this->_user['id']);
                }
            }

            if (!empty($this->request->data['CloggyBlogPost']['tags'])) {

                $exp = explode(',', $this->request->data['CloggyBlogPost']['tags']);
                if (!empty($exp)) {
                    $tags = $this->CloggyBlogTag->proceedTags($exp, $this->_user['id']);
                }
            }

            /*
             * update taxonomies
             */
            $this->CloggyBlogPost->updatePostTaxonomies(array(
                'id' => $id,
                'taxo' => 'cloggy_blog_categories',
                'data' => $cats
            ));

            $this->CloggyBlogPost->updatePostTaxonomies(array(
                'id' => $id,
                'taxo' => 'cloggy_blog_tags',
                'data' => $tags
            ));

            /*
             * update stat
             */
            $stat = $this->request->data['submit'] == 'Draft' ? 0 : 1;
            $this->CloggyBlogPost->updatePostStat($id, $stat);

            /*
             * redirect
             */
            $this->Session->setFlash('Your post has been updated', 'default', array(), 'success');
            $this->redirect($this->request->here);
            exit();
        }

        $this->set('title_for_layout', 'Cloggy - CloggyBlogPost - Add New Post');
        $this->set(compact('categories', 'tags', 'id', 'detail', 'postCategories', 'postTags','image'));
    }
    
    public function upload_image() {
        
        $this->autoRender = false;
        $this->CloggyFileUpload = $this->Components->load('Cloggy.CloggyFileUpload');
        $this->CloggyImage = $this->Components->load('Cloggy.CloggyImage');
        
        //set post
        $postId = $this->request->data['postId'];
        
        /*
         * prepare for resize and cropping images
         */
        $width = intval($this->request->data['width']);
        $height = intval($this->request->data['height']);
        
        /*
         * setup upload files
         */
        $this->CloggyFileUpload->settings(array(
            'allowed_types' => array('jpg','jpeg','png','gif'),
            'field' => 'image',
            'folder_dest_path' => APP.'Plugin'.DS.'Cloggy'.DS.'webroot'.DS.'uploads'.DS.'CloggyBlog'.DS.'images'.DS.$postId.DS
        ));
        
        //upload image
        $this->CloggyFileUpload->proceedUpload();
        
        //check error upload
        $checkError = $this->CloggyFileUpload->isError();                
        
        if ($checkError) {            
            echo 'failed';
        } else { 
            
            //get uploaded data
            $uploadedData = $this->CloggyFileUpload->getUploadedData();
            
            /*
             * check if width and height is set
             */
            if ($width > 0 && $height > 0) {
                
                /*
                 * crop image
                 */                           
               $this->CloggyImage->settings(array(
                   'image' => $uploadedData['dirname'].DS.$uploadedData['basename'],
                   'width' => $width,
                   'height' => $height,
                   'option' => 'exact',
                   'command' => 'crop',
                   'save_path' => $uploadedData['dirname'].DS.$uploadedData['filename'].'_thumb_'.$width.'_'.$height.'.'.$uploadedData['extension']
               ));

               //proceed cropping image
               $this->CloggyImage->proceed();

               $checkError = $this->CloggyImage->isError();
               $errorMsg = $this->CloggyImage->getErrorMsg();

               if ($checkError) {
                   echo $errorMsg;
               }
                
            }
            
            /*
             * save image
             */
            $mediaId = $this->CloggyBlogMedia->setImage($this->_user['id'],array(
                'media_file_type' => 'media/image',
                'media_file_location' => '/uploads/CloggyBlog/images/'.$postId.'/'.$uploadedData['basename']
            ));
            
            /*
             * attach to post
             */
            if ($postId) {
                $this->CloggyBlogMedia->setPostAttachment($postId,$mediaId);
            }
            
            echo 'Upload success';
                       
        }
        
    }

    public function publish($id = null) {

        if (is_null($id)) {
            $this->redirect($this->referer());
            exit();
        }

        $this->CloggyBlogPost->updatePostStat($id, 1);
        $this->redirect($this->referer());
    }

    public function draft($id = null) {

        if (is_null($id)) {
            $this->redirect($this->referer());
            exit();
        }

        $this->CloggyBlogPost->updatePostStat($id, 0);
        $this->redirect($this->referer());
    }

    public function remove($id = null) {

        if (is_null($id)) {
            $this->redirect($this->referer());
            exit();
        }

        $this->CloggyBlogPost->deletePost($id);
        $this->redirect($this->referer());
    }

}