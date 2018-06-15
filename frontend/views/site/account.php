<?

use common\models\User;
use common\models\File;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

//$userData = Yii::$app->user->identity;
$this->title = $userData->username . " account";
if ($userData->background != 0) {
    echo Html::script('document.body.style.backgroundImage="url(' . File::getFileLink($userData->background) . ')";');
}
$type = $userData->type;
?>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-5">
            <div class="btn-group-vertical" role="group" aria-label="...">
                <? if (Yii::$app->user->identity->id == $userData->id) { ?>
                    <?= Html::a('<div class="btn btn-default btn-group" role="group" aria-label="..."><i class="glyphicon glyphicon-pencil"></i></div>', ['/site/accountedit']) ?>
                    <div  onclick="$.post('index.php?r=site/logout', {}, function (result) {})" class="btn btn-default btn-group" role="group"><i  class="glyphicon glyphicon-log-out" style="cursor: pointer"></i></div>
                <? } ?>
            </div>

        </div>
        <div class="col-md-2">
            <a href="<?= File::getPhotoLink($userData->photo) ?>" class="thumbnail">
                <img src="<?= File::getPhotoLink($userData->photo) ?>" alt="...">
            </a>
            <center>
                <? if (User::checkOnline($userData->id)) { ?>
                    <span class="label label-success">Online</span>
                <? } else { ?>
                    <span class="label label-danger">Offline</span>
                    <br>
                    last seen at<br> <?= ($userData->last_seen != 0) ? date("d.m.Y H:i:s", $userData->last_seen) : date("d.m.Y H:i:s", $userData->updated_at) ?>
                <? } ?>
            </center>
        </div>
        <div class="col-md-5">

        </div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <center>
                <div class="panel">
                    <h3>
                        <b><?= $userData->username ?></b>
                        <br>
                        <?= ($type == "RegUser" ? "γ" : ($type == "Moderator" ? "β" : "α")) ?>
                        <br>
                    </h3>
                    <b><?= (int) Yii::$app->db->createCommand("SELECT SUM(rating) FROM `notes` WHERE `authorId`='$userData->id'")->queryScalar() ?> points</b>
                </div>
            </center>
        </div>
        <div class="col-md-4"></div>
    </div>
    <hr>
    <div class="row">

        <div class="col-md-6">
            <?
            if (Yii::$app->user->isGuest || (!Yii::$app->user->isGuest && $userData->id != Yii::$app->user->identity->id)) {
                $nameD = ($userData->name == "") ? "!!notDraw" : $userData->name;
                $surnameD = ($userData->surname == "") ? "!!notDraw" : $userData->surname;
                $birthdayD = ($userData->birthday == "0000-00-00") ? "!!notDraw" : $userData->birthday;
                $countryD = ($userData->country == "") ? "!!notDraw" : $userData->country;
                $cityD = ($userData->city == "") ? "!!notDraw" : $userData->city;
                $aboutMeD = ($userData->about_me == "") ? "!!notDraw" : $userData->about_me;
            } else {
                if ($userData->id == Yii::$app->user->identity->id) {
                    $nameD = ($userData->name == "") ? "!!notDraw" : $userData->name;
                    $surnameD = ($userData->surname == "") ? "!!notDraw" : $userData->surname;
                    $birthdayD = ($userData->birthday == "0000-00-00") ? "!!notDraw" : $userData->birthday;
                    $countryD = ($userData->country == "") ? "!!notDraw" : $userData->country;
                    $cityD = ($userData->city == "") ? "!!notDraw" : $userData->city;
                    $aboutMeD = ($userData->about_me == "") ? "!!notDraw" : $userData->about_me;
                }
            }

            /**
             * @param $dTitle
             * @param $d
             */
            function drawUserField($dTitle, $d) {
                if ($d != "!!notDraw") {
                    echo "<p>";
                    if ($d == "!!edit") {
                        echo Html::a('<button type"button" class="btn btn-info"><i class="glyphicon glyphicon-pencil"></i>' . $dTitle . '</button>', ['/site/accountedit']);
                    } else {
                        echo "<div class='panel'><b>" . $dTitle . ": </b>" . $d . '</div>';
                    }
                    echo "</p>";
                }
            }

            drawUserField(" Name", $nameD);
            drawUserField(" Surname", $surnameD);
            //     drawUserField(" Birthday", $birthdayD);
            drawUserField(" Country", $countryD);
            drawUserField(" City", $cityD);
            ?>

            <? drawUserField(" About me", nl2br($aboutMeD)); ?>
            <? if ($myGroups != null) { ?>
                <h4><b>My groups</b></h4>
                <div class="list-group">
                    <?
                    foreach ($myGroups as $gr) {
                        echo Html::a('<button type="button" class="list-group-item">[' .
                                User::findIdentity($gr->owner)->username . ']' .
                                htmlspecialchars($gr->name) . '<span class="badge">' . $gr->membersCount() .
                                '</span></button>', ['/site/group', 'groupId' => $gr->id]);
                    }
                    ?>
                </div>
            <? } ?>
        </div>

        <div class="col-md-6">
            <? if ($myThreads != null) { ?>
                <h4><b>My threads</b></h4>
                <div class="list-group">
                    <?
                    foreach ($myThreads as $th) {
                        $countNotes = (new yii\db\Query())->from('notes')->where(['threadId' => $th->id])->count();
                        echo Html::a('<button type="button" class="list-group-item">' .
                                htmlspecialchars($th->title)
                                . '<span class="badge">'
                                . $countNotes
                                . '</span>'
                                . '</button>', ['/site/thread', 'threadId' => $th->id]);
                    }
                    ?>
                </div>
            <? } ?>
        </div>
    </div>
    <!--   <div class="row">
            <div class="col-md-12">
                <h4>Notes</h4>
    <? ?>
            </div>
        </div> -->
</div>
