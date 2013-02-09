<?php

App::uses('CloggyAppModel', 'Cloggy.Model');

class CloggyBlogPost extends CloggyAppModel {

    public $name = 'CloggyBlogPost';
    public $useTable = false;
    public $actsAs = array('Cloggy.CloggyCommon');

    /**
     * Check if requested title exists or not
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @param string $title
     * @param int $userId
     * @return boolean
     */
    public function isTitleExists($title, $userId) {

        $typePostId = $this->get('node_type')->generateType('cloggy_blog_post', $userId);
        $checkPostSubject = $this->get('node')->isSubjectExistsByTypeId($typePostId, $title);

        return $checkPostSubject;
    }

    /**
     * Delete post and also with their relation
     * 
     * @uses node CloggyNode
     * @uses node_subject CloggyNodeSubject
     * @uses node_permalink CloggyNodePermalink
     * @uses node_content CloggyNodeContent
     * @uses node_rel CloggyNodeRel
     * @param int $id
     */
    public function deletePost($id) {

        $this->get('node')->delete($id, false);

        $this->get('node_subject')->deleteAll(array(
            'CloggyNodeSubject.node_id' => $id
        ));
        $this->get('node_permalink')->deleteAll(array(
            'CloggyNodePermalink.node_id' => $id
        ));
        $this->get('node_content')->deleteAll(array(
            'CloggyNodeContent.node_id' => $id
        ));
        $this->get('node_rel')->deleteAll(array(
            'CloggyNodeRel.node_object_id' => $id
        ));
    }

    /**
     * Generate new post data
     * 
     * @uses node CloggyNode
     * @uses node_type CloggyNodeType
     * @uses node_subject CloggyNodeSubject
     * @uses node_permalink CloggyNodePermalink
     * @uses node_content CloggyNodeContent
     * @param array $options
     * @return boolean|int
     */
    public function generatePost($options) {

        if (!is_array($options) || empty($options)) {
            return false;
        } else {

            if (is_array($options) && !empty($options)) {
                extract($options);
            }

            $this->get('node')->cacheQueries = false;
            $this->get('node_type')->cacheQueries = false;

            $typePostId = $this->get('node_type')->generateType('cloggy_blog_post', $userId);
            $postNodeId = $this->get('node')->generateEmptyNode($typePostId, $userId);

            $this->get('node')->modifyNode($postNodeId, array(
                'has_subject' => 1,
                'has_content' => 1,
                'node_status' => $stat
            ));

            $this->get('node_subject')->createSubject($postNodeId, $title);
            $this->get('node_permalink')->createPermalink($postNodeId, $title, '-');
            $this->get('node_content')->createContent($postNodeId, $content);

            /*
             * if need to set relation with categories
             */
            if (isset($cats) && !empty($cats)) {

                foreach ($cats as $cat) {
                    $this->get('node_rel')->saveRelation($cat, $postNodeId, 'cloggy_blog_category_post');
                }
            }

            /*
             * if need to set relation with tags
             */
            if (isset($tags) && !empty($tags)) {

                foreach ($tags as $tag) {
                    if (!empty($tag) && !is_null($tag)) {
                        $this->get('node_rel')->saveRelation($tag, $postNodeId, 'cloggy_blog_tag_post');
                    }
                }
            }

            return $postNodeId;
        }
    }

    /**
     * Update post data
     * @uses node_subject CloggyNodesubject
     * @uses node_content CloggyNodeContent
     * @param int $id
     * @param array $data
     */
    public function updatePost($id, $data) {

        if (isset($data['title'])) {

            $subject = $this->get('node_subject')->find('first', array(
                'contain' => false,
                'conditions' => array('CloggyNodeSubject.node_id' => $id),
                'fields' => array('CloggyNodeSubject.id')
                    ));

            if (!empty($subject)) {

                $this->get('node_subject')->id = $subject['CloggyNodeSubject']['id'];
                $this->get('node_subject')->save(array(
                    'CloggyNodeSubject' => array(
                        'subject' => $data['title']
                    )
                ));
            }
        }

        if (isset($data['content'])) {

            $subject = $this->get('node_content')->find('first', array(
                'contain' => false,
                'conditions' => array('CloggyNodeContent.node_id' => $id),
                'fields' => array('CloggyNodeContent.id')
                    ));

            if (!empty($subject)) {

                $this->get('node_content')->id = $subject['CloggyNodeContent']['id'];
                $this->get('node_content')->save(array(
                    'CloggyNodeContent' => array(
                        'content' => $data['content']
                    )
                ));
            }
        }
    }

    /**
     * Setup post status: publish(1),draft(0)
     * 
     * @uses node CloggyNode
     * @param int $id
     * @param int $stat
     */
    public function updatePostStat($id, $stat) {

        $this->get('node')->id = $id;
        $this->get('node')->save(array(
            'CloggyNode' => array(
                'node_status' => $stat
            )
        ));
    }

