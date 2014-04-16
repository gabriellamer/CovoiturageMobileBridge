<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$data = json_decode(utf8_encode(file_get_contents('php://input')));

if(isset($data->idAd) && !empty($data->idAd)) {

    $idAd = sql_safe($data->idAd);

    sql_open();

    $result = sql_query("
        SELECT *
        FROM T_AD ad
        LEFT JOIN T_USER u ON ad.F_ID_USER = u.F_ID_USER
        LEFT JOIN T_ADDRESS a u.F_ID_ADDRESS = a.F_ID_ADDRESS
        WHERE ad.F_ID_AD = '$idAd'
    ");

    if(!$result) {
        sql_close();

        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
    }

    if(mysqli_num_rows($result) != 1) {
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