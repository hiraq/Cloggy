<?php

App::uses('ClogAppModel','Clog.Model');

class ClogBlogTag extends ClogAppModel {
	
	public $name = 'ClogBlogTag';
	public $useTable = false;
	public $actsAs = array('Clog.ClogCommon');	
	
	public function isTagExists($tag,$userId) {
	
		$typeTagId = $this->get('node_type')->generateType('clog_blog_tags',$userId);
		$checkTagSubject = $this->get('node')->isSubjectExistsByTypeId($typeTagId,$tag);
	
		return $checkTagSubject;
	
	}
	
	public function getTags($limit,$order) {
	
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_tags');
		$tags = $this->get('node')->find('all',array(
			'contain' => array(
				'ClogSubject' => array(
					'fields' => array('ClogSubject.id','ClogSubject.subject')
				),
				'ClogRelNode' => array(
					'fields' => array('ClogRelNode.id')
				)
			),
			'conditions' => array(
				'ClogNode.node_type_id' => $typeId
			),
			'fields' => array(
				'ClogNode.id',
				'ClogNode.node_status',
				'ClogNode.node_created'
			),
			'order' => $order,
			'limit' => $limit
		));
	
		return $tags;
	
	}
	
	public function getAllTags() {
		
		$tagsNodeTypeId = $this->get('node_type')->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodeType.node_type_name' => 'clog_blog_tags')
		));
		
		if(!empty($tagsNodeTypeId)) {
			
			$tags = $this->get('node')->find('all',array(
				'contain' => array(
					'ClogSubject' => array(
						'fields' => array('ClogSubject.subject')
					)
				),
				'conditions' => array('ClogNode.node_type_id' => $tagsNodeTypeId['ClogNodeType']['id']),
				'fields' => array('ClogNode.id')
			));
			
			return $tags;
			
		}
		
		return false;
		
	}
	
	public function getDetailTag($id) {
	
		/*
		 * get detail category
		*/
		$category = $this->get('node')->find('first',array(
			'contain' => array(
				'ClogSubject' => array(
					'fields' => array('ClogSubject.subject')
				),
				'ClogParentNode'
			),
			'conditions' => array('ClogNode.id' => $id),
			'fields' => array('ClogNode.id')
		));			
	
		return $category;
	
	}
	
	public function updateTag($id,$tagName) {
	
		$this->get('node_subject')->updateAll(
				array('ClogNodeSubject.subject' => '"'.Sanitize::escape($tagName).'"'),
				array('ClogNodeSubject.node_id' => $id)
		);
	
	}
	
	public function proceedTags($exp,$userId) {
		
		$tags = array();
		foreach($exp as $tag) {
				
			$typeId = $this->get('node_type')->generateType('clog_blog_tags',$userId);
			$checkCatSubject = $this->get('node')->isSubjectExistsByTypeId($typeId,$tag);
				
			if(!$checkCatSubject) {
					
				$tagNodeId = $this->get('node')->generateEmptyNode($typeId,$userId);
				$this->get('node')->modifyNode($tagNodeId,array(
					'has_subject' => 1
				));
					
				$this->get('node_subject')->createSubject($tagNodeId,$tag);
				$this->get('node_permalink')->createPermalink($tagNodeId,$tag,'-');
					
			}else{
				$tagNodeId = $this->get('node')->getNodeIdBySubjectAndTypeId($typeId,$tag);
			}
				
			$tags[] = $tagNodeId;
		
		}
		
		return $tags;
		
	}
	
	public function deleteTag($id) {
	
		$this->get('node')->delete($id,false);
	
		$this->get('node_subject')->deleteAll(array(
			'ClogNodeSubject.node_id' => $id
		));
		$this->get('node_permalink')->deleteAll(array(
			'ClogNodePermalink.node_id' => $id
		));
		$this->get('node_rel')->deleteAll(array(
			'ClogNodeRel.node_id' => $id
		));
	
	}
	
	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
	
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_tags');
	
		return $this->get('node')->find('all',array(
			'contain' => array(
				'ClogType' => array(
					'fields' => array('ClogType.node_type_name')
				),
				'ClogSubject' => array(
					'fields' => array('ClogSubject.subject')
				),
				'ClogRelNode' => array(
					'fields' => array('ClogRelNode.id')
				)
			),
			'conditions' => array(
				'ClogType.id' => $typeId
			),
			'order' => array(
				'ClogNode.node_created' => 'desc'
			),
			'limit' => $limit,
			'page' => $page,
			'fields' => $fields
		));
	
	}
	
	public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
	
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_tags');
		return $this->get('node')->find('count',array(
			'contain' => array(
				'ClogType' => array(
					'fields' => array('ClogType.node_type_name')
				),
				'ClogSubject' => array(
					'fields' => array('ClogSubject.subject')
				)
			),
			'conditions' => array(
				'ClogType.id' => $typeId
			)
		));
	
	}
	
}