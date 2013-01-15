<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyBlogAjaxController extends CloggyAppController {

    public $uses = array(
        'CloggyBlogPost',
        'CloggyBlogCategory',
        'CloggyBlogTag'
    );    

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->deny('*');
        $this->_user = $this->Auth->user();

        if (!$this->request->is('ajax')) {
            $this->redirect('/');
        }

        $this->autoRender = false;
    }

    public function delete_all_posts() {

        $posts = $this->request->data['post'];
        foreach ($posts as $post) {
            $this->CloggyBlogPost->deletePost($post, false);
        }

        echo json_encode(array('msg' => 'success'));
    }

    public function draft_all_posts() {

        $posts = $this->request->data['post'];
        foreach ($posts as $post) {
            $this->CloggyBlogPost->updatePostStat($post, 0);
        }

        echo json_encode(array('msg' => 'success'));
    }

    public function publish_all_posts() {

        $posts = $this->request->data['post'];
        foreach ($posts as $post) {
            $this->CloggyBlogPost->updatePostStat($post, 1);
        }

        echo json_encode(array('msg' => 'success'));
    }

    public function delete_all_categories() {

        $categories = $this->request->data['category'];
        foreach ($categories as $category) {
            $this->CloggyBlogCategory->deleteCategory($category);
        }

        echo json_encode(array('msg' => 'success'));
    }

    public function delete_all_tags() {

        $tags = $this->request->data['tag'];
        foreach ($tags as $tag) {
            $this->CloggyBlogTag->deleteTag($tag);
        }

        echo json_encode(array('msg' => 'success'));
    }

}