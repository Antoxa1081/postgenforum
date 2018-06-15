<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use yii\db\ActiveRecord;

use Yii;
/**
 * Description of Note
 *
 * @author Тягун
 */
class Note extends ActiveRecord{

    public static function tableName(){
        return '{{%notes}}';
    }

    public function getId(){
        return $this->id;
    }

    public static function findOneById($id) {
        return static::findOne(['id' => $id]);
    }


    public function rateUp($userId){
      if(userRated($userId)){

      }
    }

    public function rateDown($userId){

    }

    /**
     * Returns -1, 0 or 1 by an obvious principle
     * @param type $userId
     */
    public function userRated($userId){

    }

}
