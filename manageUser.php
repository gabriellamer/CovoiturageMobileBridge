<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$data = json_decode(utf8_encode(file_get_contents('php://input')));

if(isset($data->lastname) && !empty($data->lastname) &&
    isset($data->name) && !empty($data->name) &&
    isset($data->username) && !empty($data->username) &&
    isset($data->password) && !empty($data->password) &&
    isset($data->phone) && !empty($data->phone) &&
    isset($data->email) && !empty($data->email) &&
    isset($data->sexe) && !empty($data->sexe) &&
    isset($data->age) && !empty($data->age) &&
    isset($data->streetNb) && !empty($data->streetNb) &&
    isset($data->streetName) && !empty($data->streetName) &&
    isset($data->appNb) &&
    isset($data->city) && !empty($data->city) &&
    isset($data->province) && !empty($data->province) &&
    isset($data->postCode) && !empty($data->postCode))
{

    $lastname = sql_safe($data->lastname);
    $name = sql_safe($data->name);
    $username = sql_safe($data->username);
    $password = sql_safe($data->password);
    $phone = sql_safe($data->phone);
    $email = sql_safe($data->email);
    $sexe = sql_safe($data->sexe);
    $age = sql_safe($data->age);
    $streetNb = sql_safe($data->streetNb);
    $streetName = sql_safe($data->streetName);
    $appNb = sql_safe($data->appNb);
    $city = sql_safe($data->city);
    $province = sql_safe($data->province);
    $postCode = sql_safe($data->postCode);

    if(!isset($data->idUser) && empty($data->idUser))
    {
        sql_open();

        $result = sql_query("
            SELECT F_ID_USER
            FROM T_USER
            WHERE F_USERNAME = '" . $username . "'
        ");

        if(mysqli_num_rows($result) != 0)
        {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Le nom d'utilisateur est déjà utilisé!";

            echo json_encode($response);
            die();
        }

        $result = sql_query("
            INSERT INTO T_ADDRESS(F_STREET_NB, F_STREET_NAME, F_APP_NB, F_CITY, F_PROVINCE, F_POST_CODE)
            VALUES(" . $streetNb . ", '" . $streetName . "', '" . $appNb . "', '" . $city . "', '" . $province . "', '" . $postCode . "')
        ");

        if(!$result)
        {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
            die();
        }

        $idAddress = sql_insert_id();

        $result = sql_query("
            INSERT INTO T_USER(F_ID_ADDRESS, F_LASTNAME, F_NAME, F_USERNAME, F_PASSWORD, F_PHONE, F_EMAIL, F_SEX, F_AGE)
            VALUES(" . $idAddress . ", '" . $lastname . "', '" . $name . "', '" . $username . "', '" . $password . "', '" . $phone . "', '" . $email . "', '" . $sexe . "', " . $age . ")
        ");

        $idUser = sql_insert_id();

        sql_close();

        if($result)
        {
            $response["success"] = 1;
            $response["message"] = $idUser;
        }
        else
        {
            $response["success"] = 0;
            $response["message"] = "Erreur!";
        }

        echo json_encode($response);
        die();
    }
    else
    {
        $idUser = sql_safe($data->idUser);

        sql_open();

        $result = sql_query("
            SELECT F_USERNAME
            FROM T_USER
            WHERE F_ID_USER = '" . $idUser . "'
        ");

        if(!$result)
        {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
            die();
        }

        $userUsername = null;

        while ($row = sql_fetch_array($result))
        {
            $userUsername = $row[0];
        }

        if($username != $userUsername)
        {
            $result = sql_query("
                SELECT F_ID_USER
                FROM T_USER
                WHERE F_USERNAME = '" . $username . "'
            ");

            if(mysqli_num_rows($result) != 0)
            {
                sql_close();

                $response["success"] = 0;
                $response["message"] = "Le nom d'utilisateur est déjà utilisé!";

                echo json_encode($response);
                die();
            }
        }

        $result = sql_query("
            UPDATE T_USER
            SET F_LASTNAME = '" . $lastname . "', F_NAME = '" . $name . "', F_USERNAME = '" . $username . "', F_PASSWORD = '" . $password . "', F_PHONE = '" . $phone . "', F_EMAIL = '" . $email . "', F_SEX = '" . $sexe . "', F_AGE = " . $age . "
            WHERE F_ID_USER = " . $idUser . "
        ");

        if(!$result)
        {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
            die();
        }

        $result = sql_query("
            SELECT F_ID_ADDRESS
            FROM T_USER
            WHERE F_ID_USER = " . $idUser . "
        ");

        if(!$result)
        {
            sql_close();

            $response["success"] = 0;
            $response["message"] = "Erreur!";

            echo json_encode($response);
            die();
        }

        while ($row = sql_fetch_array($result))
        {
            $idAddress = $row[0];
        }

        $result = sql_query("
            UPDATE T_ADDRESS
            SET F_STREET_NB = " . $streetNb . ", F_STREET_NAME = '" . $streetName . "', F_APP_NB = '" . $appNb . "', F_CITY = '" . $city . "', F_PROVINCE = '" . $province . "', F_POST_CODE = '" . $postCode . "'
            WHERE F_ID_ADDRESS = '$idAddress'
        ");

        sql_close();

        if($result)
        {
            $response["success"] = 1;
            $response["message"] = $idUser;
        }
        else
        {
            $response["success"] = 0;
            $response["message"] = "Erreur!";
        }

        echo json_encode($response);
        die();
    }
}
else
{
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
    die();
}