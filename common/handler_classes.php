<?

use common\models\Group;
use common\models\File;

Class HFile {

    //private $userData;
    public function __construct($args = null) {
        //print_r($args);
    }

    public static function load($args) {
        if ((new Auth)->level > 0) {
            $relType = $args->type;
            return Yii::$app->db->createCommand("SELECT * FROM `files` WHERE `$args->type`='" . $args->$relType . "'")->queryOne();
        }
    }

    public static function delete($args) {
        $relType = $args->type;
        $data = self::load($args);
        if ($data['id'] != null) {
            if ((new Auth)->id == $data->owner or ( new Auth)->getUserLevel((new Auth)->type) >= 3) {
                $filename = $data['name'];
                $username = (new HUser($data['owner']))->username;
                $link = "D:/server/domains/desu/YiiForum-3/frontend/web/files/$username/$filename";
                if (file_exists($link) and is_file($link)) {
                    if (unlink($link)) {
                        if (Yii::$app->db->createCommand("DELETE FROM `files` WHERE `id`='" . $data['id'] . "'")->execute() and Yii::$app->db->createCommand("DELETE FROM `files` WHERE `name`='" . $data['name'] . "'")->execute()) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
                //echo $link;
            }
        } else {
            return false;
        }
    }

    public function clear() {
        foreach ($this as $key => $value) {
            $this->$key = null;
        }
    }

}

Class Auth {

    public $__authMethod;
    public $__accessToken;

    const USER_LEVELS = [
        'Admin' => 3,
        'Moderator' => 2,
        'RegUser' => 1,
        'Guest' => 0
    ];

    public function __construct() {
        $this->init();
        $this->load();
        $this->level = self::getUserLevel($this->type);
    }

    public function init() {
        $this->__authMethod = $_POST['authMethod'];
        $this->__accessToken = $_POST['accessToken'];
    }

    public function load() {
        if ($this->__authMethod == "accessToken") {
            $userData = Yii::$app->db->createCommand("SELECT username,type FROM `users` WHERE `auth_key`='$this->__accessToken'")->queryOne();
        } elseif ($this->__authMethod == "cookie") {
            $userData = Yii::$app->user->identity;
        }
        foreach ($userData as $key => $value) {
            $this->$key = $value;
        }
        return $userData;
    }

    public static function getUserLevel($level) {
        return self::USER_LEVELS[$level];
    }

    public function clear() {
        foreach ($this as $key => $value) {
            $this->$key = null;
        }
    }

}

Class HUser {

    public function __construct($id = null) {
        if ($id != null) {
            $this->loadById($id);
        }
    }

    public function loadById($id) {
        $this->parse(Yii::$app->db->createCommand("SELECT * FROM `users` WHERE `id`='$id'")->queryOne());
    }

    public function loadByName($name) {
        $this->parse(Yii::$app->db->createCommand("SELECT * FROM `users` WHERE `username`='$name'")->queryOne());
    }

    public function getUserFiles($id = null) {
        $userId = $id != null ? $id : $this->id;
        $fileList = Yii::$app->db->createCommand("SELECT `id` FROM `files` WHERE `owner`='$userId'")->queryAll();
        return $fileList;
    }

    private function parse($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function setPhoto($args) {
        $userId = Yii::$app->user->identity->id;
        if ($args->id != Yii::$app->user->identity->photo) {
            if (Yii::$app->db->createCommand("UPDATE `users` SET `photo`='$args->id' WHERE `id`='$userId'")->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function setBackground($args) {
        $userId = Yii::$app->user->identity->id;
        if ($args->id != Yii::$app->user->identity->background) {
            if (Yii::$app->db->createCommand("UPDATE `users` SET `background`='$args->id' WHERE `id`='$userId'")->execute()) {
                return ['state' => true, 'url' => File::getFileLink($args->id)];
            } else {
                return ['state' => false];
            }
        } else {
            return ['state' => false];
        }
    }

    public function clear() {
        foreach ($this as $key => $value) {
            $this->$key = null;
        }
    }

}

Class HGroup {

    public function setPhoto($args) {
        $group = Group::findOne(['id' => $args->group]);
        $groupId = $args->group;
        $fileId = $args->fileId;
        if (Yii::$app->user->identity->id == $group->owner) {
            return (Yii::$app->db->createCommand("UPDATE `groups` SET `photo`='$fileId' WHERE `id`='$groupId'")->execute());
        } else {
            return false;
        }
    }

    public function edit($args) {
        if (Group::findOne(['id' => $args->group])->owner == Yii::$app->user->identity->id) {
            return (Yii::$app->db->createCommand("UPDATE groups SET name='$args->name', title='$args->title',description='$args->description' WHERE `id`='$args->group'")->execute());
        } else {
            return false;
        }
    }

}

Class Thread {
    
}

Class Theme {
    
}

Class Message {
    
}

Class HNote {

    public function rate($args) {
        $userId = Yii::$app->user->identity->id;
        if ($args->rate) {
            if (Yii::$app->db->createCommand("SELECT COUNT(*) FROM `rate` WHERE (`userId`='$userId' AND `noteId`='$args->noteId')")->queryScalar() == 0) {
                return ((Yii::$app->db->createCommand("INSERT INTO `rate`(`noteId`, `userId`) VALUES ('$args->noteId','$userId')")->execute()) && (Yii::$app->db->createCommand("UPDATE `notes` SET `rating`=`rating`+1 WHERE `id`='$args->noteId'")->execute()));
            } else {
                return false;
            }
        } else {
            if (Yii::$app->db->createCommand("SELECT COUNT(*) FROM `rate` WHERE (`userId`='$userId' AND `noteId`='$args->noteId')")->queryScalar() == 0) {
                return ((Yii::$app->db->createCommand("INSERT INTO `rate`(`noteId`, `userId`) VALUES ('$args->noteId','$userId')")->execute()) && (Yii::$app->db->createCommand("UPDATE `notes` SET `rating`=`rating`-1 WHERE `id`='$args->noteId'")->execute()));
            } else {
                return false;
            }
        }
    }

}
