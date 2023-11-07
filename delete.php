<?php

session_start();
$id = $_GET["id"];

if (!isset($_SESSION["id"]) || !isset($_SESSION["username"]))
{
    header("Location: login.php");
    exit();
}

if (isset($id))
{
    require_once "dbconfig.php";
    $sql = "DELETE FROM notes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0)
    {
        header("Location: index.php");
        exit();
    }
}
