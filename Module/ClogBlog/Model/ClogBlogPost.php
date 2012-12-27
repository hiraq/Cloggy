<?php

App::uses('ClogAppModel','Clog.Model');

class ClogBlogPost extends ClogAppModel {
	
	public $name = 'ClogBlogPost';
	public $useTable = false;
	public $actsAs = array('Clog.ClogCommon');				
	
	public function isTitleExists($title,$userId) {
		
		$typePostId = $this->get('node_type')->generateType('clog_blog_post',$userId);
		$checkPostSubject = $this->get('node')->isSubjectExistsByTypeId($typePostId,$title);
		
		return $checkPostSubject;
		
	}
	
	public function deletePost($id) {
		
		$this->get('node')->delete($id,false);
		
		$this->get('node_subject')->deleteAll(array(
			'ClogNodeSubject.node_id' => $id
		));
		$this->get('node_permalink')->deleteAll(array(
			'ClogNodePermalink.node_id' => $id
		));
		$this->get('node_content')->deleteAll(array(
			'ClogNodeContent.node_id' => $id
		));
		$this->get('node_rel')->deleteAll(array(
			'ClogNodeRel.node_object_id' => $id
		));
		
	}
	
	public function generatePost($options) {
		
		if(!is_array($options) || empty($options)) {
			return false;			
		}else{
			
			if(is_array($options) && !empty($options)) {
				extract($options);
			}
			
			$typePostId = $this->get('node_type')->generateType('clog_blog_post',$userId);
			$postNodeId = $this->get('node')->generateEmptyNode($typePostId,$userId);
			
			$this->get('node')->modifyNode($postNodeId,array(
				'has_subject' => 1,
				'has_content' => 1,
				'node_status' => $stat
			));
			
			$this->get('node_subject')->createSubject($postNodeId,$title);
			$this->get('node_permalink')->createPermalink($postNodeId,$title,'-');
			$this->get('node_content')->createContent($postNodeId,$content);
			
			if(isset($cats) && !empty($cats)) {
				
				foreach($cats as $cat) {
					$this->get('node_rel')->saveRelation($cat,$postNodeId,'clog_blog_category_post');
				}
				
			}
			
			if(isset($tags) && !empty($tags)) {
			
				foreach($tags as $tag) {
					$this->get('node_rel')->saveRelation($tag,$postNodeId,'clog_blog_tag_post');
				}
			
			}
			
			return $postNodeId;
			
		}				
		
	}
	
	public function updatePost($id,$data) {
		
		if(isset($data['title'])) {
			
			$subject = $this->get('node_subject')->find('first',array(
				'contain' => false,
				'conditions' => array('ClogNodeSubject.node_id' => $id),
				'fields' => array('ClogNodeSubject.id')
			));
			
			if(!empty($subject)) {
				
				$this->get('node_subject')->id = $subject['ClogNodeSubject']['id'];
				$this->get('node_subject')->save(array(
					'ClogNodeSubject' => array(
						'subject' => $data['title']		
					)
				));
				
			}
			
		}
		
		if(isset($data['content'])) {
				
			$subject = $this->get('node_content')->find('first',array(
				'contain' => false,
				'conditions' => array('ClogNodeContent.node_id' => $id),
				'fields' => array('ClogNodeContent.id')
			));
				
			if(!empty($subject)) {
		
				$this->get('node_content')->id = $subject['ClogNodeContent']['id'];
				$this->get('node_content')->save(array(
					'ClogNodeContent' => array(
						'content' => $data['content']
					)
				));
		
			}
				
		}
		
	}
	
	public function updatePostStat($id,$stat) {
		
		$this->get('node')->id = $id;
		$this->get('node')->save(array(
			'ClogNode' => array(
				'node_status' => $stat
			)
		));
		
	}
	
	public function updatePostTaxonomies($options) {
		
		if(!is_array($options) || empty($options)) {
			return false;
		}else{
			extract($options);
		}
		
		switch($taxo) {
			
			case 'clog_blog_tags':
				$rel = 'clog_blog_tag_post';				
				break;
				
			default:
				$rel = 'clog_blog_category_post';				
				break;
			
		}
		
		$typeId = $this->get('node_type')->getTypeIdByName($taxo);
		
		//reset
		$this->get('node_rel')->deleteAllRelations($id,$rel);
		
		foreach($data as $key) {
			
			$checkRel = $this->get('node_rel')->isRelationExists($key,$id,$rel);
			
			/*
			 * create new relation
			 */
			if(!$checkRel) {
				$this->get('node_rel')->saveRelation($key,$id,$rel);
			}
			
		}
		
	}
	
	public function getPosts($limit,$order) {
		
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_post');
		$posts = $this->get('node')->find('all',array(
			'contain' => array(
				'ClogSubject' => array(
					'fields' => array('ClogSubject.id','ClogSubject.subject')
				),
				'ClogUser' => array(
					'fields' => array('ClogUser.id','ClogUser.user_name')
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
		
		return $posts;
		
	}
	
	public function getSinglePostById($id) {
		
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_post');
		$detail = $this->get('node')->find('first',array(
			'contain' => array(
				'ClogSubject' => array(
					'fields' => array('ClogSubject.id','ClogSubject.subject')
				),
				'ClogContent' => array(
					'fields' => array('ClogContent.id','ClogContent.content')
				)
			),
			'conditions' => array(
				'ClogNode.id' => $id,
				'ClogNode.node_type_id' => $typeId
			),
			'fields' => array('ClogNode.id')
		));
		
		return $detail;
		
	}
	
	public function getSinglePostTaxonomies($id,$taxo='clog_blog_categories',$rel='clog_blog_category_post') {
				
		$categoriesNodeTypeId = $this->get('node_type')->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodeType.node_type_name' => $taxo)
		));
		
		$data = $this->get('node_rel')->find('all',array(
			'contain' => array(
				'ClogNode' => array(
					'conditions' => array('ClogNode.node_type_id' => $categoriesNodeTypeId['ClogNodeType']['id']),
					'fields' => array('ClogNode.id')
				)
			),
			'conditions' => array(
				'ClogNodeRel.node_object_id' => $id,
				'ClogNodeRel.relation_name' => $rel				
			),
			'fields' => array('ClogNodeRel.node_id','ClogNodeRel.node_object_id','ClogNodeRel.relation_name')				
		));
		
		if(!empty($data)) {
			
			$taxIds = array();
			foreach($data as $key) {
				$taxIds[] = $key['ClogNode']['id'];
			}
			
			$taxos = $this->get('node')->find('all',array(
				'contain' => array(
					'ClogSubject' => array(
						'fields' => array('ClogSubject.subject')
					)
				),
				'conditions' => array('ClogNode.id' => $taxIds),
				'fields' => array('ClogNode.id')
			));
			
			$return = array();
			foreach($taxos as $taxoKey) {
				$return[$taxoKey['ClogNode']['id']] = $taxoKey['ClogSubject']['subject'];
			}
			
			return $return;
			
		}
		
		return false;
		
	}
	
	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_post');
		
		return $this->get('node')->find('all',array(
			'contain' => array(
				'ClogType' => array(
					'fields' => array('ClogType.node_type_name')
				),
				'ClogSubject' => array(
					'fields' => array('ClogSubject.subject')
				),
				'ClogUser' => array(
					'fields' => array(
						'ClogUser.id',
						'ClogUser.user_name'
					)
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
		
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_post');
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