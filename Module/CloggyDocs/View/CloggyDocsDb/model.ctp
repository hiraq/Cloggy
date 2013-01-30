<h3>Working With Model</h3>

<p>
    You can create your own model inside your module. But if you want to manage your
    content using Cloggy table structures, you don't have to create each model for each
    tables. If you want to load Cloggy models, you can do that with:<br /><br />
    <code>
        public $uses = array('Cloggy.CloggyNode')
    </code>
    <br /><br />
    From example above, now you can use 'CloggyNode' model inside your module controller.    
</p>

<h3>Behavior</h3>

<p>
    Cloggy equipped with custom behavior named 'CloggyCommon'. With this special behavior
    you can load all Cloggy's models inside your module models. Let say, you have created 
    a MyModel, and need to interact with CloggyNode, then you must register this behavior
    inside your model, like this: <br /><br />
    <code>
        public $actsAs = array('Cloggy.CloggyCommon');
    </code>
    <br /><br />
    After that you can load all models belongs to Cloggy inside your model, example:<br /><br />
    <code>
        $this->get('node')->find()
    </code>
    <br /><br />    
</p>