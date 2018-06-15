<?

namespace common\handler;

use Yii;
use common\models\User;

include "D:/server/domains/desu/YiiForum-3/common/handler_classes.php";

Class HandlerCore {

    public $userData;
    public $callback;
    public $definition;
    public $initArgs;
    public $requests;
    public $accessLevel;
    public $definedObject;
    private $authMethod;
    private $metaData;
    private $action;

    const AUTH_COOKIE = "cookie";
    const AUTH_ACCESS_TOKEN = "accessToken";
    const STATIC_TYPE = "static";
    const PROPERTY_TYPE = "property";
    const DEFAULT_TYPE = "default";
    const ACCESS_CLASS_LIST = [
        'HFile' => true,
        'HGroup' => true,
        'HUser' => true,
        'HNote' => true,
    ];
    const WHITE_METHOD_LIST = [
        [],
        []
    ];

    public function __construct($metaData = null) {
        $this->init($metaData);
    }

    public function init($metaData) {
        $this->metaData = $metaData;
        //print_r($this->metaData);
        $this->auth($metaData);
        if ($this->getActionFromMetaData()->definition != null) {
            $this->initDefinitionObject();
        }
        $requests = $this->extractRequests($this->action);
        //print_r($this->getActionFromMetaData());
        // print_r($this->getActionFromMetaData());
        $this->executeRequestsArray($requests);
    }

    public function auth($metaData) {
        if ($this->validate((object) $metaData)) {

            if ($metaData['authMethod'] == self::AUTH_COOKIE) {
                $this->userData = Yii::$app->user->identity;
                $this->accessLevel = User::getLevel(Yii::$app->user->identity->type);
                if ($this->accessLevel > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($metaData['authMethod'] == self::AUTH_ACCESS_TOKEN) {
                $this->userData = User::findIdentityByAccessToken($metaData['accessToken']);
                $this->accessLevel = User::getLevel(User::findIdentityByAccessToken($metaData['accessToken'])->type);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function validate($metaData) {
        //return (isset($metaData->authMethod) and isset($metaData->__action__)) ? true : false;
        if ($metaData->authMethod != null) {
            if ($metaData->authMethod == self::AUTH_ACCESS_TOKEN) {
                return (isset($metaData->accessToken) and $metaData->accessToken != null);
            } elseif ($metaData->authMethod == self::AUTH_COOKIE) {
                return ($_COOKIE != null);
            } else {

                return false;
            }
        } else {
            // echo 'defisl';
            return false;
        }
    }

    private function accessClass($className) {
        return self::ACCESS_CLASS_LIST[$className];
    }

    private function accessMethod($className, $methodName) {
        
    }

//    private function accessVariable($className){}


    public function executeRequestsArray($requests) {
        if ($requests != null) {
            foreach ($requests as $request) {
                if ($request->class == null) {
                    if ($this->accessClass($this->definition)) {
                        $this->executeDefinitionRequest($this->getDefineConfig(), $request);
                    }
                } else {
                    if ($this->accessClass($request->class)) {
                        $this->callback[$request->callback] = $this->executeOnceRequest($request);
                    } else {
                        $this->callback['status'] = 'access denied';
                    }
                }
            }
            return $this->getRequestsCallbackValues();
        } else {
            return null;
        }
    }

    public function executeOnceRequest($request) {
        $method = $request->method;
        $class = $request->class;
        if ($this->checkClassName($class) and $this->checkMethodName($class, $method)) {
            switch ($request->type) {
                case self::DEFAULT_TYPE:
                    $obj = new $class($request->initArgs);
                    $result = $obj->$method($request->args);
                    break;
                case self::STATIC_TYPE:
                    $result = $class::$method($request->args);
                    break;
                case self::PROPERTY_TYPE:
                    $result = $class->$method = $request->value; //add in builder value and variables types
                    break;
                default:
                    break;
            }
            $callback = $request->callback != null ? $result : null;
            return $callback;
        } else {
            $this->callback['__state__'] = "Error";
            return false;
        }
    }

    public function initDefinitionObject($defConf = null) {
        $defConfig = $defConf != null ? $defConf : $this->getDefineConfig();
        if ($this->checkClassName($defConfig->definition) and $this->accessClass($defConfig->definition)) {
            $this->definedObject = new $defConfig->definition($defConfig->initArgs);
            return true;
        } else {
            return false;
        }
    }

    public function executeDefinitionRequest($defConfig, $request) {
        $method = $request->method;
        $class = $defConfig->definition;
        if ($this->checkClassName($class) and $this->checkMethodName($class, $method)) {
            switch ($request->type) {
                case self::DEFAULT_TYPE:
                    if ($this->checkMethodName($defConfig->definition, $method)) {
                        $result = $this->definedObject->$method($request->args);
                    }
                    break;
                case self::STATIC_TYPE:
                    $result = $class::$method($request->args);
                    break;
                case self::PROPERTY_TYPE:
                    $this->definedObject->$method = $request->value; //add in builder value and variables types
                    break;
                default:
                    break;
            }

            $callback[$request->callback] = ($request->callback != null) ? $result : null;
            $this->callback[] = $callback;
            $result = null;
            return $callback;
        } else {
            return false;
        }
    }

    public function executeDefinitionRequests($defConfig, $requests) {
        if ($this->checkClassName($defConfig->definition)) {
            $obj = new $defConfig->definition($defConfig->initArgs);
            foreach ($requests as $request) {
                $method = $request->method;
                $class = $defConfig->definition;
                switch ($request->type) {
                    case self::DEFAULT_TYPE:
                        if ($this->checkMethodName($defConfig->definition, $method)) {
                            $result = $obj->$method($request->args);
                        }
                        break;
                    case self::STATIC_TYPE:
                        $result = $class::$method($request->args);
                        break;
                    case self::PROPERTY_TYPE:
                        $obj->$method = $request->value; //add in builder value and variables types
                        break;
                    default:
                        break;
                }

                $callback[$request->callback] = ($request->callback != null) ? $result : null;
                $this->callback[] = $callback;
                $result = null;
            }
            return $callback;
        } else {
            return false;
        }
    }

    public function getDefineConfig($action = null) {
        return $action != null ? (object) ['definition' => $action->definition, 'initArgs' => $action->initArgs] : (object) ['definition' => $this->definition, 'initArgs' => $this->initArgs];
    }

    private function checkClassName($className) {
        return class_exists($className);
    }

    private function checkMethodName($className, $methodName) {
        return method_exists($className, $methodName);
    }

    private function checkPropertyName($className, $propertyName) {
        return property_exists($className, $propertyName);
    }

    public function parseMetaDataAction($metaDataW = null) {
        $metaData = $metaDataW != null ? $metaDataW : $this->metaData;
    }

    public function addRequestCallbackValue($name, $value) {
        $this->callback[$name] = $value;
        return $this->callback;
    }

    public function getRequestsCallbackValues() {
        return $this->callback;
    }

    public function extractRequests($action) {
        return $action->requests;
    }

    public function getActionFromMetaData($metaData = null) {
        if ($metaData != null) {
            return json_decode($metaData->__action__);
        } else {
            //print_r($this->metaData["__action__"]);
            $this->action = json_decode($this->metaData["__action__"]);
            return $this->action;
        }
    }

    public function getMetaData() {
        return $this->metaData;
    }

    public function parseJsonCallback() {
        return json_encode($this->callback);
    }

}
