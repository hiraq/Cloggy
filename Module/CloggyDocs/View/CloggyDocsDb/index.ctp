<h3>About Structured Content</h3>

<p>
    Each time we develop a website we always working about <strong>content management</strong>.
    It's about how we manage our content site. Blog site, news site or even e-commerce site, have 
    different table structures to manage their data. What do we do if we want develop for blog site
    for now, then tomorrow we must setup e-commerce site ? In the past, we must setup different tables
    for them, it's because they have different content types right?
    <br /><br />
    With Cloggy, beside used as <strong>web administration management</strong>, Cloggy also provide a set
    of database tables to handle that problems. Cloggy provide general structured table to manage your data
    (content).
</p>

<h3>Node</h3>
    
<blockquote>
    <p>A node is a record consisting of one or more fields that are links to other nodes, and a data field</p>
    <small><a href="http://en.wikipedia.org/wiki/Node_(computer_science)" target="_blank">WikiPedia</a></small>
</blockquote>

<p>
    Cloggy using 'node' to manage general content. People maybe know about 'node' it's because 
    <a href="http://drupal.org" target="_blank">Drupal</a>. Drupal is a first CMS that manage their main content
    following concept of node. What is relation between Drupal and Cloggy? Cloggy using CakePHP framework as a core,
    and Cloggy doesn't have any affiliate relation with Drupal. But after learn many thing about how to manage
    content of website, i can conclude that the concept of 'node' is the most flexible way to manage general 
    and structured content.
    <br /><br />
    Cloggy has 13 tables to manage data, they are:
    <ul>
        <li>Nodes</li>
        <li>Node contents</li>
        <li>Node subjects</li>
        <li>Node permalinks</li>
        <li>Node meta</li>
        <li>Node media</li>
        <li>Node rels</li>
        <li>Node types</li>
        <li>Users</li>
        <li>User roles</li>
        <li>User permissions</li>
        <li>User login</li>
        <li>User meta</li>
    </ul>
    <br />
    How exactly for developer to manage their data using these tables? I'm assume you already know about users management
    such as users,roles,permissions,login,meta, it's because following a standard in website. Now we talk about 'nodes'. 
    Node table is a main table for other node activities. Node table columns are:<br /><br />
    <ul>
        <li>id</li>
        <li>node_type_id</li>
        <li>user_id</li>
        <li>node_parent</li>
        <li>has_subject</li>
        <li>has_content</li>
        <li>has_media</li>
        <li>has_meta</li>
        <li>node_status</li>
        <li>node_created</li>
        <li>node_updated</li>
    </ul>
    <br />
    All content types will using this table. Blog posts,news articles,product descriptions,comments,categories,tags, etc.
    This table acts as main processor for your content. Let say you want to create a blog post. Then your post structure 
    data should be:<br /><br />
    <ul>
        <li>id: <code>auto_increment</code></li>
        <li>node_type_id <code>it means your content type,ex: blog_post</code></li>
        <li>user_id <code>author</code></li>
        <li>node_parent <code>only if post is a sub post from other post</code></li>
        <li>has_subject <code>value: 1</code></li>
        <li>has_content <code>value: 1</code></li>
        <li>has_media <code>value: 1 (only if your post using image/video/etc)</code></li>
        <li>has_meta <code>value: 1 (only if your post need post meta like: schema.org or others)</code></li>
        <li>node_status <code>value: 1(1 mean publish,0 draft)</code></li>
        <li>node_created <code>date time when post created</code></li>
        <li>node_updated <code>only change when update post</code></li>
    </ul>
    <br />
    If you filled field <code>has_subject</code> with value 1, then this node data has a connection with 
    <code>node_subjects</code> table that contain real post title. 
    <br /><br />
    How about categories or tag? You can
    set same as example above, except that value for <code>has_content</code> should be '0' and this means
    this 'node' doesn't have any content, of course you can change this into '1' if you want your categories
    has any description.    
    <br /><br />
    How about comments? Comments need a content but don't need a subject. So, you can set your 'node', 
    <code>has_subject</code> into '0', and <code>has_content</code> into '1', and maybe
    you want to set <code>node_status</code> into '0' as need moderation or '1' as publish. 
    And you should set 'node_rels' that manage relation between nodes, example: <br /><br />
    <ul>
        <li>id: <code>auto_increment</code></li>
        <li>node_id: <code>node that handle comment structure</code></li>
        <li>node_object_id: <code>node that node_id has relation with, in this case a blog post</code></li>
        <li>relation_name: <code>ex: blog_post_comment</code></li>
        <li>relation_created: <code>date time when relation created</code></li>
        <li>relation_updated: <code>only change when update comment</code></li>
    </ul>
    <br />
    This is how we manage our data. If you want to learn more, you can explore CloggyBlog module.
    <br /><br />
</p>

