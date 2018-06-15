<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;
use common\models\Group;
use common\Util;
use common\Labels;
$this->title = "Groups";
?>

<div class="row">
   

    <div class="col-md-9">

        <? /* if (count($groupList) == 0) { ?>
          <h3> There are no groups </h3>
          <? } else { */ ?>
        <div class="list-group">
            <?
            foreach (Group::allGroups() as $gr) {
                echo Html::a('<button type="button" class="list-group-item">[' .
                        User::findIdentity($gr->owner)->username . ']' .
                        htmlspecialchars($gr->name) . '<span class="badge">' . $gr->membersCount() .
                        '</span></button>', ['/site/group', 'groupId' => $gr->id]);
            }
            ?>
        </div>
<? //}  ?>   
    </div>

    <div class="col-md-3">
        <!-- <h4>Params to search</h4>
        <? /* echo  $form->field($model, 'sortby')->radioList([
          1 => 'countOfThreads',
          2 => 'countOfMembers'
          ]); */ ?>
         <hr> -->
        <?= Html::tag("div", Html::a('Create a new group', ['/site/groupcreate']), ['class' => "panel"]) ?>
        <div class="panel panel-info">
        <div class="panel-heading">
          <i class="glyphicon glyphicon-info-sign"></i> Info
        </div>
        <div class="panel-body">
          <?=Util::replaceBBCode(Labels::LABELS_EN['LABEL_INFO_GROUPS']) ?>
        </div>
      </div>

        
    </div>
</div>