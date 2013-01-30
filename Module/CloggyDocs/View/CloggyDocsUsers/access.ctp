<h3>User Access Management</h3>

<p>
    First time you install Cloggy, you will be asking to setup a user. This user
    will be set as 'super administrator'. Cloggy using 'roles' and 'permissions' 
    to manage user access.    
    Cloggy not using CakePHP Acl component to manage user access management, but still
    using CakePHP adapter interface.
    All user access activities will be controlled automatically by Cloggy, you just setup
    your user roles and permissions using CloggyUser module.
</p>

<h3>ARO (Access Request Object)</h3>

<p>
    Although Cloggy not using CakePHP default Acl component, still follow Acl component
    concept about ARO (access request object). What is ARO ?<br />
    <blockquote>
         things (most often users) that want to use stuff
         <small>
             <a target="_blank" href="http://book.cakephp.org/2.0/en/core-libraries/components/access-control-lists.html#understanding-how-acl-works">
                 CakePHP - Understanding How ACL Works
             </a>
         </small>
    </blockquote>    
    Inside Cloggy, who can called as ARO ? There are two types of aro : <br />
    <ol>
        <li>Roles: role_id</li>
        <li>Users: user_id</li>
    </ol>
    For this version (0.3), developer just can using one type, that is a role types.
    Each registered users inside Cloggy (admin) should be has their own roles, such
    as 'super administrator', 'administrator' or others. You can create or modify 
    your user roles <?php echo $this->Html->link('here',  CloggyCommon::urlModule('cloggy_users', 'cloggy_users_role')); ?>.    
</p>

<h3>ACO (Access Control Object)</h3>

<p>
    What is aco ?<br />
    <blockquote>
         Things in the system that are wanted (most often actions or data)
         <small>
             <a target="_blank" href="http://book.cakephp.org/2.0/en/core-libraries/components/access-control-lists.html#understanding-how-acl-works">
                 CakePHP - Understanding How ACL Works
             </a>
         </small>
    </blockquote> 
    There are three types of aco that used by Cloggy :<br />
    <ol>
        <li>Controller: controller_name/action</li>
        <li>Module: module name</li>
        <li>Url: requested url (query url not full url)</li>
    </ol>
    After your users has assigned to some roles, then you can setup that roles to allow
    or deny access to some aco. You can setup your users permission
    <?php echo $this->Html->link('here',  CloggyCommon::urlModule('cloggy_users', 'cloggy_users_perm')); ?>.    
    <br /><br />
</p>