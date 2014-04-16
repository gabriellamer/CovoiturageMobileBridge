<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$data = json_decode(utf8_encode(file_get_contents('php://input')));

if(isset($data->idAd) && !empty($data->idAd)) {

    $idAd = sql_safe($data->idAd);

    sql_open();

    $result = sql_query("
        DELETE FROM T_AD
        WHERE F_ID_AD = '$idAd'
    ");

    sql_close();

    if(!$result) {
        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    } else {
        $reponse["success"] = 1;
        $response["message"] = $idAd;
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
}