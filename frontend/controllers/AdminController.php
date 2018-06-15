<?

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\{
    BadRequestHttpException,
    Controller,
    UploadedFile
};
use yii\filters\{
    VerbFilter,
    AccessControl
};
use frontend\models\{
    PasswordResetRequestForm,
    ResetPasswordForm,
    SignupForm,
    ContactForm,
    WriteNoteForm,
    CreateThemeForm,
    CreateThreadForm,
    EditProfileForm,
    DeleteFileForm,
    SearchGroupForm,
    GroupCreateForm,
    UploadForm
};
use common\models\{
    LoginForm,
    Note,
    Theme,
    Thread,
    User,
    Group,
    File
};
use yii\data\Pagination;
use common\handler\HandlerCore;

/**
 * Admin controller
 */
class AdminController extends Controller {
    /*

      название_метода(параметры, что передаются через $_REQUEST) return [параметры, что возвращаются (кидает строку полученную через json_encode)]
      -------------------
      //все delete_  и edit_ может быть кидают boolean-значение удачности исполнения метода

      delete_user(id)
      delete_theme(id) //удаляет также все треды и записи в них
      delete_thread(id) //удаляет также все записи в них
      delete_group(id) //само собой и всех членов выкидывает

      edit_user(id, всякое говно типо имени и т.д.)
      edit_theme(id, name, description)
      edit_group(id, всякое говно...)

      move_thread_totheme(id, toThemeId) //перемещает тред из одной темы в другую
      move_thread_togroup(id, toGroupId) //перемещает тред в группу


     */

    public function actionIndex() {
        $method = $_REQUEST['__action'];
        
//        $this->editUserById(7, [
//            'name'=>"pidor",
//        ]);
    }

    private function deleteUserById($id) {
        return $this->deleteCommon('users', 'id', $id);
    }

    private function deleteUserByLogin($login) {
        return $this->deleteCommon('users', 'username', $login);
        
    }

    private function deleteThemeById($id) {
        return $this->deleteCommon('themes', 'id', $id);
        
    }

    private function deleteThreadById($id) {
        return $this->deleteCommon('threads', 'id', $id);
        
    }

    private function deleteGroupById($id) {
        return $this->deleteCommon('groups', 'id', $id);
        
    }

    private function deleteNoteById($id) {
        return $this->deleteCommon('notes', 'id', $id);
        
    }

    private function editUserById($id, $objectData) {
        $primaryData = User::findIdentity($id);
        $newData = $this->compareObjects($primaryData, $objectData);
        foreach ($newData as $key => $value) {
            $primaryData->$key = $value;
        }
    }

    private function editUserByLogin($login, $objectData) {
        
    }

    private function editThemeById($id, $objectData) {
        
    }

    private function editThreadById($id, $objectData) {
        
    }

    private function editNoteById($id, $objectData) {
        
    }

    private function editGroupById($id, $objectData) {
        
    }

    private function compareObjects($primaryObject, $transObject) {
        foreach ($primaryObject as $key => $line) {
            if (property_exists($transObject, $key)) {
                $primaryObject->$key = $transObject->$key;
            }
        }
        return $primaryObject;
    }
    private function deleteCommon($table,$type,$arg){
        return (Yii::$app->db->createCommand("DELETE FROM `$table` WHERE `$type`='$arg'"));
    }

}
