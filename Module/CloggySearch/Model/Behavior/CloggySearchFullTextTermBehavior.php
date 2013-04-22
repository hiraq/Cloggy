<?php

class CloggySearchFullTextTermBehavior extends ModelBehavior {
    
    /**
     * Format search term for MysqlFullText Boolean Mode
     * 
     * @param Model $model
     * @param string $term
     * @return string
     */
    public function buildTerm(Model $model,$term) {
        
        $term = trim($term);
        $explode = explode(' ',$term);
        
        /*
         * check if requested term is text or string
         */
        if (count($explode) > 1) {//if a sentences (multiple words)
            
            $return = array();
            $i = 0;
            foreach($explode as $word) {
                
                $i++;
                if (!empty($word)) {
                    
                    /*
                     * grouping each word
                     */
                    $return[] = '(+'.$word.'*)';
                    $return[] = '('.$word.')';                                                      
                    $return[] = '('.$word.'*)';
                    $return[] = '(+'.$word.')';
                    
                }
                
            }
            
            return join(' ',$return);
            
        } else {//if a word (one word)
            return '+'.$term.'*';
        }
        
    }
    
}