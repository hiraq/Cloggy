<?php

App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('Sanitize', 'Utility');

class CloggyBlogCategory extends CloggyAppModel {

    public $name = 'CloggyBlogCategory';
    public $useTable = false;
    public $actsAs = array('Cloggy.CloggyCommon');

    /**
     * Check if category exists or not
     * 
     * @uses node CloggyNode     
     * @param string $category
     * @param user $userId
     * @return boolean
     */
    public function isCategoryExists($category, $userId) {

        $typeCategoryId = $this->get('node_type')->generateType('cloggy_blog_categories', $userId);
        $checkCategorySubject = $this->get('node')->isSubjectExistsByTypeId($typeCategoryId, $category);

        return $checkCategorySubject;
    }

    /**
     * Get limited categories
     * 
     * @uses node CloggyNode
     * @uses node_type CloggyNodeType
     * @param int $limit
     * @param string $order
     * @return array
     */
    public function getCategories($limit, $order) {

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_categories');
        $categories = $this->get('node')->find('all', array(
            'contain' => array(
                'CloggySubject' => array(
                    'fields' => array('CloggySubject.id', 'CloggySubject.subject')
                ),
                'CloggyRelNode' => array(
                    'fields' => array('CloggyRelNode.id')
                )
            ),
            'conditions' => array(
                'CloggyNode.node_type_id' => $typeId
            ),
            'fields' => array(
                'CloggyNode.id',
                'CloggyNode.node_status',
                'CloggyNode.node_created'
            ),
            'order' => $order,
            'limit' => $limit
                ));

        return $categories;
    }

    /**
     * Get all categories
     * @uses node CloggyNode
     * @uses node_type CloggyNodeType
     * @param array $except
     * @return boolean
     */
    public function getAllCategories($except = null) {

        $categoriesNodeTypeId = $this->get('node_type')->find('first', array(
            'contain' => false,
            'conditions' => array('CloggyNodeType.node_type_name' => 'cloggy_blog_categories')
                ));

        if (!empty($categoriesNodeTypeId)) {

            $conditions = array(
                'CloggyNode.node_type_id' => $categoriesNodeTypeId['CloggyNodeType']['id']
            );

            /*
             * if except has been set
             */
            if (!is_null($except)) {

                if (!is_array($except)) {

                    $conditions = array_merge($conditions, array(
                        'CloggyNode.id !=' => $except
                            ));
                } else {

                    $conditions = array_merge($conditions, array(
                        'NOT' => array('CloggyNode.id' => $except)
                            ));
                }
            }

            /*
             * get categories
             */
            $categories = $this->get('node')->find('all', array(
                'contain' => array(
                    'CloggySubject' => array(
                        'fields' => array('CloggySubject.subject')
                    )
                ),
                'conditions' => $conditions,
                'fields' => array('CloggyNode.id')
                    ));

            return $categories;
        }

        return false;
    }

    /**
     * Get detail category
     * @uses node CloggyNode
     * @param int $id
     * @return array
     */
    public function getDetailCategory($id) {

        /*
         * get detail category
         */
        $category = $this->get('node')->find('first', array(
            'contain' => array(
                'CloggySubject' => array(
                    'fields' => array('CloggySubject.subject')
                ),
                'CloggyParentNode'
            ),
            'conditions' => array('CloggyNode.id' => $id),
            'fields' => array('CloggyNode.id')
                ));

        if (!empty($category)) {

            /*
             * generate node parent
             */
            $parent = $this->get('node')->find('first', array(
                'contain' => array(
                    'CloggySubject' => array(
                        'fields' => array('CloggySubject.subject')
                    )
                ),
                'conditions' => array('CloggyNode.id' => $category['CloggyParentNode']['id']),
                'fields' => array('CloggyNode.id')
                    ));

            //reset
            unset($category['CloggyParentNode']);
            $category = array_merge($category, array('CloggyParentNode' => $parent));
        }

        return $category;
    }

    /**
     * Get parent category
     * 
     * @uses node CloggyNode
     * @param int $id
     * @return array
     */
    public function getParentCategory($id) {

        $data = $this->get('node')->find('first', array(
            'contain' => array(
                'CloggyParentNode'
            ),
            'conditions' => array('CloggyNode.id' => $id)
                ));

        $parent = $this->get('node')->find('first', array(
            'contain' => array(
                'CloggySubject' => array(
                    'fields' => array('CloggySubject.subject')
                )
            ),
            'conditions' => array('CloggyNode.id' => $data['CloggyParentNode']['id']),
            'fields' => array('CloggyNode.id')
                ));

        return $parent;
    }
    
