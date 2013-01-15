<?php

App::uses('CloggyAppController', 'Cloggy.Controller');
App::uses('Sanitize', 'Utility');

class CloggyBlogCategoriesController extends CloggyAppController {

    public $uses = array(
        'CloggyBlogCategory',
        'Cloggy.CloggyValidation'
    );

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->deny('*');

        $this->set('moduleKeyMenus', 'cloggy_blog');
        $this->_user = $this->Auth->user();
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

        $categories = $this->paginate('CloggyBlogCategory');
        $this->set(compact('categories'));
        $this->set('title_for_layout', 'Cloggy - CloggyBlog Categories');
    }

    public function add() {

        if ($this->request->is('post')) {

            $this->request->data = Sanitize::clean($this->request->data);

            $dataToValidate = $this->request->data['CloggyBlogCategories'];
            $checkCategoryExists = $this->CloggyBlogCategory->isCategoryExists(
                    $this->request->data['CloggyBlogCategories']['category_name'], $this->_user['id']);

            $this->CloggyValidation->set($dataToValidate);
            $this->CloggyValidation->validate = array(
                'category_name' => array(
                    'empty' => array(
                        'rule' => 'notEmpty',
                        'required' => true,
                        'allowEmpty' => false,
                        'message' => 'Category field required'
                    ),
                    'exists' => array(
                        'rule' => array('isValueEqual', $checkCategoryExists, false),
                        'message' => 'Category has been exists'
                    )
                )
            );

            if ($this->CloggyValidation->validates()) {

                $category = $this->request->data['CloggyBlogCategories']['category_name'];

                $saved = $this->CloggyBlogCategory->proceedCategories(array($category), $this->_user['id']);
                $savedId = $saved[0];

                /*
                 * set category parent
                 */
                if (isset($this->request->data['CloggyBlogCategories']['category_parent'])) {

                    if ($this->request->data['CloggyBlogCategories']['category_parent'] > 0) {

                        $this->CloggyBlogCategory->setCategoryParent(
                                $this->request->data['CloggyBlogCategories']['category_parent'], $savedId);
                    }
                }

                $this->set('success', 'Category has been saved.');
            } else {
                $this->set('errors', $this->CloggyValidation->validationErrors);
            }
        }

        $categories = $this->CloggyBlogCategory->getAllCategories();

        $this->set(compact('categories'));
        $this->set('title_for_layout', 'Cloggy - CloggyBlog Add New Category');
    }

    public function edit($id = null) {

        if (is_null($id)) {
            $this->redirect($this->referer());
            exit();
        }

        $category = $this->CloggyBlogCategory->getDetailCategory($id);

        if ($this->request->is('post')) {

            $this->request->data = Sanitize::clean($this->request->data);
            $dataToValidate = array();

            $categoryName = $this->request->data['CloggyBlogCategories']['category_name'];

            if (isset($this->request->data['CloggyBlogCategories']['category_parent'])) {
                $categoryParent = $this->request->data['CloggyBlogCategories']['category_parent'];
            } else {
                $categoryParent = 0;
            }

            $parent = $this->CloggyBlogCategory->getParentCategory($id);

            if ($categoryName != $category['CloggySubject']['subject']) {
                $dataToValidate['category_name'] = $categoryName;
            }

            if (!empty($dataToValidate)) {

                $checkCategoryExists = $this->CloggyBlogCategory->isCategoryExists(
                        $this->request->data['CloggyBlogCategories']['category_name'], $this->_user['id']);

                $this->CloggyValidation->set($dataToValidate);
                $this->CloggyValidation->validate = array(
                    'category_name' => array(
                        'empty' => array(
                            'rule' => 'notEmpty',
                            'required' => true,
                            'allowEmpty' => false,
                            'message' => 'Category field required'
                        ),
                        'exists' => array(
                            'rule' => array('isValueEqual', $checkCategoryExists, false),
                            'message' => 'Category has been exists'
                        )
                    )
                );

                if ($this->CloggyValidation->validates()) {

                    $this->CloggyBlogCategory->updateCategory($id, $categoryName);
                    if ($parent['CloggyNode']['id'] != $categoryParent && $categoryParent > 0) {
                        $this->CloggyBlogCategory->updateCategoryParent($id, $categoryParent);
                    }
                } else {
                    $this->set('errors', $this->CloggyValidation->validationErrors);
                }
            }

            if ($parent['CloggyNode']['id'] != $categoryParent && $categoryParent > 0) {
                $this->CloggyBlogCategory->updateCategoryParent($id, $categoryParent);
            }

            $this->set('success', 'Your category has been updated.');
        }

        $categories = $this->CloggyBlogCategory->getAllCategories($id);

        $this->set(compact('categories', 'category', 'id'));
        $this->set('title_for_layout', 'Cloggy - CloggyBlog Edit Category');
    }

    public function remove($id = null) {

        if (is_null($id)) {
            $this->redirect($this->referer());
            exit();
        }

        $this->CloggyBlogCategory->deleteCategory($id);
        $this->redirect($this->referer());
    }

}