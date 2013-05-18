<?php

class InstallShell extends AppShell {
    
    public $uses = array('Cloggy.CloggyInstall');
    
    public function initialize() {
        parent::initialize();
        $this->stdout->styles('header',array('text' => 'blue','bold' => true));
        $this->stdout->styles('bold',array('text' => 'white','bold' => true));
    }
    
    public function startup() {
        $this->hr();
        $this->out(__d('cloggy','<header>Cloggy Shell - Installation</header>'));
        $this->hr();
    }
    
    public function main() {
        
        $this->check_os();
        $this->check_php_version();
        $this->check_tables();
        
    }        
    
    public function check_os() {
        
        $this->out(__d('cloggy','Check your operating system...'));
        sleep(1);
        
        $this->out(__d('cloggy','Your current OS : ').'<bold>'.PHP_OS.'</bold>');
        $this->hr();
        
    }
    
    public function check_php_version() {
        
        $this->out(__d('cloggy','Check your php version...'));
        sleep(1);
        
        if (floatval(PHP_VERSION) < '5.3') {
            $this->out(__d('cloggy','<error>You need at least php 5.3 version</error>'));
        } else {
            $this->out(__d('cloggy','Your current PHP Version :').'<bold>'.PHP_VERSION.'</bold>');
        }
        
        $this->hr();
    }
    
    public function check_phpunit() {
        
        ob_start();
        passthru('phpunit -v');
        $content = ob_get_contents();
        ob_end_clean();
        
        if (strstr($content,'Sebastian Bergmann')) {
            
            $this->out(__d('cloggy','<bold>PHPUnit ready</bold>'));                        
            $this->out(__d('cloggy','<bold>Phing ready to use</bold>'));
            $this->out(__d('cloggy','Running automate build now...'));            
            sleep(1);
            
            passthru('phing -f '.APP.'Plugin'.DS.'Cloggy'.DS.'build.xml');
            $this->hr();
            
            $this->url_to_use();
            
        } else {
            $this->out(__d('cloggy','<warning>You need to install phpunit to run unit test</warning>'));
        }
        
    }
    
    public function check_tables() {
        
        $tables = $this->CloggyInstall->isTableInstalled();
        
        /*
         * table not installed
         */
        if (!$tables) {
            
            $this->out(__d('cloggy','<error>Cloggy\'s tables not installed</error>'));
            $install = $this->in(__d('cloggy','Install now ? [Y]es, [N]o'),array('Y','N'),'Y');
            
            switch(ucfirst(strtolower($install))) {
                
                case 'N':
                    $this->out(__d('cloggy','<warning>You need to install Cloggy\'s tables before use it</warning>'));
                    break;
                
                default:
                    
                    $this->out(__d('cloggy','<info>Please wait...</info>'));
                    sleep(1);
                    
                    //install schema
                    $this->dispatchShell('schema create Cloggy --plugin Cloggy');            
                    
                    $this->out(__d('cloggy','<bold>Tables has been installed</bold>'));
                    $this->out(__d('cloggy','Reset...'));
                    sleep(1);
                    
                    //restart cli
                    $this->clear();
                    $this->main();
                    
                    break;
                
            }
            
        } else {
            $this->check_phing();
        }
        
    }
    
    public function check_phing() {
        
        ob_start();
        passthru('phing -v');
        $content = ob_get_contents();
        ob_end_clean();
        
        if (!strstr($content,'Phing')) {
            $this->out(__d('cloggy','Phing not installed, i recommend you to install phing'));            
        } else {            
            $this->check_phpunit();            
        }
        
    }
    
    public function url_to_use() {
        
        $host = $this->in(__d('cloggy','Please input your project host, example: http://localhost/myproject'));
        $this->hr();
        
        if (filter_var($host,FILTER_VALIDATE_URL)) {
            $host = substr($host, 0, -1);
            $this->out(__d('cloggy','To use Cloggy point your browser to: ').$host.'/cloggy');
            $this->hr();
        } else {
            $this->out(__d('cloggy','Not valid url'));
            sleep(1);
            $this->url_to_use();
        }
        
        $this->out(__d('cloggy','Dont forget to clear your caches at app/tmp/cache each time after you have done with cakephp shell.'));
        $this->out(__d('cloggy','Or simply run "phing -f build.xml clean_cache" at app/Plugin/Cloggy from your terminal'));
        
    }
    
}
