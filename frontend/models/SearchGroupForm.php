<?php


namespace frontend\models;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\base\Model;
use common\Group;

class SearchGroupForm extends Model{
    
    public $searchpatern;
    
    public function rules() {
        return [
            ['sortby', 'integer'],
            ['searchpatern', 'string', 'max'=>90]
        ];
    }
    
    
    public function attributeLabels() {
        return [
            'sortby' => 'Sort by',
            'searchpatern' => '',
        ];
    }
    
    public function getGroups(){
        if($this->validate()){
            return Group::allGroups();
        }return null;
    }
    
}
