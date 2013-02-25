<?php

App::uses('CloggyAppModel', 'Cloggy.Model');

class CloggyBlogMedia extends CloggyAppModel {
    
    public $name = 'CloggyBlogMedia';
    public $useTable = false;
    public $actsAs = array('Cloggy.CloggyCommon');
    
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
        
        $typeId = $this->__getTypeId($userId, 'cloggy_blog_media_image');
        
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
     * Attach media to some post
     * 
     * @uses node_rel NodeRel
     * @param int $postId
     * @param int $mediaId
     */
    public function setPostAttachment($postId,$mediaId) {
        
        //attach media to post
        $this->get('node_rel')->saveRelation($mediaId,$postId,'cloggy_blog_post_image');
        
    }
    
    private function __getTypeId($userId,$type) {
        $typePostId = $this->get('node_type')->generateType($type, $userId);
        return $typePostId;
    }
    
}