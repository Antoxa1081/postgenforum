<?

Class HandlerQueryBuilder {

    private $definition;
    private $initArgs;
    private $requests;

    const STATIC_TYPE = "static";
    const PROPERTY_TYPE = "property";
    const DEFAULT_TYPE = "default";
    
    const AUTH_COOKIE = "cookie";
    const AUTH_ACCESS_TOKEN = "accessToken";

    //public $build;

    public function __construct($__definition__ = null, $__requests__ = null, $__initArgs__ = null) {
        if ($__requests__ != null) {
            $this->build = $this->build($__definition__, $__requests__, $__initArgs__);
        }
    }

    public function build($__definition__ = null, $__requests__ = null, $__initArgs__ = null) {
        $definition = $__definition__ != null ? $__definition__ : $this->definition;
        $initArgs = $__initArgs__ != null ? $__initArgs__ : $this->initArgs;
        $requests = $__requests__ != null ? $__requests__ : $this->requests;
        return [
            "definition" => $definition,
            "initArgs" => $initArgs,
            "requests" => $requests
        ];
    }

    public static function buildRequest($method, $args = null, $callback = null, $type = null, $class = null, $initArgs = null) {
        return [
            "class" => $class,
            "type" => $type,
            "initArgs" => $initArgs,
            "method" => $method,
            "args" => $args,
            "callback" => $callback
        ];
    }

    public function addRequestToBuild($request) {
        $this->requests[] = $request;
    }

    public function setDefinition($definition) {
        $this->definition = $definition;
    }

    public function setInitArgs($args) {
        $this->initArgs = $args;
    }

    public function setRequests($arrayRequests) {
        $this->requests = $arrayRequests;
    }

    public function getDefinition() {
        return $this->definition;
    }

    public function getInitArgs() {
        return $this->initArgs;
    }

    public function getRequests() {
        return $this->requests;
    }

    public function flash() {
        foreach ($this as $key => $value) {
            $this->$key = null;
        }
    }

}

//
//$action = [
//    "definition" => "",
//    "initArgs" => [],
//    "requests" => [
//        "class" => "",
//        "initArgs" => [
//        ],
//        "method" => "",
//        "args" => [
//        ],
//        "callback" => ""
//    ],
//];