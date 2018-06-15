<?php

namespace frontend\models;

use yii\base\Model;
use common\models\Thread;
use common\models\Group;
use Yii;

class CreateThreadForm extends Model {

    public $threadTitle;
    public $firstMsg;

    public function rules() {
        return [
            ['threadTitle', 'string', 'min' => 3, 'max' => 128],
            ['firstMsg', 'string', 'max' => 6000]
        ];
    }

    public function attributeLabels() {
        return [
            'threadTitle' => 'Title',
            'firstMsg' => 'Starting message'
        ];
    }

    public function create($user, $theme) {
        if (!$this->validate()) {
            return null;
        }

        $thread = new Thread();

        $titleParts = explode("@", $this->threadTitle, 2);
        if (count($titleParts) == 1) {
            $thread->title = $titleParts[0];
            $thread->groupId = 0;
        } else {
            $thread->title = $titleParts[1];
            $groupId = Group::findGroupByName($titleParts[0])->id;
            if(($g = Group::findGroup($groupId))!=null && $g->userInGroup(Yii::$app->user->identity)){
                $thread->groupId = $groupId;
            }else{
                return null;
            }
        }
        $thread->state = 'Active';
        $thread->owner = $user->identity->id;
        $thread->themeId = $theme->id;
        $thread->rating = 0;
        if (str_replace(" ", "", trim($this->threadTitle)) != null) {
            if ($thread->save()) {
                $thread->writeMsg($user, /* [], */ $this->firstMsg/* , [] */);
                return $thread;
            } else
                return null;
        }
    }

}
