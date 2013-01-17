<h3>Create A Module</h3>

<p>
    This is a steps to create Cloggy modules:
    <ol>
        <li>
            Create your module folder at : <strong>app/Plugin/Cloggy/Module/</strong>.
            Example: If you want to create <strong>MyModule</strong> module, then your module
            path should located at : <strong>app/Plugin/Cloggy/Module/MyModule</strong>
        </li><br />
        <li>
            Each module should contain at least one controller for your module home page. If
            your module name is <strong>MyModule</strong> then your module should has one controller:
            MyModuleHomeController at 
            <strong>app/Plugin/Cloggy/Module/MyModule/Controller/MyModuleHomeController.php</strong>
        </li><br />        
        <li>
            You module must be have an info file (info.ini), at
            <strong>app/Plugin/Cloggy/Module/MyModule/info.ini</strong>
        </li><br />
        <li>
            Your info.ini file should contain these informations : name, author, url,dependency. You
            can learn about this INI file from this module (CloggyDocs).
        </li><br />
        <li>
            You can create your own model,controller, and view files inside your module folder and working
            like usual follow CakePHP convention rules, except that each time you create your file such as
            controller or model that extend from CakePHP class/object, you must load parent class manually, for example
            if you want to create MyModuleHomeController, then before you write your controller class, you should
            load this:<br /><br />
            <code>
                App::uses('CloggyAppController', 'Cloggy.Controller');
            </code>
        </li><br />
        <li>
            Remember to 
                <?php echo $this->Html->link('activate',  CloggyCommon::urlModule('cloggy_docs', 'cloggy_docs_module/activation')); ?> 
            your module.
        </li>
    </ol>
</p>