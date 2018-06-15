<?

namespace frontend\models;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\db\ActiveRecord;
use Yii;

class UploadForm extends Model {

    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules() {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false],
        ];
    }
    
    public function attributes() {
        return [
            'imageFile' => 'load'
        ];
    }

    public function upload() {
        if ($this->validate()) {
            $username = Yii::$app->user->identity->username;
            $path = "D:/Server/domains/desu/YiiForum-3/frontend/web/files/$username/";
            if (!file_exists($path)) {
                mkdir("D:/Server/domains/desu/YiiForum-3/frontend/web/files/$username/");
            }
            $fileName = $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($path . $fileName);
            $size = filesize($path . $fileName);
            $md5 = md5_file($path . $fileName);
            $owner = Yii::$app->user->identity->id;
            $date = date("Y-m-d H:i:s");
            $type = mime_content_type($path . $fileName);
            Yii::$app->db->createCommand("INSERT INTO `files`(`owner`, `name`, `size`, `hash`, `date`, `type`) VALUES ('$owner','$fileName','$size','$md5','$date','$type')")->execute();
            return true;
        } else {
            return false;
        }
    }

}
