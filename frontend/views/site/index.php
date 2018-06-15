<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;
use common\models\Theme;
use common\Labels;

$this->title = "ϰ-forum";
?>
<? if (User::getLevel(Yii::$app->user->identity->type) >= 2) { ?>
    <div class="panel panel-info"> 
        <div class="panel-heading"> 
            <h4 class="panel-title"> 
                <a data-toggle="collapse" data-parent="#accordion" href="#filterPanel"><i class="glyphicon glyphicon-plus"></i> New theme</a> 
                <span class="pull-right panel-collapse-clickable collapsed" data-toggle="collapse" data-parent="#accordion" href="#filterPanel" aria-expanded="false"> 
                    <i class="glyphicon glyphicon-chevron-down"></i> 
                </span> 
            </h4> 
        </div> 
        <div id="filterPanel" class="panel-collapse panel-collapse collapse" aria-expanded="false" style="height: 0px;"> 
            <div class="panel-body"> 
                Сreate new theme here:<br>
                <?php $form = ActiveForm::begin(['id' => 'createtheme-form']); ?>

                <?= $form->field($model, 'title')->textInput() ?>
                <?= $form->field($model, 'description')->textArea() ?>


                <div class="form-group">
                    <?= Html::submitButton('Create!', ['class' => 'btn btn-primary', 'name' => 'createtheme-button']) ?>
                </div>

                <? ActiveForm::end(); ?>
            </div> 
        </div> 
    </div>
<? } ?>
<div class="row">
    <div class="col-md-9">
        <ul class="list-group">
            <?
            if (Yii::$app->db->createCommand("SELECT COUNT(*) FROM `themes`")->queryScalar() > 0) {
                foreach ($list as $themeS) {
                    if ((User::getLevel($themeS->level)) <= (User::getLevel(Yii::$app->user->identity->type))) {
                        $themeId = intval($themeS->id);
                        $themeTitle = $themeS->title;
                        $countThreads = Yii::$app->db->createCommand("SELECT COUNT(*) FROM `threads` WHERE `themeId`='$themeId'")->queryScalar();

                        echo Html::a('<li class="list-group-item">
                            <span class="badge">' . $countThreads . '</span><b>
                            ' . htmlspecialchars($themeTitle) . '</b> | ' . htmlspecialchars($themeS->description) . '
                        </li>', ['theme', 'themeId' => $themeId]);
                    }
                }
                echo LinkPager::widget([
                    'pagination' => $pages,
                ]);
            } else {
                echo "<h1>There are no topics</h1>";
            }
            ?>
        </ul>
    </div>
    <div class="col-md-3">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-info-sign"></i> Info
            </div>
            <div class="panel-body">
                <?=Labels::$Lang['LABEL_INFO_INDEX'] ?>
            </div> 
        </div>
    </div>

