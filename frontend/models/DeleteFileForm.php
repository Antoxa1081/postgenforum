<?

namespace frontend\models;

use yii\base\Model;
use common\models\File;

Class DeleteFileForm extends Model {

    public $fileId;

    public function rules() {
        return [
            ['fileId', 'integer'],
        ];
    }

    public function delete() {
        File::deleteFileById($this->fileId);
    }

}
