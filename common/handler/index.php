<pre>
<?

include 'handler.query.server.builder.php';

$action = new HandlerQueryBuilder(); //Action::buildRequest("getDataFileById", ['id' => 5], "fileData")
$action->addRequestToBuild(HandlerQueryBuilder::buildRequest("load", [
            'type' => 'id',
            'id' => 7
                ], "data"));
//$action->addRequestToBuild(HandlerQueryBuilder::buildRequest("delete", [
//            'type' => 'id',
//            'id' => 7
//                ], "deletedData"));

//$action->setDefinition("HFile");
$buildAction = $action->build("HFile");
print_r($buildAction);
echo json_encode($buildAction); // JSON


function pushPostReq($url, $jsonData){
    $postData['jsonData'] = $jsonData;
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    
    $response = curl_exec($ch);
    echo $response;
}

pushPostReq('http://forum.postgen.xyz/', json_encode($buildAction));

/*
Делаем POST запрос к core.php
 * 
 * Fields:
    authMethod=accessToken||cookie
 *  accessToken=<token>
 *  __action__ = <JSON>
 *  
  */

?>
