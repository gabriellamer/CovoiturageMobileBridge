<?php
require_once("sys/inc/config.php");
require_once("sys/inc/database.php");

$data = json_decode(utf8_encode(file_get_contents('php://input')));

if(isset($data->username) && !empty($data->username) &&
   isset($data->password) && !empty($data->password))
{

    $username = sql_safe($data->username);
    $password = sql_safe($data->password);

    sql_open();

    $result = sql_query("
        SELECT F_ID_USER
        FROM T_USER
        WHERE F_USERNAME = '" . $username . "'
        AND F_PASSWORD = '" . $password . "'
    ");

    if(!$result)
    {
        sql_close();

        $response["success"] = 0;
        $response["message"] = "Erreur!";

        echo json_encode($response);
        die();
    }

    if(mysqli_num_rows($result) != 1)
    {
        sql_close();

        $response["success"] = 0;
        $response["message"] = "Nom d'utilisateur ou mot de passe incorrect!";

        echo json_encode($response);
        die();
    }

    while ($row = sql_fetch_array($result))
    {
        $idUser = $row[0];
    }

    $result = sql_query("
        SELECT u.F_ID_USER, u.F_LASTNAME, u.F_NAME, u.F_USERNAME, u.F_PASSWORD, u.F_PHONE, u.F_EMAIL, u.F_SEX, u.F_AGE, a.F_STREET_NB, a.F_STREET_NAME, a.F_APP_NB, a.F_CITY, a.F_PROVINCE, a.F_POST_CODE
        FROM T_USER u
        LEFT JOIN T_ADDRESS a on a.F_ID_ADDRESS = u.F_ID_ADDRESS
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
        $response["message"][] = $row;
    }

    sql_close();

    if($result)
    {
        $response["success"] = 1;
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
    $response["success"] = 0;
    $response["message"] = "Un ou plusieurs champs manquant!";

    echo json_encode($response);
    die();
}