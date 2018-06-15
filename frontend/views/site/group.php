<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Note;
use common\models\User;
use common\models\File;
use common\models\Thread;
use common\Util;

$this->title = $gr->name;
?>

<div class ="row">
    <div class="col-md-5">
        
    </div>
    <div class="col-md-2">
        <center>
            <a href="<?= File::getPhotoLink($gr->photo) ?>" class="thumbnail">
                <img src="<?= File::getPhotoLink($gr->photo) ?>" alt="...">
            </a>
            <h3> <?= htmlspecialchars($gr->name) ?> </h3>
            <h4> <?= htmlspecialchars($gr->title) ?> </h4>
        </center>
    </div>
    <div class="col-md-5">

    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-3">
        <!--Members-->
        <h4> Members
            <span class="label label-primary"><?= $gr->membersCount() ?>
            </span>
            <? if ($gr->closed_group && Yii::$app->user->identity->id == $gr->owner) { ?>
                <span class="label label-default"><?= $gr->nearlyMembersCount() ?></span>
            <? } ?>
        </h4>
        <div class="list-group">
            <? foreach ($gr->firstRealMembers(10) as $m) { ?>
                <?=
                Html::a('<button type="button" class="list-group-item">' . $m->username
                        . '</button>', ['/site/account', 'userId' => $m->id])
                ?>
            <? } ?>
        </div>
        <hr>
        <? if ($gr->description != "") { ?>
            <h4> Info </h4>
            <?= htmlspecialchars($gr->description) ?>
            <hr>
        <? } ?>
        <button  onclick='
                $.post("index.php?r=handler/left_from_group",
                        {
                            groupId: <?= $gr->id ?>,
                            userToRemove: <?= Yii::$app->user->identity->id ?>
                        }, (data) => {
                    window.location.replace("http://forum.postgen.xyz/index.php?r=site/groups");
                });
                 ' class="btn btn-default"><i class="glyphicon glyphicon-share-alt"></i> Leave group </button>
                 <? if (Yii::$app->user->identity->id == $gr->owner) { ?>
            <a href="?r=site/groupedit&id=<?=$gr->id?>"class="btn btn-primary"><i class="glyphicon glyphicon-cog"></i> </a>
        <? } ?>
    </div>

    <div class="col-md-9">

        <ul class="list-group">
            <?
            $list = Thread::findAllInGroup($gr);
            foreach ($list as $line) {
                $threadId = $line->id;
                $countNotes = (new yii\db\Query())->from('notes')->where(['threadId' => $threadId])->count();
                $fm = $line->firstMsg();
                $fm = 
                        Util::replaceBBCode(count($fm) == 1 ? '<div class="well well-sm">' . $fm[0]->content . '</div>' : '')
                        ;
                echo '<li class="list-group-item">'.Html::a(
                        user::findIdentity($line->owner)->username . '/' .
                        htmlspecialchars($line->title). '  <span class="badge">' . $countNotes . '</span>
                ',['thread', 'threadId' => $threadId] ) . '
                </li>' . $fm . '<br>';
                //Html::a($user->username, ['/site/account', 'userId' => $user->id])
                //date("d.m.Y H:i:s", strtotime($note->date))
            }
            ?>
        </ul>


       
    </div>
</div>
