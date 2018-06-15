<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\models;

use yii\base\Model;

/**
 * Description of WriteNodeForm
 *
 * @author Тягун
 */
class WriteNoteForm extends Model {

    public $textToWrite;

    public function rules() {
        return [
            ['textToWrite', 'string', 'min' => 1, 'max' => 6000],
        ];
    }

    public function attributeLabels() {
        return [
            'textToWrite' => 'Message',
        ];
    }

    public function write($user, $thread) {
        if (!$this->validate()) {
            return null;
        }
        return $thread->writeMsg($user, $this->textToWrite);
    }

}
