<?php

class ClogNodeMedia extends ClogAppModel {
	
	public $name = 'ClogNodeMedia';
	public $useTable = 'node_media';
	
	public $belongsTo = array(
		'ClogNode' => array(
			'className' => 'Clog.ClogNode',
			'foreignKey' => 'node_id'
		)
	);
	
}