<?php

$connect = null;

function sql_open()
{
    global $config, $connect;
    $connect = mysqli_connect($config['db.server'], $config['db.username'], $config['db.password'], $config['db.database']);

    if(!$connect)
    {
        die("Erreur: ".mysqli_connect_error());
    }

    //Patch temporaire
    sql_query("SET NAMES 'UTF8'");
}

function sql_close()
{
    global $connect;
    mysqli_close($connect);
}

function sql_query($query)
{
    global $connect;
    return mysqli_query($connect, $query);
}

function sql_fetch_array($result)
{
    return mysqli_fetch_array($result);
}

function sql_fetch_row($result)
{
    return mysqli_fetch_row($result);
}

function sql_safe($string)
{
    $string = addslashes($string);
    $string = strip_tags($string);

    return $string;
}

function sql_data_seek($result, $number)
{
    mysql_data_seek($result, $number);
}

function sql_prepare($string)
{
    global $connect;
    return mysqli_prepare($connect, $string);
}

function sql_insert_id()
{
    global $connect;
    return mysqli_insert_id($connect);
}