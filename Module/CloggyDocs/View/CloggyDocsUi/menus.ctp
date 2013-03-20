<h3>Menus Management</h3>

<p>
    Inside Cloggy there are two types of menus : <br />
    <ol>
        <li>Module</li>
        <li>Sidebar</li>
    </ol>
    Before version 0.3, you must be setup your menus one by one for each controllers.
    Since this version (0.3), you can create just a menus config then it will parsed
    each request for your modules.
</p>

<h3>How To</h3>

<p>
    To create menus config, you must create <strong>menus.php</strong> inside your Config directory. 
    Let say you have created <strong>MyModule</strong>, then your menus config should be: <br />
    <code>app/Plugin/Cloggy/Module/MyModule/Config/menus.php</code>. <br /><br />
    and inside your menus.php : <br />
    <pre>
        Configure::write('Cloggy.MyModule.menus', array(
        &nbsp;&nbsp;&nbsp;&nbsp;'module' => array(
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'My Menu' => array(
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'About' => CloggyCommon::urlModule('my_module', 'my_module_desc')
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        &nbsp;&nbsp;&nbsp;&nbsp;),
        &nbsp;&nbsp;&nbsp;&nbsp;'sidebar' => array(
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'Sidebar Menu' => array(
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'About' => CloggyCommon::urlModule('my_module', 'my_module_desc')
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
        &nbsp;&nbsp;&nbsp;&nbsp;)  
        ));
    </pre>    
    This config will be parsed automatically every request to your module. Each 'module' menus will be placed on top, and 'sidebar' 
    menus will placed on left sidebar.
</p>

<h3>Dynamic Sidebar Menus</h3>

<p>
    You can also setup custom sidebar menus for your module controller, you can using CloggyModuleMenuComponent. At your
    <code>beforeFilter</code> method inside your module controller, write your code like this: <br />
    <code>$this->CloggyModuleMenu->setGroup('your_group_name',array());</code><br /><br />
    Inside 'array' part you can put your menus like menus config.<br /><br />
</p>