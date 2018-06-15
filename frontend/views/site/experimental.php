<?php

use yii\helpers\Html;

use common\models\User;
use common\models\Group;
$groups = Group::allGroups();

?>


<ul class="list group"></ul>
<?
foreach ($groups as $gr) {
    echo '<li class="list-group-item"><div id = "iii'.$gr->id.'">[' . User::findIdentity($gr->owner)->username . ']' .htmlspecialchars($gr->name) .
            '</div><button class="btn btn-info" id="item'.$gr->id.'" onclick="setEdit('.$gr->id.')">Edit</button>'.
            '<span class="badge">' . $gr->membersCount() . '</span></li>';
} 
?>

<script>
    function setEdit(el){
       // $("#iii"+el).text('<input id="ih'.el.'">');
       //prevValue = $("#iii"+el).text();
       var prevValue = "ff";
       alert(prevValue);
       $("#iii"+el).empty().html('<input id="ih'+el+'" value="'+prevValue+'">');
       $("#item"+el)empty().html('<button class="btn btn-primary">');
//       .click( () -> {
//           // ^(\n(\w)*)+
//           // (\n(\w)*)+$
//       });
    }
    
    var nextId = 0;
    
//    $("#pey").click( e =>{
//        $("#content").append( '<button class="btn btn-primary" onclick="hn(\'d'+nextId+'\')" id="d'+nextId+'">'+$("#input1").val()+'</button>' );
//        nextId++;
//    });
   
</script>