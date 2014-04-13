<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$response = array();

if(isset($_POST['idAd']) &&
    isset($_POST['idUser']) && !empty($_POST['idUser']) &&
    isset($_POST['driver']) &&
    isset($_POST['title']) && !empty($_POST['title']) &&
    isset($_POST['description']) && !empty($_POST['description']) &&
    isset($_POST['nbPlace']) && !empty($_POST['nbPlace']) &&
    isset($_POST['airConditionner']) &&
    isset($_POST['heater'])) {

    $idAd = sql_safe($_POST['idAd']);
    $idUser = sql_safe($_POST['idUser']);
    $driver = sql_safe($_POST['driver']);
    $title = sql_safe($_POST['title']);
    $description = sql_safe($_POST['description']);
    $nbPlace = sql_safe($_POST['nbPlace']);
    $airConditionner = sql_safe($_POST['airConditionner']);
    $heater = sql_safe($_POST['heater']);

    if(empty($idAd)) {
        sql_open();

        $result = sql_query("
            INSERT INTO T_AD(F_ID_USER, F_DRIVER, F_TITLE, F_DESCRIPTION, F_NB_PLACE, F_AIR_CONDITIONNER, F_HEATER)
            VALUES('$idUser', '$driver', '$title', '$description', '$nbPlace', '$airConditionner', '$heater')
        ");

        sql_close();

        if($result) {
            $response["success"] = 1;
            $response["message"] = "Création de l'annonce complété!";

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
            SET F_ID_USER = '$idUser', F_DRIVER = '$driver', F_TITLE = '$title', F_DESCRIPTION = '$description', F_NB_PLACE = '$nbPlace', F_AIR_CONDITIONNER = '$airConditionner', F_HEATER = '$heater'
            WHERE F_ID_AD = '$idAd'
        ");

        sql_close();

        if(result) {
            $response["success"] = 1;
            $response["message"] = "Mise à jour de l'annonce complété!";

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