<?php 
class CloggySchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $cloggy_node_contents = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'node_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'content' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'node_id' => array('column' => 'node_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_node_media = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'node_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'media_file_type' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'media_file_location' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'node_id' => array('column' => array('node_id', 'media_file_type'), 'unique' => 0),
			'media_file_location' => array('column' => 'media_file_location', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_node_meta = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'node_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'meta_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'meta_value' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'node_id' => array('column' => array('node_id', 'meta_key'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_node_permalinks = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'node_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'permalink_url' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'node_id' => array('column' => array('node_id', 'permalink_url'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_node_rels = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'node_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'node_object_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150),
		'relation_name' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'relation_created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'relation_updated' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'node_id' => array('column' => array('node_id', 'node_object_id'), 'unique' => 0),
			'relation_name' => array('column' => 'relation_name', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_node_subjects = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'node_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'subject' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'node_id' => array('column' => 'node_id', 'unique' => 0),
			'subject' => array('column' => 'subject', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_node_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'node_type_name' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'node_type_desc' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'node_type_created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'node_type_updated' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'node_type_name' => array('column' => 'node_type_name', 'unique' => 0),
			'user_id' => array('column' => 'user_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_nodes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'node_type_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150),
		'node_parent' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 150, 'key' => 'index'),
		'has_subject' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
		'has_content' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
		'has_media' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
		'has_meta' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
		'node_status' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
		'node_created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'node_updated' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'node_type_id' => array('column' => 'node_type_id', 'unique' => 0),
			'node_parent' => array('column' => 'node_parent', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_user_login = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'login_datetime' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_user_meta = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'index'),
		'meta_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'meta_value' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => array('user_id', 'meta_key'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 150, 'key' => 'primary'),
		'user_name' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'user_password' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'user_email' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'users_roles_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'user_status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'user_last_login' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'user_created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'user_updated' => array('type' => 'timestamp', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_name' => array('column' => 'user_name', 'unique' => 0),
			'user_email' => array('column' => array('user_email', 'user_status'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_users_perms = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'aro_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'aro_object' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'aco_object' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'aco_adapter' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'allow' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'deny' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'aco_adapter' => array('column' => 'aco_adapter', 'unique' => 0),
			'aro_object' => array('column' => 'aro_object', 'unique' => 0),
			'aro_object_id' => array('column' => 'aro_object_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
	public $cloggy_users_roles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'role_name' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'role_name' => array('column' => 'role_name', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);
}
