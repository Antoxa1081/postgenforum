<?

use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use common\models\File;
use common\models\User;
use yii\helpers\Html;
?>
<?
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']])
?>
<div class="col-md-6">
    <?= $form->field($model, 'imageFile')->fileInput(['type' => 'file', 'class' => 'file', 'data-show-preview' => 'false']) ?>                            
    <button class="btn btn-info"><i class="glyphicon glyphicon-ok"></i></button>
</div>
<?
ActiveForm::end();
$userId = Yii::$app->user->identity->id;
?>
<div clas="row">

</div>
<div class="panel panel-default">
    <!-- Table -->
    <table class="table">
        <thead>
        <th>File name</th>
        <th></th>
        <th>Upload at</th>
        <th>Size</th>
        </thead>
        <tbody>
            <?
            foreach ($paginModel as $file) {
                $link = "http://forum.postgen.xyz/files/" . Yii::$app->user->identity->username . '/' . $file['name'];
                if (substr($file['type'], 0, 5) == "image") {
                    $lineContent = '<a  href="' . urldecode($link) . '"><img height="75px" src="' . urldecode($link) . '" /></a><br><a href="' . urldecode($link) . '">' . $file['name'] . "</a>";
                } else {
                    $lineContent = '<img height="50px" src="' . File::getFileIcon($file['type']) . '"/><a href="' . urldecode($link) . '">' . $file['name'] . "</a>";
                }
                ?>
                <tr id="file-<?= $file['id'] ?>">

                    <td><?= $lineContent; ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <? if (substr($file['type'], 0, 5) == "image") { ?>
                                    <li><a onclick="setAvatar(<?= $file['id'] ?>)" href="javascript:void(0);">Set Avatar</a></li>
                                    <li><a onclick="setBackground(<?= $file['id'] ?>)" href="javascript:void(0);">Set Background</a></li>
                                    <? if ($groupId != null) { ?><li><a onclick="setGroupPhoto(<?= $file['id'] ?>,<?= $groupId ?>)" href="javascript:void(0);">Set Group Photo</a></li><? } ?>
                                <? } ?>
                                <li role="separator" class="divider"></li>
                                <li><a class="button btn-danger" href="javascript:void(0);" onclick="deleteFileById(<?= $file['id'] ?>)">Delete</a></li>
                            </ul>
                        </div>
                    </td>
                    <td><?= date("d.m.Y H:i:s", strtotime($file['date'])) ?></td>
                    <td><?= number_format(($file['size'] / (1024 * 1024)), 2) . " mb" ?></td>
                    <td>
                        <!--                        <div class="btn-group" role="group">
                        <? if (substr($file['type'], 0, 5) == "image") { ?><button onclick="setAvatar(<?= $file['id'] ?>)" type="button" class="btn btn-default">Avatar</button>
                                                                                                                                                                                <button onclick="setBackground(<?= $file['id'] ?>)" type="button" class="btn btn-default">Background</button><? } ?>
                                                    <button onclick="deleteFileById(<?= $file['id']; ?>)" type="button" class="btn btn-danger">Delete</button>
                                                </div>-->
                        <!-- Single button -->

                    </td>
                </tr>
                <?
            }
            ?>
        </tbody>
    </table>

</div>
<?
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
             

