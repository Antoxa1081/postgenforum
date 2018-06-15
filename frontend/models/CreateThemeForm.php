<?

namespace frontend\models;

use yii\base\Model;
use common\models\Theme;

class CreateThemeForm extends Model {

    public $title;
    public $description;

    public function rules() {
        return [
            ['title', 'string', 'min' => 3, 'max' => 128],
            ['description', 'string', 'min' => 3, 'max' => 600]
        ];
    }

    public function attributeLabels() {
        return [
            'title' => 'Title',
            'description' => 'Description',
        ];
    }

    public function create($user) {
        if (!$this->validate()) {
            return null;
        }

        $theme = new Theme();
        $theme->title = $this->title;
        $theme->description = $this->description == null ? "" : $this->description;
        $theme->owner = $user->identity->id;
        $theme->level = Theme::LEVEL_GUEST;
        $theme->accessControl = Theme::ACCESS_DISABLED;
        if (str_replace(" ", "", trim($this->title)) != null and str_replace(" ", "", trim($this->description)) != null) {
            return $theme->save() ? $theme : null;
        }
    }

}
