<?php

class CloggyUserMeta extends CloggyAppModel {

    public $name = 'CloggyUserMeta';
    public $useTable = 'user_meta';
    public $belongsTo = array(
        'CloggyUser' => array(
            'className' => 'Cloggy.CloggyUser',
            'foreignKey' => 'user_id'
        )
    );

    /**
     * Check if meta exists or not
     * @param int $userId
     * @param string $key
     * @return boolean
     */
    public function isMetaExists($userId, $key) {

        $check = $this->find('count', array(
            'contain' => false,
            'conditions' => array(
                'CloggyUserMeta.user_id' => $userId,
                'CloggyUserMeta.meta_key' => $key
            )
                ));

        return $check < 1 ? false : true;
    }

    /**
     * Get detail user meta
     * @param int $userId
     * @return array
     */
    public function getUserMeta($userId) {

        $data = $this->find('all', array(
            'contain' => false,
            'conditions' => array('CloggyUserMeta.user_id' => $userId)
                ));

        return $data;
    }

    /**
     * Update user meta data
     * @param int $userId
     * @param string $key
     * @param string $value
     * @return boolean
     */
    public function updateMeta($userId, $key, $value) {

        $check = $this->isMetaExists($userId, $key);
        if ($check) {

            $data = $this->find('first', array(
                'contain' => false,
                'conditions' => array(
                    'CloggyUserMeta.user_id' => $userId,
                    'CloggyUserMeta.meta_key' => $key
                ),
                'fields' => array('CloggyUserMeta.id')
                    ));

            $this->id = $data['CloggyUserMeta']['id'];
            $update = $this->saveField('meta_value', $value);

            return !empty($update) ? true : false;
        }

        return false;
    }

    /**
     * Create and save meta data
     * @param int $userId
     * @param array $data
     * @return boolean
     */
    public function saveMeta($userId, $data) {

        $returnIds = array();

        if (is_array($data) && !empty($data)) {

            foreach ($data as $key => $value) {

                $check = $this->isMetaExists($userId, $key);
                if (!$check) {

                    $this->create();
                    $this->save(array(
                        'CloggyUserMeta' => array(
                            'user_id' => $userId,
                            'meta_key' => $key,
                            'meta_value' => $value
                        )
                    ));

                    $returnIds[$key] = $this->id;
                }
            }

            return $returnIds;
        }

        return false;
    }

}