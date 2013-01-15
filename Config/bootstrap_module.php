<?php

/*
 * get all registered modules
 */
$registeredModules = Configure::read('Cloggy.modules');

/*
 * setup custom mvc path
 */
if (!empty($registeredModules)) {

    /*
     * custom mvc path by modules
     */
    $buildModuleControllerPaths = array();
    $buildModuleModelPaths = array();
    $buildModuleViewPaths = array();

    /*
     * custom mvc extensions by modules
     */
    $buildModuleComponentPaths = array();
    $buildModuleHelperPaths = array();
    $buildModuleBehaviorPaths = array();
    $buildModuleDatasourcePaths = array();

    $mainPath = APP . 'Plugin' . DS . 'Cloggy' . DS . 'Module' . DS;

    foreach ($registeredModules as $module) {

        $modulePath = $mainPath . $module . DS;

        /*
         * if module exists
         */
        if (is_dir($modulePath)) {

            /*
             * custom controller
             */
            $moduleControllerPath = $modulePath . 'Controller' . DS;
            $moduleComponentPath = $moduleControllerPath . 'Component' . DS;

            if (is_dir($moduleControllerPath)) {
                $buildModuleControllerPaths[] = $moduleControllerPath;
            }

            if (is_dir($moduleComponentPath)) {
                $buildModuleComponentPaths[] = $moduleComponentPath;
            }

            /*
             * custom model
             */
            $moduleModelPath = $modulePath . 'Model' . DS;
            $moduleBehaviorPath = $moduleModelPath . 'Behavior' . DS;
            $moduleDatasourcePath = $moduleModelPath . 'Datasource' . DS;

            if (is_dir($moduleModelPath)) {
                $buildModuleModelPaths[] = $moduleModelPath;
            }

            if (is_dir($moduleBehaviorPath)) {
                $buildModuleBehaviorPaths[] = $moduleBehaviorPath;
            }

            if (is_dir($moduleDatasourcePath)) {
                $buildModuleDatasourcePaths[] = $moduleDatasourcePath;
            }

            /*
             * custom view
             */
            $moduleViewPath = $modulePath . 'View' . DS;
            $moduleHelperPath = $moduleViewPath . 'Helper' . DS;

            if (is_dir($moduleViewPath)) {
                $buildModuleViewPaths[] = $moduleViewPath;
            }

            if (is_dir($moduleHelperPath)) {
                $buildModuleHelperPaths[] = $moduleHelperPath;
            }
        }
    }

    /*
     * register custom model and extension paths
     */
    if (!empty($buildModuleModelPaths)) {
        App::build(array('Model' => $buildModuleModelPaths), APP::APPEND);
    }

    if (!empty($buildModuleBehaviorPaths)) {
        App::build(array('Model/Behavior' => $buildModuleBehaviorPaths), APP::APPEND);
    }

    if (!empty($buildModuleDatasourcePaths)) {
        App::build(array('Model/Datasource' => $buildModuleDatasourcePaths), APP::APPEND);
    }

    /*
     * register custom view and helper paths
     */
    if (!empty($buildModuleViewPaths)) {
        App::build(array('View' => $buildModuleViewPaths), APP::APPEND);
    }

    if (!empty($buildModuleHelperPaths)) {
        App::build(array('View/Helper' => $buildModuleHelperPaths), APP::APPEND);
    }

    /*
     * register custom controller and component paths
     */
    if (!empty($buildModuleControllerPaths)) {
        App::build(array('Controller' => $buildModuleControllerPaths), APP::APPEND);
    }

    if (!empty($buildModuleComponentPaths)) {
        App::build(array('Controller/Component' => $buildModuleComponentPaths), APP::APPEND);
    }
}