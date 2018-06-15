<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\controllers;

use Yii;
use app\models\Post;
use common\models\Group;
use common\models\User;
use common\models\LoginForm;
use yii\web\Controller;

class OutsideController extends Controller {

    public function actionAuth() {
        if (isset($_REQUEST['isapp']) && $_REQUEST['isapp'] == "true") {
            if (isset($_REQUEST['login']) && isset($_REQUEST['pass'])) {
                $model = new LoginForm();
                $model->username = $_REQUEST['login'];
                $model->password = $_REQUEST['pass'];
                $model->rememberMe = false;

                if ($model->validate()) {
                    return json_encode($model->getUserInArrayForm());
                } else {
                    return "R#001";
                }
            } else if (isset($_REQUEST['auth_key'])) {
                $r = json_encode(Yii::$app->db->createCommand("SELECT * FROM `users` WHERE `auth_key` = '" . $_REQUEST['auth_key'] . "'")->queryOne());
                if ($r != "") {
                    return $r;
                } else {
                    return "R#001";
                }
            }
            return "R#002";
        } else {
            return "R#000";
        }
    }

    public function actionDb_request() {
        if (isset($_REQUEST['isapp']) && $_REQUEST['isapp'] == "true") {
            if (isset($_REQUEST['auth_key']) && isset($_REQUEST['req']) && isset($_REQUEST['req_type'])) {
                $authKey = $_REQUEST['auth_key'];
                $req = $_REQUEST['req'];
                $reqType = $_REQUEST['req_type'];

                $user = User::findIdentityByAccessToken($authKey);
                if ($user != null) {
                    if ($user->id != null) {
                        return json_encode(Yii::$app->db->createCommand($req)->$reqType())
                       /* .(isset($_REQUEST['info'])&&$_REQUEST['info']=='true'?'['.$authKey.' '.$reqType.' '.$req.']':'')*/;
                    }
                }
            }else{
                return "R#002";
            }
        } else {
            return "R#000";
        }
    }

}
