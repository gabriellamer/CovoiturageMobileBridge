<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$response = array();

if(isset($_POST['idAd']) && !empty($_POST['idAd'])) {

    $idAd = sql_safe($_POST['idAd']);

    sql_open();

    $result = sql_query("
        SELECT F_ID_USER, F_DRIVER, F_TITLE, F_DESCRIPTION, F_NB_PLACE, F_AIR_CONDITIONNER, F_HEATER
        FROM T_AD
        WHERE F_ID_AD = '$idAd'
    ");

    if(!$result) {
        sql_close();

        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    }

    while ($row = sql_fetch_array($result)) {
        $response["message"][] = $row;
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