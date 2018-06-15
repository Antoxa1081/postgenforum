<?

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\File;

$this->title = "Group Edit";
?>
<div class="col-md-12">
    <center>
        <h3>
            Edit Group
            <br>
            <hr>
        </h3>
    </center>
</div>
<div class="col-md-6">

    <b>Name</b>
    <input id="name" class="form-control" type="text" value="<?= $group->name ?>">
    <b><br>Description</b>
    <textarea id="description" class="form-control" rows="10"><?= $group->description ?></textarea>

</div>
<div class="col-md-6">

    <b>Title</b>
    <input id="title" class="form-control" type="text" value="<?= $group->title ?>">
    <b><br>Avatar</b>
    <a href="?r=site/files&q=selectGroupPhoto&id=<?= $group->id ?>"class="thumbnail">
        <img style="height: 200px"src="<?= File::getPhotoLink($group->photo) ?>">
    </a>
</div>
<div class="col-md-12"><br>
    <button onclick="editGroupInfo(<?= $group->id ?>)" class="btn btn-primary">Save</button>
</div>