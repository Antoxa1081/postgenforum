<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;
use common\models\Thread;
use common\models\Group;
use yii\widgets\LinkPager;

//$themeValue - тема
$this->title = $themeV->title;
//print_r($themeV);
$themeId = $themeV->id;
/* @var $this yii\web\View */
?>

<h1><?= Html::encode($themeV->title) ?></h1>
<h5><?= $themeV->description ?></h5>
<hr>
<? if (User::getLevel(Yii::$app->user->identity->type) >= 1) { ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#filterPanel"><i class="glyphicon glyphicon-plus"></i> New thread</a>
                <span class="pull-right panel-collapse-clickable collapsed" data-toggle="collapse" data-parent="#accordion" href="#filterPanel" aria-expanded="false">
                    <i class="glyphicon glyphicon-chevron-down"></i>
                </span>
            </h4>
        </div>
        <div id="filterPanel" class="panel-collapse panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body">
                Сreate new thread here:<br>
                <?php $form = ActiveForm::begin(['id' => 'createthread-form']); ?>

                <?= $form->field($model, 'threadTitle')->textInput() ?>
                <?= $form->field($model, 'firstMsg')->textArea() ?>
                <div class="form-group">
                    <?= Html::submitButton('Create!', ['class' => 'btn btn-primary', 'name' => 'createthread-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<? } ?>

<div class="row">

    <div class="col-md-9">
        <ul class="list-group">
            <?
            foreach ($list as $line) {
                $threadId = $line->id;
                $countNotes = (new yii\db\Query())->from('notes')->where(['themeId' => $themeId, 'threadId' => $threadId])->count();


                if ($line->groupId == 0) {
                    $toDraw = true;
                    $groupName = "";
                } else {
                    $gr = Group::findGroup($line->groupId);

                    if (($gr != null &&
                            $gr->userInGroup(Yii::$app->user->identity)) || (User::getLevel(Yii::$app->user->identity->type) >= 2)) {
                        $groupName = $gr->name;
                        $toDraw = true;
                    } else {
                        $groupName = '';
                        $toDraw = false;
                    }
                }

                if ($toDraw) {
                    echo Html::a('<li class="list-group-item">
                                <span class="badge">' . $countNotes . '</span>
                                ' . htmlspecialchars($groupName) . '/' . User::findIdentity($line->owner)->username . '/' . htmlspecialchars($line->title) . '
                            </li>', ['thread', 'threadId' => $threadId]);
                }
                ?>
                <?
            } echo LinkPager::widget([
                'pagination' => $pages,
            ]);
            ?>
        </ul>
    </div>

    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-info-sign"></i> Info
            </div>
            <div class="panel-body">
                Information for user
            </div>
        </div>

    </div>
