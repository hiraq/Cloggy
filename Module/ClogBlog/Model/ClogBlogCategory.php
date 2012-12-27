<?php

App::uses('ClogAppModel','Clog.Model');

class ClogBlogCategory extends ClogAppModel {
	
	public $name = 'ClogBlogCategory';
	public $useTable = false;
	public $actsAs = array('Clog.ClogCommon');	
	
	public function isCategoryExists($category,$userId) {
		
		$typeCategoryId = $this->get('node_type')->generateType('clog_blog_categories',$userId);
		$checkCategorySubject = $this->get('node')->isSubjectExistsByTypeId($typeCategoryId,$category);
		
		return $checkCategorySubject;
		
	}
	
	public function getCategories($limit,$order) {
		
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_categories');
		$categories = $this->get('node')->find('all',array(
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
		
		return $categories;
		
	}
	
	public function getAllCategories($except=null) {
		
		$categoriesNodeTypeId = $this->get('node_type')->find('first',array(
			'contain' => false,
			'conditions' => array('ClogNodeType.node_type_name' => 'clog_blog_categories')
		));
		
		if(!empty($categoriesNodeTypeId)) {

			$conditions = array(
				'ClogNode.node_type_id' => $categoriesNodeTypeId['ClogNodeType']['id']
			);
			
			if(!is_null($except)) {
				
				if(!is_array($except)) {
					
					$conditions = array_merge($conditions,array(
						'ClogNode.id !=' => $except
					));
					
				}else{
					
					$conditions = array_merge($conditions,array(
						'NOT' => array('ClogNode.id' => $except)
					));
					
				}				
			}
			
			$categories = $this->get('node')->find('all',array(
				'contain' => array(
					'ClogSubject' => array(
						'fields' => array('ClogSubject.subject')
					)					
				),
				'conditions' => $conditions,
				'fields' => array('ClogNode.id')
			));
			
			return $categories;
			
		}						

		return false;
		
	}
	
	public function getDetailCategory($id) {
		
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
		
		if(!empty($category)) {

			/*
			 * generate node parent
			 */
			$parent = $this->get('node')->find('first',array(
				'contain' => array(
					'ClogSubject' => array(
						'fields' => array('ClogSubject.subject')
					)					
				),
				'conditions' => array('ClogNode.id' => $category['ClogParentNode']['id']),
				'fields' => array('ClogNode.id')
			));
			
			//reset
			unset($category['ClogParentNode']);
			$category = array_merge($category,array('ClogParentNode' => $parent));
			
		}
		
		return $category;
		
	}
	
	public function getParentCategory($id) {
		
		$data = $this->get('node')->find('first',array(
			'contain' => array(
				'ClogParentNode'
			),
			'conditions' => array('ClogNode.id' => $id)
		));
		
		$parent = $this->get('node')->find('first',array(
			'contain' => array(
				'ClogSubject' => array(
					'fields' => array('ClogSubject.subject')
				)
			),
			'conditions' => array('ClogNode.id' => $data['ClogParentNode']['id']),
			'fields' => array('ClogNode.id')
		));
		
		return $parent;
		
	}
	
	public function updateCategory($id,$catName) {
		
		$this->get('node_subject')->updateAll(
			array('ClogNodeSubject.subject' => '"'.Sanitize::escape($catName).'"'),
			array('ClogNodeSubject.node_id' => $id)			
		);
		
	}
	
	public function updateCategoryParent($id,$parentId) {
		
		$this->get('node')->updateAll(
			array('ClogNode.node_parent' => '"'.Sanitize::escape($parentId).'"'),
			array('ClogNode.id' => $id)
		);
		
	}
	
	public function deleteCategory($id) {
		
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
	
	public function proceedCategories($categories,$userId) {
		
		$cats = array();
				
		foreach($categories as $cat) {
		
			$typeId = $this->get('node_type')->generateType('clog_blog_categories',$userId);
				
			$checkCatSubject = $this->get('node')->isSubjectExistsByTypeId($typeId,$cat);
			if(!$checkCatSubject) {
		
				$catNodeId = $this->get('node')->generateEmptyNode($typeId,$userId);
				$this->get('node')->modifyNode($catNodeId,array(
					'has_subject' => 1
				));
		
				$this->get('node_subject')->createSubject($catNodeId,$cat);
				$this->get('node_permalink')->createPermalink($catNodeId,$cat,'-');
		
			}else{
				$catNodeId = $this->get('node')->getNodeIdBySubjectAndTypeId($typeId,$cat);
			}
				
			$cats[] = $catNodeId;
				
		}
		
		return $cats;
		
	}
	
	public function setCategoryParent($parentId,$catId) {
		
		$this->get('node')->id = $catId;
		$this->get('node')->save(array(
			'ClogNode' => array(
				'node_parent' => $parentId
			)
		));
		
	}
	
	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
	
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_categories');
	
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
	
		$typeId = $this->get('node_type')->getTypeIdByName('clog_blog_categories');
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