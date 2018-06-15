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
 * Description of file
 *
 * @author anton
 */
class File extends ActiveRecord {

    const pathToIcons = "http://forum.postgen.xyz/icons/filesicons/png/";
    const fileMathes = [
        'text/x-php' => 'php',
        'text/html' => 'html',
        'application/zip' => 'archive',
        'application/gzip' => 'archive',
        'application/x-7z-compressed' => 'archive',
        'application/x-rar' => 'archive',
        'application/pdf' => 'acrobat',
        'application/javascript' => 'js',
        'audio/vnd.wave' => 'waw',
        'text/plain' => 'txt',
        'application/msword' => 'word',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'word',
        'application/vnd.ms-powerpoint' => 'powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'powerpoint',
        'application/vnd.ms-excel' => 'excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'excel',
        'application/x-dosexec' => 'exe',
        'application/x-msi' => 'exe',
        'application/java-archive' => 'jar',
        'application/java' => 'java',
        'application/x-iso9660-image' => 'iso',
        'application/x-bittorrent' => 'torrent',
        'unknown' => 'else',
        '' => '',
    ];

    public static function tableName() {
        return '{{%files}}';
    }

    public function getId() {
        return $this->id;
    }

    public static function findOneById($id) {
        return static::findOne(['id' => $id]);
    }

    public static function getFileLink($id) {
        $data = self::findOneById($id);
        $username = User::findIdentity($data->owner)->username;
        $filename = $data->name;
        return "http://forum.postgen.xyz/files/$username/$filename";
    }

    public static function getPhotoLink($id) {
        if ($id != null) {
            $data = self::findOneById($id);
            $username = User::findIdentity($data->owner)->username;
            $filename = $data->name;
            if ($filename != null and file_exists("D:/server/domains/desu/YiiForum-3/frontend/web/files/" . $username . "/" . $filename) and substr(mime_content_type("D:/server/domains/desu/YiiForum-3/frontend/web/files/" . $username . "/" . $filename), 0, 5) == "image") {
                return "http://forum.postgen.xyz/files/$username/$filename";
            } else {
                return "http://forum.postgen.xyz/default-avatar.png";
            }
        } else {
            return "http://forum.postgen.xyz/default-avatar.png";
        }
    }

    public static function getFileIcon($type) {
        if (self::fileMathes[$type] != null) {
            return self::pathToIcons . self::fileMathes[$type] . ".png";
        } else {
            return self::pathToIcons . self::fileMathes["unknown"] . ".png";
        }
    }

    public static function deleteFileById($id) {
        if ($id != null) {
            $data = self::findOneById($id);
            if ($data != null) {
                $userData = User::findIdentity($data->owner);
                $path = "D:/server/domains/desu/YiiForum-3/frontend/web/files/" . $userData->username . "/" . $data->name;
                echo $path;
                if (file_exists($path)) {
                    if (unlink($path)) {
                        return (Yii::$app->db->createCommand()->delete('files', ['id' => $id])->execute());
                    }
                }
            }
        }
    }

}
