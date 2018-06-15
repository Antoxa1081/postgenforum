<?

use common\models\User;
use common\models\File;
?><div>
    <?
    foreach ($list as $user) {
        ?>
        <div class="media">
            <div class="media-left">
                <a href="<?= "?r=site%2Faccount&userId=" . $user['id'] ?>">
                    <img class="media-object"  style="width: 64px; height: 64px;" src="<?= File::getPhotoLink($user['photo']) ?>" data-holder-rendered="true">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading">
                    <a href="<?= "?r=site%2Faccount&userId=" . $user['id'] ?>"><?= "" . $user['username'] ?></a>
                </h4>
                <? if (User::checkOnline($user['id'])) { ?>
                    <span class="label label-success">Online</span>
                <? } else { ?>
                    <span class="label label-danger">Offline</span>
                <? } ?>
                <b> <?= ($user['type'] == "RegUser" ? "γ" : ($user['type'] == "Moderator" ? "β" : "α")) ?></b>
            </div>
        </div>
        <hr>
    <? } ?>
</div>
