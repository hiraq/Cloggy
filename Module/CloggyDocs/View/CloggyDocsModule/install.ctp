<h3>Module Installation</h3>

<p>
    If your module need to install first, then this is how you can do it (assume your module name is: <strong>MyModule</strong>):
    <ol>
        <li>Create <strong>MyModuleInstallController.php</strong></li>
        <li>At action 'index' inside your installation controller (MyModuleInstallController.php), you can place your logic</li>
        <li>
            After your logic is done, render a view to give your user a notification (success/failed), 
            at this step Cloggy will automatically install your module by create an empty file <strong>.installed</strong>
            inside your module folder.
        </li>
    </ol>
    You can open <strong>CloggySearchInstallController.php</strong> to learn an example about module installation.
</p>