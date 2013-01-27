<?php

App::uses('CloggyAppController', 'Cloggy.Controller');

class CloggyDocsDbController extends CloggyAppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny('*');                                                                 
        
        /*
         * menus for database documentations
         */
        $this->CloggyModuleMenu->setGroup('tables', array(
            'Nodes' => CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_db/nodes'),
            'Node Contents' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/node_contents/'),
            'Node Media' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/node_media/'),
            'Node Permalinks' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/node_permalinks/'),
            'Node Rels' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/node_rels/'),
            'Node Subjects' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/node_subjects/'),
            'Node Types' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/node_types/'),
            'Users' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/users/'),
            'User Perms' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/users_perms/'),
            'User Roles' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/users_roles/'),
            'User Login' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/user_login/'),
            'User Meta' => CloggyCommon::urlModule('cloggy_docs_db', 'cloggy_docs_db/user_meta/')
        ));
        
        //remove menus group
        $this->CloggyModuleMenu->removeGroup('Basic');  
        
    }                
    
    public function index() {           
        $this->set('title_for_layout', 'CloggyDocs - Database');
    }
    
}