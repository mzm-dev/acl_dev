<?php

App::uses('AppController', 'Controller');

/**
 * User Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

/**
     * Update AROs
     * Get all Role/Group and User in the database
     */
    public function sync_aros($id) {
        //defind $aro
        $aro = $this->Acl->Aro;
        //find data user after save
        $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
        $user = $this->User->find('first', $options);
        $arr = array();
        foreach ($user['Group'] as $k => $val) {
            $arr[$k] = $aro->find('all', array('conditions' => array('model' => 'Group', 'foreign_key' => $val['id'])));
        }
        $aroList = array();
        foreach ($arr as $r => $v) {
            if (isset($v[0]['Aro']['id'])) {
                $aroList[$r] = array(
                    'alias' => 'NULL',
                    'parent_id' => $v[0]['Aro']['id'],
                    'model' => 'User',
                    'foreign_key' => $user['User']['id'],
                );
            }
        }
        foreach ($aroList as $data) {
            $aro->create();
            $aro->save($data);
        }
    }
