<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\File;
?>
<?php
$userdata = Yii::$app->user->identity;
$model = new \frontend\models\EditProfileForm();
$form = ActiveForm::begin(['id' => 'accountedit-form',
            'method' => 'POST',
            'action' => '',
            'enableAjaxValidation' => false,
            'options' => ['autocomplete' => 'off', 'data-pjax' => true]]);
?>

<div class="row"> 
    <div class="col-md-6">
        <?= $form->field($model, 'name')->textinput(['value' => $userdata->name]) ?>
        <?= $form->field($model, 'surname')->textinput(['value' => $userdata->surname]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'country')->textinput(['value' => $userdata->country]) ?>
        <?= $form->field($model, 'city')->textinput(['value' => $userdata->city]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <!-- <? /* DatePicker::widget([
          'model' => $model,
          'attribute' => 'fromdate',
          //'language' => 'ru',
          //'dateFormat' => 'yyyy-MM-dd',
          ]); */ ?> -->
        <?= $form->field($model, 'aboutme')->textarea(['value' => $userdata->about_me, 'rows' => '9']) ?>
    </div>
    <div class="col-md-6">
        <div class="col-md-6">
            <?= $form->field($model, 'photo')->textinput(['value' => $userdata->photo, 'type' => 'hidden']) ?>
            <?= Html::tag('a', Html::img(File::getPhotoLink($userdata->photo), ['style' => 'height: 170px']), ['class' => 'thumbnail', 'href' => 'http://forum.postgen.xyz/index.php?r=site/files&q=selectPhoto']) ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'background')->textinput(['value' => $userdata->background, 'type' => 'hidden']) ?>
            <?= Html::tag('a', Html::img(File::getPhotoLink($userdata->background), ['style' => 'height: 170px']), ['class' => 'thumbnail', 'href' => 'http://forum.postgen.xyz/index.php?r=site/files&q=selectBackground']) ?>

        </div>

    </div>
</div>
<div class="form-group">
    <?= Html::submitButton('Edit!', ['class' => 'btn btn-primary', 'name' => 'accountedit-button']) ?>
</div>
<?php ActiveForm::end(); ?>
