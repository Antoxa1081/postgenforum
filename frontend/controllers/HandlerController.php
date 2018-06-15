<?php

namespace frontend\controllers;

use Yii;
use app\models\Post;
use common\models\Group;
use common\models\User;
use yii\web\Controller;

class HandlerController extends Controller {

 /* public function actionExp(){
    return ( (Group::findGroup(1))->userApply(Yii::$app->user->identity) ? "true" : "false");
  }
*/
    /**
    * Leftside join to group
    *
    */
    public function actionJoin_to_group() {
        if (isset($_REQUEST['groupId'])) {
            $group = Group::findGroup(intval($_REQUEST['groupId']));
            $group->justAddUserToGroupById(Yii::$app->user->identity->id);
            Yii::$app->session->setFlash('success', 'You applied');
            return json_encode(['success'=>true]);
        } else{
            return json_encode(['success'=>false]);
        }
    }

    /**
    * Full remove user for group
    *
    */
    public function actionLeft_from_group() {
        if (isset($_REQUEST['groupId']) && isset($_REQUEST['userToRemove'])) {
            $group = Group::findGroup(intval($_REQUEST['groupId']));
            $thisUser = Yii::$app->user->identity;
            $userId = intval($_REQUEST['userToRemove']);
            $userToRemove = User::findIdentity($userId);

            if( $thisUser->id == $group->owner) {
              Group::removeGroupById($group->id);
              return json_encode(['success'=>true]);
            }
              elseif($thisUser->id == $userId){
                if($userToRemove != null){
                  $group->removeUserFromGroupById($userId);
                  return json_encode(['success'=>true]);
                }
            }
        }
        return json_encode(['success'=>false]);
    }

    public function actionRateNote(){
      if (isset($_REQUEST['groupId']) && isset($_REQUEST['userToRemove'])) {
          
          return json_encode(['success'=>true]);
      }
      return json_encode(['success'=>false]);
    }


}
