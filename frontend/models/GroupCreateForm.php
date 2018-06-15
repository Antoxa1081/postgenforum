<?

namespace frontend\models;

use yii\base\Model;
use common\models\Group;

class GroupCreateForm extends Model {

    public $groupName;
    public $groupTitle;
    public $groupDescription;
    public $groupAvatar;
    public $groupIsClosed;

    public function rules() {
        return [
            ['groupName', 'string', 'min' => 3, 'max' => 128],
            ['groupDescription', 'string', 'min' => 3, 'max' => 1024],
            ['groupTitle', 'string', 'max' => 128],
            ['groupAvatar', 'integer'],
            ['groupIsClosed', 'boolean']
          //  ['groupName', 'unique', 'targetClass' => '\common\models\Group', 'message' => 'This name has already been taken.'],
        ];
    }

    public function attributeLabels() {
        return [
            'groupName' => 'Name',
            'groupTitle' => 'Title',
            'groupDescription' => 'Description',
            'groupAvatar' => 'Group avatar'
        ];
    }

    public function create($user) {
        $this->groupTitle = $this->groupTitle == null ? '' :$this->groupTitle;
        $this->groupDescription = $this->groupDescription == null ? '' : $this->groupDescription;
        $this->groupAvatar = $this->groupAvatar == null ? 0 : $this->groupAvatar;
        if (!$this->validate()) {
            return null;
        }
        return Group::createGroup(
                        $user, $this->groupName, $this->groupTitle, $this->groupDescription, $this->groupAvatar, $this->groupIsClosed
        );
    }

}
