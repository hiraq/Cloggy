<h3>Javascript Management</h3>

<p>
    Cloggy administrator also using javascript, and there are three javascript libraries used by  Cloggy:<br />
    <ol>        
        <li><a href="http://jquery.com/" target="_blank">jQuery (dom manipulation)</a></li>
        <li><a href="http://sugarjs.com/" target="_blank">SugarJS (data processing)</a></li>
        <li><a href="http://handlebars.com/" target="_blank">HandleBars (template engine)</a></li>
    </ol>
    If you need to create your own javascript code for your module, you should write your code like this in your view file: <br />
    <pre>
        $this->append('cloggy_js_module_page');    
        &lt;script type="text/javascript"&gt;
             //your javascript codes
        &lt;/script&gt;        
        $this->end();
    </pre>
</p>