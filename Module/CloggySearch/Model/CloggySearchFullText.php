<?php

App::uses('CloggyAppModel', 'Cloggy.Model');

class CloggySearchFullText extends CloggyAppModel {       
    
    public $name = 'CloggySearchFullText';
    public $useTable = 'search_fulltext';
    public $actsAs = array('CloggySearchFullTexIndex','CloggySearchFullTextTerm');
    
    public function getTotal() {
        return $this->find('count');
    }       
    
    /**
     * Do search using MysqlFullTextSearch
     * 
     * @link http://dev.mysql.com/doc/refman/5.0/en/fulltext-natural-language.html NATURAL SEARCH
     * @link http://dev.mysql.com/doc/refman/5.0/en/fulltext-boolean.html BOOLEAN MODE
     * @param string $query
     * @return array
     */
    public function search($query) {
        
        //default used mode
        $usedMode = __d('cloggy','Natural Search');
        
        /*
         * first try to search with natural search
         * NATURAL SEARCH                  
         */
        $data = $this->find('all',array(
            'contain' => false,
            'fields' => array('*','MATCH (CloggySearchFullText.source_sentences,CloggySearchFullText.source_text) AGAINST (\''.$query.'\') AS rating'),
            'conditions' => array('MATCH (CloggySearchFullText.source_sentences,CloggySearchFullText.source_text) AGAINST (\''.$query.'\')'),
            'order' => array('rating' => 'desc')
        ));
        
        /*
         * if failed or empty results then
         * reset search in BOOLEAN MODE
         * with operator '*' that means search
         * data which contain 'query' or 'queries', etc                  
         */
        if (empty($data)) {
            
            //format query string
            $query = $this->buildTerm($query);
            
            /*
             * begin searching data
             */
            $data = $this->find('all',array(
                'contain' => false,
                'fields' => array('*','MATCH (CloggySearchFullText.source_sentences,CloggySearchFullText.source_text) AGAINST (\''.$query.'\' IN BOOLEAN MODE) AS rating'),
                'conditions' => array('MATCH (CloggySearchFullText.source_sentences,CloggySearchFullText.source_text) AGAINST (\''.$query.'\' IN BOOLEAN MODE)'),
                'order' => array('rating' => 'desc')
            ));                            
            
            //switch search mode
            $usedMode = __d('cloggy','Boolean Mode');
            
        }
        
        return array(
            'mode' => $usedMode,
            'results' => $data
        );
        
    }        
    
}