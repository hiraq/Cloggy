<h3>About Module</h3>

<p>
    All Cloggy administration activity separated by modules. So what is module?
    Module is like a "plugin" inside a plugin, or an extension for Cloggy. You 
    can create your own module like for guestbook management, page management, or
    others, there are no limits.
</p>

<h3>Working With Module</h3>

<p>
    Working with module, just like working with CakePHP plugin. If you know about
    CakePHP plugin, then you can work with module. All module folder and files follow
    CakePHP naming conventions, the only difference is you must create your module at 
    this path:<br /><br />
    <code>
        app/Plugin/Cloggy/Module/
    </code>
    <br /><br />
    You can create your own controller,model and views. Cloggy has been setup custom 
    path for you. This is a list of what you can create inside your module : <br />
    <ul>
        <li>Controller</li>
        <li>Controller/Component</li>
        <li>Model</li>
        <li>Model/Behavior</li>
        <li>Model/Datasource</li>
        <li>View</li>
        <li>View/Helper</li>
    </ul>
    
    All controller,model,view and their extensions (component,behavior,helper), created
    using CakePHP convention, so you should working like usual. But you must remember,
    that Cloggy module is not a part of CakePHP plugin, so you're <strong>not</strong> 
    allowed use this : 
    <br /><br />
    <code>
        App::uses('MyModel',<strong>'MyModule.Model'</strong>); //plugin convention
    </code>
    <br /><br />
    If you want to load/call some file from inside your module, you should use this :
    <br /><br />
    <code>
        App::uses('MyModel','Model');
    </code>
    <br /><br />
    It's because, Cloggy register all module path as CakePHP custom path not a part of
    plugin. This mean, you can call module files from your main app (if Cloggy enabled).
    <br /><br />
    You can't also create your own router for your module, all url request will be formatted
    like this :<br />
    <code>
        http://your_project/cloggy/module/my_module<br />
        http://your_project/cloggy/module/my_module/my_module_controller/action<br />
    </code>
</p>

<br /><br /><br /><br /><br /><br />