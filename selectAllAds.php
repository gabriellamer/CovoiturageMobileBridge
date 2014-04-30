<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$data = json_decode(utf8_encode(file_get_contents('php://input')));
//$data = json_decode('{"passenger":"0","maxDistance":"0","description":"","woman":"0","airConditionner":"0","heater":"0","idUser":"1","longitude":-72.5243292,"driver":"0","latitude":46.368885,"man":"0"}');


if(isset($data->idUser) && !empty($data->idUser) &&
   isset($data->description) &&
   isset($data->man) &&
   isset($data->woman) &&
   isset($data->driver) &&
   isset($data->passenger) &&
   isset($data->heater) &&
   isset($data->airConditionner) &&
   isset($data->maxDistance) &&
   isset($data->latitude) && !empty($data->latitude) &&
   isset($data->longitude) && !empty($data->longitude)) {

    $idUser = sql_safe($data->idUser);
    $description = sql_safe($data->description);
    $man = sql_safe($data->man);
    $woman = sql_safe($data->woman);
    $driver = sql_safe($data->driver);
    $passenger = sql_safe($data->passenger);
    $heater = sql_safe($data->heater);
    $airConditionnier =  sql_safe($data->airConditionner);
    $maxDistance = sql_safe($data->maxDistance);
    $latitude = sql_safe($data->latitude);
    $longitude = sql_safe($data->longitude);

    $query = "SELECT a.*, u.*, ad.*
              FROM T_AD a
              LEFT JOIN T_USER u ON a.F_ID_USER = u.F_ID_USER
              LEFT JOIN T_ADDRESS ad ON u.F_ID_ADDRESS = ad.F_ID_ADDRESS
              WHERE a.F_ID_USER != " . $idUser . " ";

    if($description != "") {
        $query .= "AND UPPER(a.F_DESCRIPTION) LIKE UPPER('%" . $description . "%') ";
    }

    if($man != $woman) {
        if($man) {
            $query .= "AND UPPER(u.F_SEX) = 'M' ";
        }

        if($woman) {
            $query .= "AND UPPER(u.F_SEX) = 'F' ";
        }
    }

    if($driver != $passenger) {
        if($driver) {
            $query .= "AND a.F_DRIVER = 1 ";
        }

        if($passenger) {
            $query .= "AND a.F_DRIVER = 0 ";
        }
    }

    if($heater) {
        $query .= "AND a.F_HEATER = 1 ";
    }

    if($airConditionnier) {
        $query .= "AND a.F_AIR_CONDITIONNER = 1 ";
    }

    sql_open();

    $result = sql_query($query);

    if(!$result) {
        sql_close();

        $response["success"] = 0;
//      $response["message"] = $query;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    }

    while ($row = sql_fetch_array($result)) {
        $distance = distance($latitude, $longitude, $row[25], $row[26]);

        //echo $maxDistance;
        //die();

        if($distance < $maxDistance) {
            $response["message"][] = $row;
        }
    }

    sql_close();

    if($result) {
        $response["success"] = 1;

        echo json_encode($response);
    } else {
        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
}

function distance($lat1, $lon1, $lat2, $lon2) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;

    return ($miles * 1.609344);
}
