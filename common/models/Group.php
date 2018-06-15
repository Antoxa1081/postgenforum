<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Group extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%groups}}';
    }

    public static function findGroup($id) {
        return static::findOne(['id' => $id]);
    }

    public static function findGroups($ids) {
        foreach ($ids as $groupId) {
            $groups[] = Group::findGroup($groupId);
        }
        return $groups;
    }

    
    /**
     * Finds group by name
     *
     * @param string $groupName
     * @return static|null
     */
    public static function findGroupByName($groupName) {
        return static::findOne(['name' => $groupName]);
    }

    //----------------------------------------

    public static function allGroups() {
        return static::find()->all();
    }

    /**
     * Create the new group
     *
     * @param type $user
     * @param type $name
     * @param type $title
     * @param type $description
     * @param type $photo
     */
    public static function createGroup($user, $name, $title, $description, $photo, $isClosed) {
        $group = new Group();

        $group->owner = $user->id;
        $group->name = $name;
        $group->title = $title == null ? '' : $title;
        $group->description = $description == null ? '' : $description;
        $group->photo = $photo == null ? 0 : $photo;
        $group->closed_group = $isClosed;

        if ($group->save()) {
            $group->addUserToGroup($user, true);
            return $group;
        } else {
            return null;
        }
    }

    /**
     *
     * @param type $id
     */
    public static function removeGroupById($id) {
        Yii::$app->db->createCommand()->delete('groups', ['id' => $id])->execute();
        Yii::$app->db->createCommand()->delete('members', ['groupId' => $id])->execute();
    }

    /**
     *
     * @param type $name
     */
    public static function removeGroupByName($name) {
        $g = Group::findGroupByName($name);
        if ($g != null) {
            removeGroupById($g->id);
        }
    }

    //----------------------------------------

    public function genMembersCount($status) {
      return (new yii\db\Query())
                      ->from('members')
                      ->where(['groupId' => $this->id, 'status'=>$status])
                      ->count();
    }

    /**
     *
     * @return type
     */
    public function membersCount() {
        return $this->genMembersCount(true);
    }

    /**
     *
     * @return type
     */
    public function nearlyMembersCount() {
        return $this->genMembersCount(false);
    }

    public function containsMember($user, $status) {
        return 0 != ((new yii\db\Query())
                        ->from('members')
                        ->where(['groupId' => $this->id, 'status' => $status, 'userId' => $user->id])
                        ->count());
    }

    public function justContainsMember($user) {
        return 0 != ((new yii\db\Query())
                        ->from('members')
                        ->where(['groupId' => $this->id,
                            'userId' => $user->id])
                        ->count());
    }

    /**
     * Returns true if the user has submitted an application into group
     *
     * @param string $user
     * @return boolean
     */
    public function userApply($user) {
        return $this->containsMember($user, 0);
    }

    /**
     * Returns true if user in the group
     *
     * @param string $user
     * @return boolean
     */
    public function userInGroup($user) {
        return $this->containsMember($user, 1);
    }

    /**
     *
     * @return type
     */
    public function members() {
        foreach ((new yii\db\Query())
                ->from('members')
                ->select('userId')
                ->where(['groupId' => $this->id])
                ->all() as $userId) {
            $members[] = User::findIdentity($userId);
        }
        return $members;
    }


    /**
     *
     * @return type
     */
    public function genFirstMembers($limit, $status) {
        foreach ((new yii\db\Query())
                ->from('members')
                ->select('userId')
                ->where(['groupId' => $this->id, 'status'=>$status])
                ->limit($limit)
                ->all() as $userId) {
            $members[] = User::findIdentity($userId);
        }
        return $members;
    }

    public function firstRealMembers($limit){
      return $this->genFirstMembers($limit,true);
    }

    /**
     * Add user to group by id
     * @param type $userId
     * @param type $status
     * @return boolean
     */
    public function addUserToGroupById($userId, $status) {
        if ($this->justContainsMember($userId)) {
            return true;
        } else {
            return Yii::$app->db->createCommand()->insert('members', ['userId' => $userId, 'groupId' => $this->id, 'status' => $status])->execute();
        }
    }

    /**
     * Add user to group
     *
     * @param string $user
     * @return boolean
     */
    public function addUserToGroup($user, $status) {
        $this->addUserToGroupById($user->id, $status);
    }

    public function rightsideJoin($user) {
        Yii::$app->db->createCommand()->update('members', ['status' => 1])->where(['userId' => $user->id, 'groupId' => $this->id])->execute();
    }

    public function justAddUserToGroup($user) {
        $this->addUserToGroup($user, !$this->closed_group);
    }

    public function rightsideJoinById($userId) {
        Yii::$app->db->createCommand()->update('members', ['status' => 1])->where(['userId' => $userId, 'groupId' => $this->id])->execute();
    }

    public function justAddUserToGroupById($userId) {
        $this->addUserToGroupById($userId, !$this->closed_group);
    }

    /**
     * Remove user from group
     *
     * @param string $user
     * @return boolean
     */
    public function removeUserFromGroup($user) {
        return $this->removeUserFromGroupById($user->id);
    }

    public function removeUserFromGroupById($userId) {
        return Yii::$app->db->createCommand()
        ->delete('members', ['groupId' => $this->id, 'userId' => $userId])->execute();
    }

    /**
     * Returns all user groups ids
     */
    public static function userGroups($user) {
        return (new yii\db\Query())
                        ->from('members')
                        ->select('groupId')
                        ->where(['userId' => $user->id, 'status'=>true])
                        ->all();
    }

}
