<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyBlogTagsController extends CloggyAppController {

    public $uses = array(
        'CloggyBlogTag',
        'Cloggy.CloggyValidation'
    );

    public function beforeFilter() {
        parent::beforeFilter();        
    }

    public function index() {

        $this->paginate = array(
            'CloggyBlogCategory' => array(
                'limit' => 10,
                'contain' => false,
                'fields' => array(
                    'CloggyNode.id',
                    'CloggyNode.node_created',
                    'CloggyNode.node_status'
                )
            )
        );

        $tags = $this->paginate('CloggyBlogTag');
        $this->set(compact('tags'));
        $this->set('title_for_layout', 'Cloggy - CloggyBlog Tags');
    }

    public function add() {

        if ($this->request->is('post')) {

            //sanitize data
            $this->request->data = Sanitize::clean($this->request->data);

            $dataToValidate = $this->request->data['CloggyBlogTags'];
            
            /*
             * check if requested tag has exists or not
             */
            $checkTagExists = $this->CloggyBlogTag->isTagExists(
                    $this->request->data['CloggyBlogTags']['tag_name'], $this->_user['id']);

            /*
             * setup validation
             */
            $this->CloggyValidation->set($dataToValidate);
            $this->CloggyValidation->validate = array(
                'tag_name' => array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => 'Tag field required'
                    ),
                    'exists' => array(
                        'rule' => array('isValueEqual', $checkTagExists, false),
                        'message' => 'Category has been exists'
                    )
                )
            );

            /*
             * validates
             */
            if ($this->CloggyValidation->validates()) {

                $tag = $this->request->data['CloggyBlogTags']['tag_name'];

                $saved = $this->CloggyBlogTag->proceedTags(array($tag), $this->_user['id']);
                $this->set('success', 'Tag has been saved.');
            } else {
                $this->set('errors', $this->CloggyValidation->validationErrors);
            }
        }
        $this->set('title_for_layout', 'Cloggy - CloggyBlog Add New Tag');
    }

    public function edit($id = null) {

        if (is_null($id)) {
            $this->redirect($this->referer());
            exit();
        }

        //get detail tag
        $tag = $this->CloggyBlogTag->getDetailTag($id);

        /*
         * form submitted
         */
        if ($this->request->is('post')) {

            //sanitize data
            $this->request->data = Sanitize::clean($this->request->data);
            $dataToValidate = array();

            $tagName = $this->request->data['CloggyBlogTags']['tag_name'];

            /*
             * if subject need to update
             */
            if ($tagName != $tag['CloggySubject']['subject']) {
                $dataToValidate['tag_name'] = $tagName;
            }

            if (!empty($dataToValidate)) {

                /*
                 * check if requested tag has exists or not
                 */
                $checkTagExists = $this->CloggyBlogTag->isTagExists(
                        $this->request->data['CloggyBlogTags']['tag_name'], $this->_user['id']);

                /*
                 * setup validation
                 */
                $this->CloggyValidation->set($dataToValidate);
                $this->CloggyValidation->validate = array(
                    'tag_name' => array(
                        'empty' => array(
                            'rule' => 'notEmpty',
                            'required' => true,
                            'allowEmpty' => false,
                            'message' => 'Tag field required'
                        ),
                        'exists' => array(
                            'rule' => array('isValueEqual', $checkTagExists, false),
                            'message' => 'Tag has been exists'
                        )
                    )
                );

                /*
                 * validates
                 */
                if ($this->CloggyValidation->validates()) {
                    $this->CloggyBlogTag->updateTag($id, $tagName);
                } else {
                    $this->set('errors', $this->CloggyValidation->validationErrors);
                }
            }

            $tag = $this->CloggyBlogTag->getDetailTag($id);
            $this->set('success', 'Your tag has been updated.');
        }

        $this->set(compact('tag', 'id'));
        $this->set('title_for_layout', 'Cloggy - CloggyBlog Edit Tag');
    }

    public function remove($id = null) {

        if (is_null($id)) {
            $this->redirect($this->referer());
            exit();
        }

        $this->CloggyBlogTag->deleteTag($id);
        $this->redirect($this->referer());
    }

}