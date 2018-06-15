<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\models;

use yii\base\Model;

/**
 * Description of EditUserForm
 *
 * @author Тягун
 */
class EditProfileForm extends Model {

    public $name;
    public $surname;
    public $country;
    public $city;
    public $aboutme;
    public $photo;
    public $background;

    //public $fromdate;

    public function rules() {
        return [
            ['name', 'string', 'min' => 0, 'max' => 128],
            ['surname', 'string', 'min' => 0, 'max' => 128],
            ['country', 'string', 'min' => 0, 'max' => 128],
            ['city', 'string', 'min' => 0, 'max' => 128],
            ['aboutme', 'string', 'min' => 0, 'max' => 6000],
            ['photo', 'integer'],
            ['background', 'integer'],
                //      [['fromdate'], 'default', 'value' => null],
        ];
    }

    public function attributeLabels() {
        return [
            'name' => 'First name',
            'surname' => 'Second name',
            'country' => 'Country',
            'city' => 'City',
            'aboutme' => 'About me',
                //      'fromdate' => 'Birthday',
        ];
    }

    public function edit($user) {
        if (!$this->validate()) {
            return null;
        }
        $user->name = htmlspecialchars($this->name);
        $user->surname = htmlspecialchars($this->surname);
        $user->about_me = htmlspecialchars($this->aboutme);
        $user->city = htmlspecialchars($this->city);
        $user->country = htmlspecialchars($this->country);
        $user->photo = intval(trim($this->photo));
        $user->background = intval(trim($this->background));
        return $user->save() ? $user : null;
    }

}
