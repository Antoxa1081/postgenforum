<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

Class Theme extends ActiveRecord{

    
    
    const LEVEL_GUEST = 'Guest';
    const LEVEL_REGUSER = 'RegUser';
    const LEVEL_MODERATOR = 'Moderator';
    const LEVEL_ADMIN = 'Admin';
    
    const ACCESS_DISABLED = 'Disabled';
    const ACCESS_WHITELIST = 'EnabledWhiteList';
    const ACCESS_IGNORELIST = 'EnabledIgnoreList';
    
  
    
    /**
     * @inheritdoc
     */
    public static function tableName(){
        return 'themes';
    }
    
    /**
     * Id of this theme
     */
    public function getId(){
        return $this->id;
    }
    
    /**
     * Find and return Theme by id
     */
    public static function findOneById($id) {
        return static::findOne(['id' => $id]);
    }
    
    public static function getAll(){
        return static::find()->all();
    }
    
}