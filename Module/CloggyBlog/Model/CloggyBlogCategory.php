<?php

App::uses('CloggyAppModel','Cloggy.Model');

class CloggyBlogCategory extends CloggyAppModel {
	
	public $name = 'CloggyBlogCategory';
	public $useTable = false;
	public $actsAs = array('Cloggy.CloggyCommon');	
	
	public function isCategoryExists($category,$userId) {
		
		$typeCategoryId = $this->get('node_type')->generateType('cloggy_blog_categories',$userId);
		$checkCategorySubject = $this->get('node')->isSubjectExistsByTypeId($typeCategoryId,$category);
		
		return $checkCategorySubject;
		
	}
	
	public function getCategories($limit,$order) {
		
		$typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_categories');
		$categories = $this->get('node')->find('all',array(
			'contain' => array(
				'CloggySubject' => array(
					'fields' => array('CloggySubject.id','CloggySubject.subject')
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
	
	public function getAllCategories($except=null) {
		
		$categoriesNodeTypeId = $this->get('node_type')->find('first',array(
			'contain' => false,
			'conditions' => array('CloggyNodeType.node_type_name' => 'cloggy_blog_categories')
		));
		
		if(!empty($categoriesNodeTypeId)) {

			$conditions = array(
				'CloggyNode.node_type_id' => $categoriesNodeTypeId['CloggyNodeType']['id']
			);
			
			if(!is_null($except)) {
				
				if(!is_array($except)) {
					
					$conditions = array_merge($conditions,array(
						'CloggyNode.id !=' => $except
					));
					
				}else{
					
					$conditions = array_merge($conditions,array(
						'NOT' => array('CloggyNode.id' => $except)
					));
					
				}				
			}
			
			$categories = $this->get('node')->find('all',array(
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
	
	public function getDetailCategory($id) {
		
		/*
		 * get detail category
		 */
		$category = $this->get('node')->find('first',array(
			'contain' => array(
				'CloggySubject' => array(
					'fields' => array('CloggySubject.subject')
				),
				'CloggyParentNode'
			),
			'conditions' => array('CloggyNode.id' => $id),
			'fields' => array('CloggyNode.id')
		));
		
		if(!empty($category)) {

			/*
			 * generate node parent
			 */
			$parent = $this->get('node')->find('first',array(
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
			$category = array_merge($category,array('CloggyParentNode' => $parent));
			
		}
		
		return $category;
		
	}
	
	public function getParentCategory($id) {
		
		$data = $this->get('node')->find('first',array(
			'contain' => array(
				'CloggyParentNode'
			),
			'conditions' => array('CloggyNode.id' => $id)
		));
		
		$parent = $this->get('node')->find('first',array(
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
	
	public function updateCategory($id,$catName) {
		
		$this->get('node_subject')->updateAll(
			array('CloggyNodeSubject.subject' => '"'.Sanitize::escape($catName).'"'),
			array('CloggyNodeSubject.node_id' => $id)			
		);
		
	}
	
	public function updateCategoryParent($id,$parentId) {
		
		$this->get('node')->updateAll(
			array('CloggyNode.node_parent' => '"'.Sanitize::escape($parentId).'"'),
			array('CloggyNode.id' => $id)
		);
		
	}
	
	public function deleteCategory($id) {
		
		$this->get('node')->delete($id,false);
		
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
	
	public function proceedCategories($categories,$userId) {
		
		$cats = array();
		
		$this->get('node')->cacheQueries = false;
		$this->get('node_type')->cacheQueries = false;
				
		foreach($categories as $cat) {
		
			$typeId = $this->get('node_type')->generateType('cloggy_blog_categories',$userId);
				
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
			'CloggyNode' => array(
				'node_parent' => $parentId
			)
		));
		
	}
	
	public function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
	
		$typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_categories');
	
		return $this->get('node')->find('all',array(
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
	
	public function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
	
		$typeId = $this->get('node_type')->getTypeIdByName('cloggy_blog_categories');
		return $this->get('node')->find('count',array(
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