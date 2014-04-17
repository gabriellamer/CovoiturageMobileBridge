<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$data = json_decode(utf8_encode(file_get_contents('php://input')));

if(isset($data->idUser) && !empty($data->idUser) &&
    isset($data->driver) &&
    isset($data->title) && !empty($data->title) &&
    isset($data->description) && !empty($data->description) &&
    isset($data->nbPlace) && !empty($data->nbPlace) &&
    isset($data->airConditionner) &&
    isset($data->heater)) {

    $idUser = sql_safe($data->idUser);
    $driver = sql_safe($data->driver);
    $title = sql_safe($data->title);
    $description = sql_safe($data->description);
    $nbPlace = sql_safe($data->nbPlace);
    $airConditionner = sql_safe($data->airConditionner);
    $heater = sql_safe($data->heater);

    if(!isset($data->idAd) && empty($data->idAd)) {
        $idAd = sql_safe($data->idAd);

        sql_open();

        $result = sql_query("
            INSERT INTO T_AD(F_ID_USER, F_DRIVER, F_TITLE, F_DESCRIPTION, F_NB_PLACE, F_AIR_CONDITIONNER, F_HEATER)
            VALUES(" . $idUser . ", " . $driver . ", '" . $title . "', '" . $description . "', " . $nbPlace . ", " . $airConditionner . ", " . $heater . ")
        ");

        $idAd = sql_insert_id();

        sql_close();

        if($result) {
            $response["success"] = 1;
            $response["message"] = $idAd;

            echo json_encode($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
        }
    } else {
        sql_open();

        $result = sql_query("
            UPDATE T_AD
            SET F_ID_USER = " . $idUser . ", F_DRIVER = " . $driver . ", F_TITLE = '" . $title . "', F_DESCRIPTION = '" . $description . "', F_NB_PLACE = " . $nbPlace . ", F_AIR_CONDITIONNER = " . $airConditionner . ", F_HEATER = " . $heater . "
            WHERE F_ID_AD = " . $idAd . "
        ");

        sql_close();

        if($result) {
            $response["success"] = 1;
            $response["message"] = $idAd;

            echo json_encode($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
        }
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
}