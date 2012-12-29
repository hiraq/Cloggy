<?php

class CloggyNodeMedia extends CloggyAppModel {
	
	public $name = 'CloggyNodeMedia';
	public $useTable = 'node_media';
	
	public $belongsTo = array(
		'CloggyNode' => array(
			'className' => 'Cloggy.CloggyNode',
			'foreignKey' => 'node_id'
		)
	);
	
}