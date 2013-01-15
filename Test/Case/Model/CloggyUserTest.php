<?php

App::uses('CloggyAppModel', 'Cloggy.Model');
App::uses('CloggyUser', 'Cloggy.Model');

class CloggyUserTest extends CakeTestCase {

    public $fixtures = array(
        'plugin.cloggy.cloggy_user',
        'plugin.cloggy.cloggy_user_login',
        'plugin.cloggy.cloggy_user_meta'
    );
    private $__CloggyUser;

    public function setUp() {
        parent::setUp();
        $this->__CloggyUser = ClassRegistry::init('CloggyUser');
        $this->__CloggyUser->cacheQueries = false;
    }

    public function testNodeObjects() {
        $this->assertFalse(empty($this->__CloggyUser));
        $this->assertTrue(is_a($this->__CloggyUser, 'CloggyUser'));
    }

    public function testUserExists() {

        $check = $this->__CloggyUser->isUserNameExists('test user');
        $checkFake = $this->__CloggyUser->isUserNameExists('test user fake');

        $this->assertTrue($check);
        $this->assertFalse($checkFake);
    }

    public function testEmailExists() {

        $check = $this->__CloggyUser->isUserEmailExists('test@test.com');
        $checkFake = $this->__CloggyUser->isUserEmailExists('test2@test.com');

        $this->assertTrue($check);
        $this->assertFalse($checkFake);
    }

    public function testSetUserLogin() {

        $this->__CloggyUser->setUserLastLogin(1);
        $data = $this->__CloggyUser->getUserLastLogin(1);

        $this->assertFalse(empty($data));
        $this->assertTrue($data['CloggyUser']['user_last_login'] < date('c'));
    }

    public function testUserDetail() {

        $data = $this->__CloggyUser->getUserDetail(1);

        $this->assertFalse(empty($data));
        $this->assertEqual($data['CloggyUser']['user_name'], 'test user');
    }

    public function testUserStatus() {

        $data = $this->__CloggyUser->getUserStatus(1);

        $this->assertFalse(empty($data));
        $this->assertEqual($data['CloggyUser']['user_status'], 1);
    }

    public function testUserRole() {

        $data = $this->__CloggyUser->getUserRole(1);

        $this->assertFalse(empty($data));
        $this->assertEqual($data['CloggyUser']['user_role'], 'test role');
    }

    public function testRemoveUser() {

        $this->__CloggyUser->delete(1, false);
        $data = $this->__CloggyUser->getUserDetail(1);

        $this->assertTrue(empty($data));
    }

}