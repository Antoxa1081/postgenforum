<?

if (!isset($api_version)) {
    $time[0] = microtime(true);
    $listVer = scandir("D:/server/domains/API/versions/");
    $maxWeight = 0;
    for ($i = 2; $i < count($listVer); $i++) {
        //print('-> ' . $listVer[$i] . "\n");
        $arr = explode(".", explode("-", $listVer[$i])[1]);
        $weight = intval($arr[0] . $arr[1] . $arr[2]);
        if ($weight > $maxWeight) {
            $maxWeight = $weight;
            $api_version = $listVer[$i];
        }
    }
}

include "D:/server/domains/api/versions/$api_version";

