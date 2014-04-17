<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$data = json_decode(utf8_encode(file_get_contents('php://input')));

if(isset($data->idUser) && !empty($data->idUser)) {

    $idUser = sql_safe($data->idUser);

    sql_open();

    $result = sql_query("
            SELECT F_ID_ADDRESS
            FROM T_USER
            WHERE F_ID_USER = " . $idUser . "
        ");

    if(!$result) {
        sql_close();

        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    }

    $idAddress = null;

    while ($row = sql_fetch_array($result)) {
        $idAddress = $row[0];
    }

    $result = sql_query("
        DELETE FROM T_USER
        WHERE F_ID_USER = " . $idUser . "
    ");

    if(!$result) {
        sql_close();

        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    }

    $result = sql_query("
        DELETE FROM T_ADDRESS
        WHERE F_ID_ADDRESS = " . $idAddress . "
    ");

    sql_close();

    if(!$result) {
        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    } else {
        $reponse["success"] = 1;
        $response["message"] = $idUser;
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
}