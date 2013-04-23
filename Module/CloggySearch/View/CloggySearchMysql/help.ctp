<h4>MysqlFullText Search</h4>

<p>
    MySQL has support for full-text indexing and searching. For now FullText search only supported
    for MyISAM engine, although InnoDB will be supported too. Basically, people try to searching their
    data at mysql databases using LIKE with '%' operator. It's different with search, it's more like for reqular
    expression (LIKE). On other hand, FULLTEXT are fully indexed fields with support stopwords, boolean searches 
    and relevancy ratings.<br /><br />
    CloggySearch is a module to handle all search operations and supported for MySQL FullText Search. This module
    will indexing your tables from schema that you should be setup first. By default schema would be supported only for
    Cloggy nodes structures, but you can add your tables too inside that schema. After you have done setup schema for 
    your tables, you can using this module to search data inside your tables. All search operations will not touch your tables,
    but happening inside CloggySearch fulltext table (where data stored inside it).<br /><br/>
</p>

<h4>Schema</h4>

<p>
    Edit CloggySearch module schema for mysql full text at <strong>CloggySearch/Config/schema_mysqlfull_text.php</strong>.
    Assume your table that want to index named with <strong>'my_table'</strong>. Then schema created inside shema_mysqlfull_text should be: <br />
    <pre>
    /*
    * cloggy schema configurations
    */
   $config = array(
       'table_prefix' => 'cloggy_',
       'tables' => array(
           'node_contents' => array(
               'primary_key' => 'id',
               'field' => array(
                   'name' => 'content',
                   'format' => 'text'
               ),
               'limit' => 100
           ),
           'node_subjects' => array(
               'primary_key' => 'id',
               'field' => array(
                   'name' => 'subject',
                   'format' => 'sentences'
               ),
               'limit' => 100
           )
       )
   );

    $myconfig = array(
        'tables' => array(
            'my_table' => array(
                'primary_key' => 'id',
                'field' => array(
                    'name' => 'my_table_field_to_indexed',
                    'format' => 'text'
                ),
                'limit' => 50
            )
        )
    );

    //write config
    Configure::write('Cloggy.CloggySearch.schema_mysqlfull_text',array(
        'cloggy' => $config,
        'my_group' => $myconfig
    ));
    </pre>
    <br />
    Required options: <br />
    <ol>
        <li>tables</li>
        <li>primary_key</li>
        <li>
                field (including name and format). Name means, field name that you want the data to indexed, 
                one table only has one field to index. Format means, which format you want to choose, you can choose
                'text' if your data is like a blogpost, or choose 'sentences' if your data is like a blog title. Please remember
                that one table only has one field to index.
        </li>        
    </ol>
    Optional options: <br />
    <ol>
        <li>table prefix</li>
        <li>
                limit. Default value for limit only 10, please only give reasonable value, it's because, after you set limit value
                this module will automatically index your data based on your limit value, and it means if you set this value
                to 1000, then each update, this module will indexing your 1000 data from your table, if only one table to index
                maybe it's not a problem, but if there are have let's say, 20 tables, then you should upgrade your php memory limit 
                and max_execution time to ensure that update process running.
        </li>
    </ol>
    <br />
    After you have done create your schema, then go to 
    <?php echo $this->Html->link('update',  CloggyCommon::urlModule('cloggy_search')); ?>, then choose 'Update' to update
    index. After update process, you can <?php echo $this->Html->link('test',  CloggyCommon::urlModule('cloggy_search')); ?> search too.
    <br /><br />
</p>