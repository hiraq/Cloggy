<?php

App::uses('CloggyAppModel', 'Cloggy.Model');

class CloggyBlogMedia extends CloggyAppModel {
    
    public $name = 'CloggyBlogMedia';
    public $useTable = false;
    public $actsAs = array('Cloggy.CloggyCommon');
    
    /**
     * Check if post has image or not
     * 
     * @uses node_rel NodeRel
     * @param int $postId
     * @return boolean
     */
    public function isPostHasImage($postId) {
        
        $check = $this->get('node_rel')->find('count',array(
            'contain' => false,
            'conditions' => array(
                'CloggyNodeRel.node_object_id' => $postId,
                'CloggyNodeRel.relation_name' => 'cloggy_blog_post_media_image'
            )
        ));
        
        return $check < 1 ? false : true;
        
    }
    
    /**
     * Update post image
     * 
     * @uses node_rel NodeRel
     * @param int $imageId
     * @param int $postId
     */
    public function updatePostImage($imageId,$postId) {
        
        $this->get('node_rel')->updateAll(array(
            'CloggyNodeRel.node_id' => '"'.$imageId.'"'
        ),array(
            'CloggyNodeRel.node_object_id' => $postId,
            'CloggyNodeRel.relation_name' => 'cloggy_blog_post_media_image'
        ));
        
    }
    
    /**
     * Save image
     * 
     * @uses node_type NodeType
     * @uses node Node
     * @uses node_media NodeMedia
     * @param int $userId
     * @param array $data
     * @return int
     */
    public function setImage($userId,$data) {
        
        $typeId = $this->__getTypeId($userId, 'cloggy_blog_post_image');
        
        //create node
        $nodeId = $this->get('node')->generateEmptyNode($typeId,$userId);
        
        //modify node
        $this->get('node')->modifyNode($nodeId,array(
            'has_media' => 1
        ));                            
        
        $this->get('node_media')->saveMedia($nodeId,$data['media_file_type'],$data['media_file_location']);
        return $nodeId;
        
    }
    
    /**
     * Get post image
     * 
     * @uses node_rel NodeRel
     * @uses node_media NodeMedia
     * @param int $postId
     * @return null|string
     */
    public function getImage($postId) {
        
        $data = $this->get('node_rel')->find('first',array(
            'contain' => false,
            'conditions' => array(
                'CloggyNodeRel.node_object_id' => $postId,
                'CloggyNodeRel.relation_name' => 'cloggy_blog_post_media_image'
            ),
            'fields' => array('CloggyNodeRel.node_id')
        ));
        
        /*
         * if not empty
         */
        if (!empty($data)) {
            
            $imageId = $data['CloggyNodeRel']['node_id'];
            $image = $this->get('node_media')->find('first',array(
                'contain' => false,
                'conditions' => array('CloggyNodeMedia.node_id' => $imageId)
            ));
            
            return $image['CloggyNodeMedia']['media_file_location'];
            
        }        
        
        return null;
        
    }
    
    /**
     * Attach media to some post
     * 
     * @uses node_rel NodeRel
     * @param int $postId
     * @param int $mediaId
     */
    public function setPostAttachment($postId,$mediaId) {
        
        //attach media to post
        $this->get('node_rel')->saveRelation($mediaId,$postId,'cloggy_blog_post_media_image');
        
    }
    
    /**
     * Generate and get node type post id
     * 
     * @uses node_type NodeType
     * @param int $userId
     * @param string $type
     * @return int
     */
    private function __getTypeId($userId,$type) {
        $typePostId = $this->get('node_type')->generateType($type, $userId);
        return $typePostId;
    }
    
}