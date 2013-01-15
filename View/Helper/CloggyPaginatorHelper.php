<?php

App::uses('PaginatorHelper', 'View/Helper');

class CloggyPaginatorHelper extends PaginatorHelper {

    /**
     * @link https://gist.github.com/1263853
     * @param string $pluginUrl
     */
    public function paginatorBootstrap($pluginUrl) {

        $requestedParams = $this->_View->request->params;

        $params = $this->params();
        if ($params['pageCount'] < 2) {
            return false;
        }

        $this->options(array(
            'url' => array(
                'order' => '',
                'plugin' => $pluginUrl
            ),
        ));

        if (isset($requestedParams['named']['sort_index'])
                && !empty($requestedParams['named']['sort_index'])) {

            $this->options['url'] = array_merge($this->options['url'], array(
                'sort_index' => $requestedParams['named']['sort_index']
                    ));
        }

        $modulus = 11;
        $models = ClassRegistry::keys();
        $model = Inflector::camelize(current($models));

        $page = $this->params['paging'][$model]['page'];
        $pageCount = $this->params['paging'][$model]['pageCount'];
        if ($modulus > $pageCount) {
            $modulus = $pageCount;
        }
        $start = $page - intval($modulus / 2);
        if ($start < 1) {
            $start = 1;
        }
        $end = $start + $modulus;
        if ($end > $pageCount) {
            $end = $pageCount + 1;
            $start = $end - $modulus;
        }
        for ($i = $start; $i < $end; $i++) {
            $url = array('page' => $i);
            $class = null;
            if ($i == $page) {
                $url = array();
                $class = 'active';
            }

            echo $this->Html->tag('li', $this->link($i, $url), array(
                'class' => $class,
            ));
        }
    }

}