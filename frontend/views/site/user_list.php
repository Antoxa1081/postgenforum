<?
    $this->title = $u_title;
?>

<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <? foreach($list as $user){?>
            <div class="media">
                <div class="media-left">
                 <?=Html::a('<img class="media-object" src="'.File::getPhotoLink($user->photo).'">',['site/account', ['userId'=>$user->id]])?>     
                </div>
                <div class="media-body">
                  <h4 class="media-heading"><?=Html::a($user->username, ['site/account', ['userId'=>$user->id]])?> </h4>
                </div>
            </div>
            <hr>
        <? } ?>  
    </div>
    <div class="col-md-3"></div>
</div>>