    /**
     * Update post taxonomies
     * 
     * @uses node_type CloggyNodeType
     * @uses node_rel CloggyNodeRel
     * @param array $options
     * @return boolean
     */
    public function updatePostTaxonomies($options) {

        if (!is_array($options) || empty($options)) {
            return false;
        } else {
            extract($options);
        }

        /*
         * set taxo name
         */
        switch ($taxo) {

            case 'cloggy_blog_tags':
                $rel = 'cloggy_blog_tag_post';
                break;

            default:
                $rel = 'cloggy_blog_category_post';
                break;
        }

        $typeId = $this->get('node_type')->getTypeIdByName($taxo);

        //reset
        $this->get('node_rel')->deleteAllRelations($id, $rel);

        if (is_array($data) && !empty($data)) {

            foreach ($data as $key) {

                $checkRel = $this->get('node_rel')->isRelationExists($key, $id, $rel);

                /*
                 * create new relation
                 */
                if (!$checkRel) {
                    $this->get('node_rel')->saveRelation($key, $id, $rel);
                }
            }
        }
    }

    /**
     * 
     * Get post data
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @param int $limit
     * @param string $order
     * @return array
     */
    public function getPosts($limit, $order) {

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_post');
        $posts = $this->get('node')->find('all', array(
            'contain' => array(
                'CloggySubject' => array(
                    'fields' => array('CloggySubject.id', 'CloggySubject.subject')
                ),
                'CloggyUser' => array(
                    'fields' => array('CloggyUser.id', 'CloggyUser.user_name')
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

        return $posts;
    }

    /**
     * Get single post detail
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @param int $id
     * @return array
     */
    public function getSinglePostById($id) {

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_post');
        $detail = $this->get('node')->find('first', array(
            'contain' => array(
                'CloggySubject' => array(
                    'fields' => array('CloggySubject.id', 'CloggySubject.subject')
                ),
                'CloggyContent' => array(
                    'fields' => array('CloggyContent.id', 'CloggyContent.content')
                )
            ),
            'conditions' => array(
                'CloggyNode.id' => $id,
                'CloggyNode.node_type_id' => $typeId
            ),
            'fields' => array('CloggyNode.id')
                ));

        return $detail;
    }

    /**
     * 
     * Get taxonomies from single post
     * @uses node_rel CloggyNodeRel
     * @uses node CloggyNode
     * @param int $id
     * @param string $taxo
     * @param string $rel
     * @return boolean
     */
    public function getSinglePostTaxonomies($id, $taxo = 'cloggy_blog_categories', $rel = 'cloggy_blog_category_post') {

        $categoriesNodeTypeId = $this->get('node_type')->find('first', array(
            'contain' => false,
            'conditions' => array('CloggyNodeType.node_type_name' => $taxo)
                ));

        $data = $this->get('node_rel')->find('all', array(
            'contain' => array(
                'CloggyNode' => array(
                    'conditions' => array('CloggyNode.node_type_id' => $categoriesNodeTypeId['CloggyNodeType']['id']),
                    'fields' => array('CloggyNode.id')
                )
            ),
            'conditions' => array(
                'CloggyNodeRel.node_object_id' => $id,
                'CloggyNodeRel.relation_name' => $rel
            ),
            'fields' => array('CloggyNodeRel.node_id', 'CloggyNodeRel.node_object_id', 'CloggyNodeRel.relation_name')
                ));

        if (!empty($data)) {

            $taxIds = array();
            foreach ($data as $key) {
                $taxIds[] = $key['CloggyNode']['id'];
            }

            $taxos = $this->get('node')->find('all', array(
                'contain' => array(
                    'CloggySubject' => array(
                        'fields' => array('CloggySubject.subject')
                    )
                ),
                'conditions' => array('CloggyNode.id' => $taxIds),
                'fields' => array('CloggyNode.id')
                    ));

            $return = array();
            foreach ($taxos as $taxoKey) {
                $return[$taxoKey['CloggyNode']['id']] = $taxoKey['CloggySubject']['subject'];
            }

            return $return;
        }

        return false;
    }

    /**
     * 
     * Get posts for pagination
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
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

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_post');

        return $this->get('node')->find('all', array(
                    'contain' => array(
                        'CloggyType' => array(
                            'fields' => array('CloggyType.node_type_name')
                        ),
                        'CloggySubject' => array(
                            'fields' => array('CloggySubject.subject')
                        ),
                        'CloggyUser' => array(
                            'fields' => array(
                                'CloggyUser.id',
                                'CloggyUser.user_name'
                            )
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
     * Get total post data
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @param array $conditions
     * @param float|int $recursive
     * @param array $extra
     * @return int
     */
    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_post');
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