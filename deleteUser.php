<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$response = array();

if(isset($_POST['idUser'])) {

    $idUser = sql_safe($_POST['idUser']);

    sql_open();

    $result = sql_query("
            SELECT F_ID_ADDRESS
            FROM T_USER
            WHERE F_ID_USER = '$idUser'
        ");

    if(!$result) {
        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    }

    while ($row = sql_fetch_array($result)) {
        $idAddress = $row[0];
    }

    $result = sql_query("
        DELETE FROM T_USER
        WHERE F_ID_USER = '$idUser'
    ");

    if(!$result) {
        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    }

    $result = sql_query("
        DELETE FROM T_ADDRESS
        WHERE F_ID_ADDRESS = '$idAddress'
    ");

    if(!$result) {
        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    } else {
        $reponse["success"] = 1;
        $response["message"] = "Suppression de l'utilisateur complété!";
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
}