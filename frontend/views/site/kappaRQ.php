<?

//include "uapi-loader.php";

echo Yii::$app->user->identity->username;
/*
  __do__
  uploadFile
  downloadFile
  dbExecute
  dbReturn
  __accessToken__
  __mode__
  arg
 */
switch ($_REQUEST['__mode__']) {
    case 0:
		
        break;
    case 1:
        $db = new db();
        $userData = $db->assoc("SELECT * FROM `users` WHERE `auth_key`='" . $_REQUEST['__accessToken__'] . "'");
        if ($userData->id != null) {
            $username = $userData->username;
            $type = $userData->type;
        }
        break;

    default:
        break;
}

$ans[$_REQUEST['__do__']] = call_user_func($_REQUEST['__do__'], $_REQUEST['arg']);
echo json_encode($ans);

function getFileLink($fileId) {
    return (new File((int) $fileId))->link;
}

function dbExecute($query) {
    $db = new db();
    $state = $db->tfraw($query);
    $db->close();
    return $state;
}

function dbReturnOne($query) {
    $db = new db();
    $answer = $db->assoc($query);
    $db->close();
    return $answer;
}

function dbReturnAll($query) {
    $db = new db();
    $answer = $db->q($query);
    $db->close();
    return $answer;
}

function getUserLevelNyType($type = null) {
    $arr = [
        'Admin' => 3,
        'Moderator' => 2,
        'RegUser' => 1
    ];
    if ($type != null) {
        return $arr[$type];
    }
}
