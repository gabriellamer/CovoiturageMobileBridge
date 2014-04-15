<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$response = array();

if(isset($_POST['username']) && !empty($_POST['username']) &&
    isset($_POST['password']) && !empty($_POST['password'])) {

    $username = sql_safe($_POST['username']);
    $password = sql_safe($_POST['password']);

    if(empty($idUser)) {
        sql_open();

        $result = sql_query("
            SELECT F_ID_USER
            FROM T_USER
            WHERE F_USERNAME = '$username'
            AND F_PASSWORD = '$password'
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
            $response["message"] = "Nom d'utilisateur ou mot de passe incorrect!";

            echo json_encode($response);
        }

        while ($row = sql_fetch_array($result)) {
            $idUser = $row[0];
        }

        $result = sql_query("
            SELECT u.F_LASTNAME, u.F_NAME, u.F_USERNAME, u.F_PASSWORD, u.F_PHONE, u.F_EMAIL, u.F_SEXE, u.F_AGE, a.F_STREET_NB, a.F_STREET_NAME, a.F_APP_NB, a.F_CITY, a.F_PROVINCE, a.F_POST_CODE
            FROM T_USER u
            LEFT JOIN T_ADDRESS a on a.F_ID_USER = u.F_ID_USER
            WHERE F_ID_USER = '$idUser'
        ");

        if(!$result) {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
        }

        while ($row = sql_fetch_array($result)) {
            $response["message"] = $row;
            /*$lastname = $row[0];
            $name = $row[1];
            $username = $row[2];
            $password = $row[3];
            $phone = $row[4];
            $email = $row[5];
            $sexe = $row[6];
            $age = $row[7];
            $streetNb = $row[8];
            $streetName = $row[9];
            $appNb = $row[10];
            $city = $row[11];
            $province = $row[12];
            $postCode = $row[13];*/
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
    }
} else {
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
}