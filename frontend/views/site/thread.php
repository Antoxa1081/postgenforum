<?php

//$threadId = Yii::$app->request->get()["id"];
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;
use common\models\File;
use common\Util;

$this->title = $threadV->title;
?>
<style>
    i{
        cursor: pointer;
    }
</style>
<!--Title-->
<p><?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Back to theme', ['/site/theme', 'themeId' => $threadV->themeId]) ?></p>
<? if ($threadV->groupId != 0) { ?>
    <p><?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Back to group', ['/site/group', 'groupId' => $threadV->groupId]) ?></p>
<? } ?>
<h1>

    <?= htmlspecialchars($this->title) ?> 
</h1>
<!--Написать-->
<? if (Yii::$app->user->isGuest) { ?>
    Please signup to write in this thread<? } else { ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#filterPanel"><i class="glyphicon glyphicon-pencil"></i> Write</a>
                <span class="pull-right panel-collapse-clickable collapsed" data-toggle="collapse" data-parent="#accordion" href="#filterPanel" aria-expanded="false">
                    <i class="glyphicon glyphicon-chevron-down"></i>
                </span>
            </h4>
        </div>
        <div id="filterPanel" class="panel-collapse panel-collapse collapse" aria-expanded="false" style="height: 0px;">
            <div class="panel-body">
                <?php
                $model = new \frontend\models\WriteNoteForm();
                $form = ActiveForm::begin(['id' => 'writenote-form',
                            'method' => 'POST',
                            'action' => '',
                            'enableAjaxValidation' => false,
                            'options' => ['autocomplete' => 'off', 'data-pjax' => true]]);
                ?>

                <?= $form->field($model, 'textToWrite')->textarea(['value' => '']) ?>


                <div class="form-group">
                    <?= Html::submitButton('Write!', ['class' => 'btn btn-primary', 'name' => 'writenote-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<? } ?>
<hr>

<!--Сам список-->
<div class="media-list">  
    <?
    foreach ($list as $note) {
        $user = User::findIdentity($note->authorId);
        ?>


        <div class="media">
            <div class="media-left">
                <div class="btn-group-vertical" role="group" aria-label="...">

                    <i onclick="rate(<?= $note->id ?>, true)" class="glyphicon glyphicon-triangle-top" aria-hidden="true"></i>
                    <center id="note-rate-<?= $note->id ?>"><?= $note->rating ?></center>
                    <i onclick="rate(<?= $note->id ?>, false)" class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></i>

                </div>
            </div>
            <div class="media-left media-top">
                <img class="media-object" height = "50" wight = "50" src="<?= File::getPhotoLink($user->photo); ?>" alt="Not Found">
            </div>
            <div class="media-body">
                <h4 class="media-heading">
                    <?= Html::a($user->username, ['/site/account', 'userId' => $user->id]) ?>
                    <?= date("d.m.Y H:i:s", strtotime($note->date)) ?>
                </h4>
                <?= Util::replaceBBCode(str_replace("  ", "&nbsp;&nbsp;", trim(htmlspecialchars($note->content)))) ?>
            </div>
            <hr>
        </div>

        <?
    }
    echo LinkPager::widget([
        'pagination' => $pages,
    ]);
    ?>
</div>