    /**
     * Get category id
     * 
     * @param string $categoryName
     * @return int
     */
    public function getCategoryIdByName($categoryName) {
        
        $data = $this->get('node_subject')->find('first',array(
            'contain' => false,
            'conditions' => array('CloggyNodeSubject.subject' => $categoryName),
            'fields' => array('CloggyNodeSubject.node_id')
        ));
        
        return isset($data['CloggyNodeSubject']['node_id']) ? $data['CloggyNodeSubject']['node_id'] : false;
        
    }

    /**
     * Update category data
     * @uses node_subject CloggyNodeSubject
     * @param int $id
     * @param string $catName
     */
    public function updateCategory($id, $catName) {

        $this->get('node_subject')->updateAll(
                array('CloggyNodeSubject.subject' => '"' . Sanitize::escape($catName) . '"'), array('CloggyNodeSubject.node_id' => $id)
        );
    }

    /**
     * Update category parent
     * @uses node CloggyNode
     * @param int $id
     * @param int $parentId
     */
    public function updateCategoryParent($id, $parentId) {

        $this->get('node')->updateAll(
                array('CloggyNode.node_parent' => '"' . Sanitize::escape($parentId) . '"'), array('CloggyNode.id' => $id)
        );
    }

    /**
     * Delete category and also their relation
     * 
     * @uses node CloggyNode
     * @uses node_subject CloggyNodeSubject
     * @uses node_permalink CloggyNodePermalink
     * @uses node_rel CloggyNodeRel
     * @param int $id
     */
    public function deleteCategory($id) {

        $this->get('node')->delete($id, false);

        $this->get('node_subject')->deleteAll(array(
            'CloggyNodeSubject.node_id' => $id
        ));
        $this->get('node_permalink')->deleteAll(array(
            'CloggyNodePermalink.node_id' => $id
        ));
        $this->get('node_rel')->deleteAll(array(
            'CloggyNodeRel.node_id' => $id
        ));
    }

    /**
     * Save categories
     * 
     * @uses node CloggyNode
     * @uses node_type CloggyNodeType
     * @uses node_subject CloggyNodeSubject
     * @uses node_permalink CloggyNodePermalink
     * @param int $categories
     * @param int $userId
     * @return array
     */
    public function proceedCategories($categories, $userId) {

        $cats = array();

        /*
         * disable cacheQueries
         */
        $this->get('node')->cacheQueries = false;
        $this->get('node_type')->cacheQueries = false;

        foreach ($categories as $cat) {

            $typeId = $this->get('node_type')->generateType('cloggy_blog_categories', $userId);

            $checkCatSubject = $this->get('node')->isSubjectExistsByTypeId($typeId, $cat);
            if (!$checkCatSubject) {

                $catNodeId = $this->get('node')->generateEmptyNode($typeId, $userId);
                $this->get('node')->modifyNode($catNodeId, array(
                    'has_subject' => 1
                ));

                $this->get('node_subject')->createSubject($catNodeId, $cat);
                $this->get('node_permalink')->createPermalink($catNodeId, $cat, '-');
            } else {
                $catNodeId = $this->get('node')->getNodeIdBySubjectAndTypeId($typeId, $cat);
            }

            $cats[] = $catNodeId;
        }

        return $cats;
    }

    /**
     * Set category parent
     * @uses node CloggyNode     
     * @param int $parentId
     * @param int $catId
     */
    public function setCategoryParent($parentId, $catId) {

        $this->get('node')->id = $catId;
        $this->get('node')->save(array(
            'CloggyNode' => array(
                'node_parent' => $parentId
            )
        ));
    }

    /**
     * Paginate category
     * 
     * @uses node CloggyNode
     * @uses node_type CloggyNodeType
     * @param array $conditions
     * @param array $fields
     * @param string $order
     * @param int $limit
     * @param int $page
     * @param float|int $recursive
     * @param array $extra
     * @return array
     */
    public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_categories');

        return $this->get('node')->find('all', array(
                    'contain' => array(
                        'CloggyType' => array(
                            'fields' => array('CloggyType.node_type_name')
                        ),
                        'CloggySubject' => array(
                            'fields' => array('CloggySubject.subject')
                        ),
                        'CloggyRelNode' => array(
                            'fields' => array('CloggyRelNode.id')
                        )
                    ),
                    'conditions' => array(
                        'CloggyType.id' => $typeId
                    ),
                    'order' => array(
                        'CloggyNode.node_created' => 'desc'
                    ),
                    'limit' => $limit,
                    'page' => $page,
                    'fields' => $fields
                ));
    }

    /**
     * Get total paginate data
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @param array $conditions
     * @param float|int $recursive
     * @param array $extra
     * @return array
     */
    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_categories');
        return $this->get('node')->find('count', array(
                    'contain' => array(
                        'CloggyType' => array(
                            'fields' => array('CloggyType.node_type_name')
                        ),
                        'CloggySubject' => array(
                            'fields' => array('CloggySubject.subject')
                        )
                    ),
                    'conditions' => array(
                        'CloggyType.id' => $typeId
                    )
                ));
    }

}