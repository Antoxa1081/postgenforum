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
<div class="row" id="content">
    <div class="col-md-6">
        <h2>Description <i class="glyphicon glyphicon-info-sign"></i></h2>
        <?= $gr->description==""?"No description":$gr->description ?>
    </div>

    <div class="col-md-3">
        <h2> <?= htmlspecialchars($gr->name )?> </h2>
        <h3> <?= htmlspecialchars($gr->title) ?> </h3>
    </div>

    <div class="col-md-3">
      <h2><?=$gr->closed_group?"Is closed group":"Is open group" ?></h2>
      <?  if (!Yii::$app->user->isGuest) { ?>
        <div id = "joinButtonPoint">
            <?if($canJoin){?>
              <button class="btn btn-info" id="join"> Join </button>
            <?}else{?>
              Wait for confirmation of the application from the owner of the group.
              Or cancel the application.
              <button class="btn btn-info" id="cancel">Cancel membership request</button>
            <?}?>
        </div>
    <?  }else{  ?>
        You should register in order to join the groups
    <?  } ?>
    </div>



</div>
<?  if (!Yii::$app->user->isGuest) { ?>
<script>

    $("#join").click((e)=>{
        $.post("index.php?r=handler/join_to_group", {groupId : <?=$gr->id?> } , (data)=>{
          //  $("#joinButtonPoint").html('Wait for confirmation of the application from the owner of the group. Or cancel the application.<button class="btn btn-info" id="cancel">Cancel membership request</button>');
          window.location.replace("http://forum.postgen.xyz/index.php?r=site%2Fgroups");
        });
    });

    $("#cancel").click((e)=>{
        $.post("index.php?r=handler/left_from_group",
         {
            groupId : <?=$gr->id?>,
            userToRemove : <?=Yii::$app->user->identity->id?>
          } , (data) => {
            //$("#joinButtonPoint").html('<button class="btn btn-info" id="join"> Join </button>');
            window.location.replace("http://forum.postgen.xyz/index.php?r=site%2Fgroups");
        });
    });
    
</script>
<?  } ?>
