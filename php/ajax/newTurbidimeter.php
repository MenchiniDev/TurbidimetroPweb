<?php
require_once __DIR__ . "/../util/datamanagerDB.php";

session_start();
if($_SERVER['REQUEST_METHOD']=='POST')
{
    
    $id = (int)$_POST['identificatore'];
    $lat = (float)$_POST['latitudine'];
    $long = (float)$_POST['longitudine'];

    header('Content-Type: application/json; charset=utf-8'); 
    //ritorno un array col tipo in caso di errore o meno 

    $done = storeTurbidimeter($id,$lat,$long);
    if($done)
    {
        echo json_encode(array("result" => true, "text" => "Registrazione effettuata con successo!"));
        $_SESSION['notifica']=true;
        return;
    } else {
        echo json_encode(array("result" => false, "text" => "Problema con l'inserimento dei dati"));
        return;
    }
}
?>