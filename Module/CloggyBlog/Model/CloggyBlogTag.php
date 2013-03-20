<?php

App::uses('CloggyAppModel', 'Cloggy.Model');

class CloggyBlogTag extends CloggyAppModel {

    public $name = 'CloggyBlogTag';
    public $useTable = false;
    public $actsAs = array('Cloggy.CloggyCommon');

    /**
     * Check if requested tag exists or not
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @param string $tag
     * @param int $userId
     * @return boolean
     */
    public function isTagExists($tag, $userId) {

        $typeTagId = $this->get('node_type')->generateType('cloggy_blog_tags', $userId);
        $checkTagSubject = $this->get('node')->isSubjectExistsByTypeId($typeTagId, $tag);

        return $checkTagSubject;
    }

    /**
     * Get limited tags
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @param int $limit
     * @param string $order
     * @return array
     */
    public function getTags($limit, $order) {

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_tags');
        $tags = $this->get('node')->find('all', array(
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

        return $tags;
    }

    /**
     * Get all tags
     * 
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @return boolean|array
     */
    public function getAllTags() {

        $tagsNodeTypeId = $this->get('node_type')->find('first', array(
            'contain' => false,
            'conditions' => array('CloggyNodeType.node_type_name' => 'cloggy_blog_tags')
                ));

        if (!empty($tagsNodeTypeId)) {

            $tags = $this->get('node')->find('all', array(
                'contain' => array(
                    'CloggySubject' => array(
                        'fields' => array('CloggySubject.subject')
                    )
                ),
                'conditions' => array('CloggyNode.node_type_id' => $tagsNodeTypeId['CloggyNodeType']['id']),
                'fields' => array('CloggyNode.id')
                    ));

            return $tags;
        }

        return false;
    }
    
    public function getTagIdByName($tag) {
        
        $data = $this->get('node_subject')->find('first',array(
            'contain' => false,
            'conditions' => array('CloggyNodeSubject.subject' => $tag),
            'fields' => array('CloggyNodeSubject.node_id')
        ));
        
        return isset($data['CloggyNodeSubject']['node_id']) ? $data['CloggyNodeSubject']['node_id'] : false;
        
    }

    /**
     * Get detail tag
     * @uses node CloggyNode
     * @param int $id
     * @return array
     */
    public function getDetailTag($id) {

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

        return $category;
    }

    /**
     * Update tag
     * @uses node_subject CloggyNodeSubject
     * @param int $id
     * @param string $tagName
     */
    public function updateTag($id, $tagName) {

        $this->get('node_subject')->updateAll(
                array('CloggyNodeSubject.subject' => '"' . Sanitize::escape($tagName) . '"'), array('CloggyNodeSubject.node_id' => $id)
        );
    }

    /**
     * Generate tags
     * 
     * @uses node CloggyNode
     * @uses node_type CloggyNodeType
     * @param array $exp
     * @param int $userId
     * @return array
     */
    public function proceedTags($exp, $userId) {

        $tags = array();

        $this->get('node')->cacheQueries = false;
        $this->get('node_type')->cacheQueries = false;
        
        foreach ($exp as $tag) {

            $typeId = $this->get('node_type')->generateType('cloggy_blog_tags', $userId);
            $checkCatSubject = $this->isTagExists($tag, $userId);

            /*
             * if not exists then create it
             * if exists then get id
             */
            if (!$checkCatSubject) {

                $tagNodeId = $this->get('node')->generateEmptyNode($typeId, $userId);
                $this->get('node')->modifyNode($tagNodeId, array(
                    'has_subject' => 1
                ));

                $this->get('node_subject')->createSubject($tagNodeId, $tag);
                $this->get('node_permalink')->createPermalink($tagNodeId, $tag, '-');
            } else {
                $tagNodeId = $this->get('node')->getNodeIdBySubjectAndTypeId($typeId, $tag);
            }

            $tags[] = $tagNodeId;            
        }
        
        return $tags;
    }

    /**
     * Delete tags with their relation
     * 
     * @uses node CloggyNode
     * @uses node_subject CloggyNodeSubject
     * @uses node_permalink CloggyNodePermalink
     * @uses node_rels CloggyNodeRel
     * @param int $id
     */
    public function deleteTag($id) {

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
     * Get data for pagination
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

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_tags');

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
     * Get total data for pagination
     * @uses node_type CloggyNodeType
     * @uses node CloggyNode
     * @param array $conditions
     * @param float|int $recursive
     * @param array $extra
     * @return array
     */
    public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {

        $typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_tags');
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