<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\File;
use common\models\User;
use common\Util;

AppAsset::register($this);

Yii::$app->db->createCommand("UPDATE `users` SET `last_seen`='" . time() . "' WHERE `id`='" . Yii::$app->user->identity->id . "'")->execute();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="handler.client.query.builder.js"></script>
        <script src="./assets/5ea95a74/jquery.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <?
    if (Yii::$app->user->identity->background != 0) {
        $link = File::getFileLink(Yii::$app->user->identity->background);
        $background = "url($link)";
    } else {
        $background = "white";
    }
    ?>
    <body style="background: <?= $background ?>; background-attachment: fixed; background-size: cover;">
        <?php $this->beginBody() ?>

        <div class="wrap">

            <?php
            NavBar::begin([
                'brandLabel' => '<img height = "23" src = "icon_black.png">',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-default navbar-fixed-top',
                ],
            ]);

            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = '<li><a href="?r=site/users">
        <i style="left: 7px;" class="glyphicon glyphicon-user" aria-hidden="true"></i><i class="glyphicon glyphicon-user" aria-hidden="true"></i>
    </a></li>';
                $menuItems[] = Util::iconLink('folder-open', '', ['/site/files']);
                $menuItems[] = Util::iconLink('comment', '', ['/site/groups']);
                $menuItems[] = Util::iconLink('user', Yii::$app->user->identity->username, ['/site/account', 'userId' => Yii::$app->user->identity->id]);
                //$menuItems[] = Util::iconLink('user', Yii::$app->user->identity->username, ['/site/account', 'userId' => Yii::$app->user->identity->id]);
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>

            <div class="container panel ">

                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        <!--
        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; ϰ-forum <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>
        -->
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
