<?

include './handler_server_core.php';

$core = new HandlerCore($_POST);

print_r($core->callback);