<?

namespace common\models;

use yii\db\ActiveRecord;
use Yii;

Class Thread extends ActiveRecord {

    public static function tableName() {
        return '{{%threads}}';
    }

    public function getId() {
        return $this->id;
    }

    public static function findOneById($id) {
        return static::findOne(['id' => $id]);
    }

    public static function findAllInGroup($gr){
        return static::find()->where(['groupid'=>$gr->id])->all();
    }

    public static function findThreads($ids) {
        foreach ($ids as $thrId) {
            $thrs[] = Thread::findOneById($thrId);
        }
        return $thrs;
    }


    public function writeMsg($author, /* $attached, */ $content/* , $for */) {
        if ($author->isGuest) {
            return null;
        }
        if ($this->state == "Active") {
            $note = new Note();
            $note->authorId = $author->identity->id;
            $note->content = $content;
            $note->date = date("Y-m-d H:i:s");
            $note->themeId = $this->themeId;
            $note->threadId = $this->id;
            //anti-spam
            // if (Yii::$app->db->createCommand("SELECT content FROM notes WHERE (`authorId`='$note->authorId' AND `themeId`='$note->themeId' AND `threadId`='$note->threadId') ORDER BY id DESC")->queryOne()['content'] != $note->content) {
            if (str_replace(" ", "", trim($content)) != null) {
                return $note->save() ? $note : null;
            }
            //} else {
            // return null;
            // }
        }
        return null;
    }

    /**
     * Returns all user threads ids
     */
    public static function userThreads($user) {
        return (new yii\db\Query())
                        ->from('threads')
                        ->select('groupId')
                        ->where(['owner' => $user->id])
                        ->all();
    }


    public function firstMsg(){
        foreach((new yii\db\Query())
                        ->from('notes')
                        ->select('id')
                        ->where(['threadId' => $this->id])
                        ->limit(1)
                        ->all() as $f){
            $nt[] = Note::findOneById($f);
        }
        return $nt;
    }



}
