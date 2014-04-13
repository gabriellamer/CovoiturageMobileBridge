<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$response = array();

if(isset($_POST['idUser']) &&
    isset($_POST['lastname']) && !empty($_POST['lastname']) &&
    isset($_POST['name']) && !empty($_POST['name']) &&
    isset($_POST['username']) && !empty($_POST['username']) &&
    isset($_POST['password']) && !empty($_POST['password']) &&
    isset($_POST['phone']) && !empty($_POST['phone']) &&
    isset($_POST['email']) && !empty($_POST['email']) &&
    isset($_POST['sexe']) && !empty($_POST['sexe']) &&
    isset($_POST['age']) && !empty($_POST['age']) &&
    isset($_POST['streetNb']) && !empty($_POST['streetNb']) &&
    isset($_POST['streetName']) && !empty($_POST['streetName']) &&
    isset($_POST['appNb']) &&
    isset($_POST['city']) && !empty($_POST['city']) &&
    isset($_POST['province']) && !empty($_POST['province']) &&
    isset($_POST['postCode']) && !empty($_POST['postCode'])) {

    $idUser = sql_safe($_POST['idUser']);
    $lastname = sql_safe($_POST['lastname']);
    $name = sql_safe($_POST['name']);
    $username = sql_safe($_POST['username']);
    $password = sql_safe($_POST['password']);
    $phone = sql_safe($_POST['phone']);
    $email = sql_safe($_POST['email']);
    $sexe = sql_safe($_POST['sexe']);
    $age = sql_safe($_POST['age']);
    $streetNb = sql_safe($_POST['streetNb']);
    $streetName = sql_safe($_POST['streetName']);
    $appNb = sql_safe($_POST['appNb']);
    $city = sql_safe($_POST['city']);
    $province = sql_safe($_POST['province']);
    $postCode = sql_safe($_POST['postCode']);

    if(empty($idUser)) {
        sql_open();

        $result = sql_query("
            INSERT INTO T_ADDRESS(F_STREET_NB, F_STREET_NAME, F_APP_NB, F_CITY, F_PROVINCE, F_POST_CODE)
            VALUES('$streetNb', '$streetName', '$appNb', '$city', '$province', '$postCode')
        ");

        if(!$result) {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
        }

        $idAddress = sql_insert_id();

        $result = sql_query("
            INSERT INTO T_USER(F_ID_ADDRESS, F_LASTNAME, F_NAME, F_USERNAME, F_PASSWORD, F_PHONE, F_EMAIL, F_SEXE, F_AGE)
            VALUES('$idAddress', '$lastname', '$name', '$username', '$password', '$phone', '$email', '$sexe', '$age')
        ");

        sql_close();

        if($result) {
            $response["success"] = 1;
            $response["message"] = "Création de l'utilisateur complété!";

            echo json_encode($response);
        } else {
            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
        }
    } else {
        sql_open();

        $result = sql_query("
            UPDATE T_USER
            SET F_LASTNAME = '$lastname', F_NAME = '$name', F_USERNAME = '$username', F_PASSWORD = '$password', F_PHONE = '$phone', F_EMAIL = '$email', F_SEXE = '$sexe', F_AGE = '$age'
            WHERE F_ID_USER = '$idUser'
        ");

        if(!$result) {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
        }

        $result = sql_query("
            SELECT F_ID_ADDRESS
            FROM T_USER
            WHERE F_ID_USER = '$idUser'
        ");

        if(!$result) {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
        }

        while ($row = sql_fetch_array($result)) {
            $idAddress = $row[0];
        }

        $result = sql_query("
            UPDATE T_ADDRESS
            SET F_STREET_NB = '$streetNb', F_STREET_NAME = '$streetName', F_APP_NB = '$appNb', F_CITY = '$city', F_PROVINCE = '$province', F_POST_CODE = '$postCode'
            WHERE F_ID_ADDRESS = '$idAddress'
        ");

        sql_close();

        if(result) {
            $response["success"] = 1;
            $response["message"] = "Mise à jour de l'utilisateur complété!";

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