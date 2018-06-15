<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin([
            'id' => 'groupcreate-form',
            'method' => 'POST',
            'action' => '',
            'enableAjaxValidation' => false,
            'options' => ['autocomplete' => 'off', 'data-pjax' => true]
                ]
);
?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'groupName')->textinput() ?>
        <?= $form->field($model, 'groupTitle')->textinput() ?>
        <b>Group Avatar</b><br>
        <a href="?r=site/files&q=selectGroupPhoto&groupId=<??>">Select Photo</a>
        <?= $form->field($model, 'groupIsClosed')->checkbox(['value' => 1, 'label' => 'Closed']) ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($model, 'groupDescription')->textArea(['rows' => '8']) ?>
    </div>
</div>
<div class="form-group">
    <?= Html::submitButton('Create!', ['class' => 'btn btn-primary', 'name' => 'groupcreate-button']) ?>
</div>
<? ActiveForm::end(); ?>
