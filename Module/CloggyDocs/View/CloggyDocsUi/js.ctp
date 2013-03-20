<h3>Javascript Management</h3>

<p>
    Cloggy administrator also using javascript, and there are two main javascript libraries used by  Cloggy:<br />
    <ol>
        <li><a href="http://yepnopejs.com/" target="_blank">YepNope</a></li>
        <li><a href="http://jquery.org/" target="_blank">jQuery</a></li>
    </ol>
    If you need to create your own javascript code for your module, you should write your code like this in your view file: <br />
    <pre>
        $this->append('cloggy_js_module_page');    
        &nbsp;&nbsp;&lt;script type="text/javascript"&gt;
        &nbsp;&nbsp;&nbsp;&nbsp;    //your javascript codes
        &nbsp;&nbsp;&lt;/script&gt;        
        $this->end();
    </pre>
</p>

<h3>YepNope</h3>

<p>
    What is YepNope ? <br />
    <blockquote>
         yepnope is an asynchronous conditional resource loader that's super-fast, and allows you to load only the scripts that your users need. 
    </blockquote>
    Cloggy using YepNope to load asynchronously <strong>all resources</strong> for css and js files, so you should enable your javascript in your
    browser.
</p>

<h3>jQuery</h3>

<p>
    If you want to write some animation or maybe action handler using jQuery, you should write your code like this in your view file: <br /><br />
     <pre>
        $this->append('cloggy_js_module_page');
        &nbsp;&nbsp;&lt;script type="text/javascript"&gt;
        &nbsp;&nbsp;&nbsp;&nbsp;cloggy.captureJQuery(function() {
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;//your jquery code
        &nbsp;&nbsp;&nbsp;&nbsp;});
        &nbsp;&nbsp;&lt;/script&gt;        
        $this->end();
    </pre>
    <br /><br />
    <code>cloggy.captureJQuery</code> it same with <code>jQuery(document).ready(function)</code>, the difference is, that all jquery source
    library will be loaded asynchronously, and it means your code will be executed asynchronous too, that's why you cannot use standard
    jquery code here.
    <br /><br />
</p>