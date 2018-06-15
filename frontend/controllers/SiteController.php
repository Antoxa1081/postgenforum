<?php

namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\WriteNoteForm;
use frontend\models\CreateThemeForm;
use frontend\models\CreateThreadForm;
use frontend\models\EditProfileForm;
use frontend\models\DeleteFileForm;
use frontend\models\SearchGroupForm;
use frontend\models\GroupCreateForm;
use frontend\models\UploadForm;
use common\models\LoginForm;
use common\models\Note;
use common\models\Theme;
use common\models\Thread;
use common\models\User;
use common\models\Group;
use common\models\File;
use yii\data\Pagination;
use common\handler\HandlerCore;

/**
 * Site controller
 */
class SiteController extends Controller {

    const PAGE_SIZE = 24;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'editprofile'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex() {
        $rawList = Theme::find()->where(['status' => 'active']);
        //print_r($rawList);
        $countThemes = clone $rawList;
        $pages = new Pagination(['totalCount' => $countThemes->count()]);
        $list = $rawList->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        if (Yii::$app->user->isGuest) {
            return $this->render('index', ['list' => $list, 'pages' => $pages]);
        }
        $model = new CreateThemeForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->create(Yii::$app->user)) {
                Yii::$app->session->setFlash('success', 'The new theme ' . $model->title . ' is successfully created');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to create new topic');
            }
            return $this->refresh();
//            return $this->render('index', ['model' => $model, 'list' => $list, 'pages' => $pages]);
        } else {
            return $this->render('index', ['model' => $model, 'list' => $list, 'pages' => $pages]);
        }
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    public function actionAccount() {
        if (isset(Yii::$app->request->get()['userId'])) {
            $userV = User::findIdentity(intval(Yii::$app->request->get()['userId']));

            $myGroups = Group::findGroups(Group::userGroups($userV));
            $myThreads = Thread::find()->where(['owner' => $userV->id])->all();

            if ($userV == null) {
                Yii::$app->session->setFlash('error', 'There is no such user here');
                $this->goHome();
            } else {
                return $this->render('account', [
                            'userData' => $userV,
                            'myGroups' => $myGroups,
                            'myThreads' => $myThreads
                ]);
            }
        } else {
            return $this->goHome();
        }
    }

    public function actionThread() {
        $thread = Thread::findOneById(intval(Yii::$app->request->get()['threadId']));
        $rawList = Note::find()->where(['threadId' => $thread->id, 'themeId' => $thread->themeId]);
        $countList = clone $rawList;
        $pages = new Pagination(['totalCount' => $countList->count(), 'pageSize' => 50]);
        $list = $rawList->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        $model = new WriteNoteForm();
        if ($thread != null) {
            if (Yii::$app->user->isGuest) {
                return $this->render('thread', ['threadV' => $thread, 'list' => $list, 'pages' => $pages]);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->write(Yii::$app->user, $thread)) {
                    $this->refresh();
                    //$model = null;
                    //$model = new WriteNoteForm(); //reset model
                    //unset($_POST);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to write new message');
                }
            }
            return $this->render('thread', ['threadV' => $thread, 'model' => $model, 'list' => $list, 'pages' => $pages]);
        } else {
            return $this->goHome();
        }
    }

    public function actionTheme() {

        $theme = Theme::findOneById(intval(Yii::$app->request->get()['themeId']));
        $rawList = Thread::find()->where(['themeId' => Yii::$app->request->get()['themeId']]);
        $countList = clone $rawList;
        $pages = new Pagination(['totalCount' => $countList->count()]);
        $list = $rawList->offset($pages->offset)
                ->limit($pages->limit)
                ->all();
        $model = new CreateThreadForm();
        if ($theme != null) {
            if (Yii::$app->user->isGuest) {
                return $this->render('theme', ['themeV' => $theme, 'list' => $list, 'pages' => $pages]);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->create(Yii::$app->user, $theme, $userGroups)) {
                    Yii::$app->session->setFlash('success', 'The new theme ' . htmlspecialchars($model->threadTitle) . ' is successfully created');
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to create new topic');
                    return $this->render('theme', ['themeV' => $theme, 'model' => $model, 'list' => $list, 'pages' => $pages]);
                }
            } else {
                return $this->render('theme', ['themeV' => $theme, 'model' => $model, 'list' => $list, 'pages' => $pages]);
            }
        } else {
            return $this->goHome();
        }
    }

    public function actionFiles() {
        //File::deleteFileById(2);
        //echo "<pre>"; print_r(Yii::$app->request->post(), false);echo "</pre>";
        $model = new UploadForm();
        $del_model = new DeleteFileForm();
        $fileList = File::find()->where(['owner' => (Yii::$app->user->identity->id)]);
        //print_r($fileList);
        $countFiles = clone $fileList;
        $pages = new Pagination(['totalCount' => $countFiles->count()]);
        $paginModel = $fileList->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            //$fileId = Yii::$app->request->post()['delete_file_id'];
            if ($del_model->load(Yii::$app->request->post())) {
                if ($del_model->delete()) {
                    Yii::$app->session->setFlash('Success', 'Your file has been successfully deleted.');
                }
            }
            if ($model->upload()) {
                Yii::$app->session->setFlash('Success', 'Your file has been successfully uploaded.');
                if (Yii::$app->request->get()['q'] == 'selectPhoto') {
                    $userId = Yii::$app->user->identity->id;
                    $fileId = Yii::$app->db->createCommand("SELECT id FROM `files` WHERE `owner`='$userId' ORDER BY `id` DESC LIMIT 1")->queryOne()['id'];
                    Yii::$app->db->createCommand("UPDATE `users` SET `photo`='$fileId' WHERE `id`='$userId'")->execute();
                    return $this->redirect("http://forum.postgen.xyz/index.php?r=site%2Faccountedit");
                } elseif (Yii::$app->request->get()['q'] == 'selectBackground') {
                    $userId = Yii::$app->user->identity->id;
                    $fileId = Yii::$app->db->createCommand("SELECT id FROM `files` WHERE `owner`='$userId' ORDER BY `id` DESC LIMIT 1")->queryOne()['id'];
                    Yii::$app->db->createCommand("UPDATE `users` SET `background`='$fileId' WHERE `id`='$userId'")->execute();
                    return $this->redirect("http://forum.postgen.xyz/index.php?r=site%2Faccountedit");
                } elseif (Yii::$app->request->get()['q'] == 'selectGroupPhoto') {
                    if (Yii::$app->request->get()['id'] != null) {
                        $userId = Yii::$app->user->identity->id;
                        $groupId = Yii::$app->request->get()['id'];
                        $fileId = Yii::$app->db->createCommand("SELECT id FROM `files` WHERE `owner`='$userId' ORDER BY `id` DESC LIMIT 1")->queryOne()['id'];
                        Yii::$app->db->createCommand("UPDATE `groups` SET `photo`='$fileId' WHERE `id`='$groupId'")->execute();
                        if ($groupId != null) {
                            return $this->redirect("http://forum.postgen.xyz/index.php?r=site%2Fgroup&groupId=" . $groupId);
                        } else {
                            return $this->redirect("http://forum.postgen.xyz/index.php?r=site%2Fgroups");
                        }
                    }
                } else {
                    return $this->refresh();
                }
            }
        }

        //return $this->render('files', ['model' => $model, 'userData' => Yii::$app->user->identity, 'fileList' => $fileList, 'paginModel' => $paginModel, 'pages' => $pages]);

        if (Yii::$app->request->get()['q'] == 'selectGroupPhoto') {
            $groupId = Yii::$app->request->get()['id'];
        }
        return $this->render('files', ['model' => $model, 'userData' => Yii::$app->user->identity, 'fileList' => $fileList, 'paginModel' => $paginModel, 'pages' => $pages, 'groupId' => $groupId]);
    }

    public function actionFriends() {
        return $this->render('friends');
    }

    public function actionAccountedit() {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            $model = new EditProfileForm();
            if ($model->load(Yii::$app->request->post())) {
                if ($model->edit(Yii::$app->user->identity)) {
                    Yii::$app->session->setFlash('success', 'Your account has been successfully updated.');
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to create new topic');
                }
                return $this->render('account', ['userData' => Yii::$app->user->identity]);
            } else {
                return $this->render('accountedit');
            }
            return $this->render('accountedit');
        }
    }

    public function actionGroup() {

        $group = Group::findGroup(intval(Yii::$app->request->get()['groupId']));
        if ($group != null) {
            if ($group->userInGroup(Yii::$app->user->identity)) {
                return $this->render("group", ['gr' => $group]);
            } else {
                return $this->render("applytomember", ['gr' => $group, 'canJoin' => !$group->userApply(Yii::$app->user->identity)]);
            }
        }
        return $this->goHome();
    }

    public function actionGroupcreate() {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        } else {
            $model = new GroupCreateForm();
            if ($model->load(Yii::$app->request->post()) && ($group = $model->create(Yii::$app->user->identity)) != null) {
                Yii::$app->session->setFlash('success', 'Your new group is succefully created.');
                return $this->redirect('http://forum.postgen.xyz/?r=site/groups');
            } else {
                return $this->render('groupcreate', ['model' => $model]);
            }
        }
    }

    public function actionGroups() {

        return $this->render('groups', ['groupList' => Group::allGroups()]);
    }

    public function actionHandler() {
        $core = new HandlerCore(Yii::$app->request->post());
        // print_r($core);
        echo json_encode($core->callback);
    }

    public function actionExperimental() {
        return $this->render('experimental');
    }

    public function actionAdminpanel() {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        if (Yii::$app->user->identity->type == "Admin") {
            return $this->render('adminpanel');
        } else {
            return $this->goHome();
        }
    }

    public function actionGroupedit() {
        $group = Group::findGroup(Yii::$app->request->get()['id']);
        if ($group != null) {
            if (Yii::$app->user->identity->id == $group->owner) {
                return $this->render('group_edit', ['group' => $group]);
            } else {
                return $this->redirect('?r=site/group&groupId=' . $group->id);
            }
        } else {
            return $this->redirect('?r=site/groups');
        }
    }

    public function actionUsers() {
        $userList = Yii::$app->db->createCommand("SELECT id,type,username,photo FROM `users` WHERE `status`=10")->queryAll();
        //print_r($userList);
        return $this->render('users', ['list' => $userList]);
    }
    public function actionKapparq(){
        return $this->render('kappaRQ');
    }

//    public function actionExp(){
//        return $_REQUEST['a'];
//    }
}
// implement this class
// explode ("",$variable)
