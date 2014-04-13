<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$response = array();

if(isset($_POST['idAd']) && !empty($_POST['idAd'])) {

    $idAd = sql_safe($_POST['idAd']);

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
        $response["message"] = "Suppression de l'annonce complété!";
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
}