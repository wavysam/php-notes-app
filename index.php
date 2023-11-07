<?php

session_start();
require_once "dbconfig.php";

if (!isset($_SESSION["id"]) || !isset($_SESSION["username"]))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["id"];
$sql = "SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$notes = $stmt->fetchAll(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes App - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="h-full font-['Inter']">

    <?php require "partials/nav.php" ?>

    <div class="max-w-7xl mx-auto p-6">
        <a href="create.php" class="bg-emerald-600 hover:bg-emerald-500 text-slate-200 py-2 px-4 rounded-md shadow-md transition-all">
            Add note
            <i class="bi bi-arrow-right"></i>
        </a>

        <div class="grid grid-cols-3 gap-5 mt-10">
            <?php foreach ($notes as $note): ?>
                <div class="rounded-md border shadow-sm p-3 relative">
                    <div class="flex flex-col h-full border-slate-300">
                        <div class="flex">
                            <h3 class="text-lg font-medium"><?= $note->title ?></h3>
                            <div class="flex items-center ml-auto">
                                <a href="<?= "update.php?id=".$note->id ?>" class="flex items-center justify-center bg-emerald-600 text-slate-200 text-sm h-6 w-6 rounded shadow-md mr-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= "delete.php?id=".$note->id ?>" class="flex items-center justify-center bg-rose-500 text-slate-200 text-sm h-6 w-6 rounded shadow-md" onclick="return confirm('Are you sure you want to delete?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="my-2">
                            <p class="text-slate-700 mt-3"><?= $note->description ?></p>
                        </div>
                        <div class="flex justify-end mt-auto">
                            <p class="text-sm font-medium text-neutral-700"><?= date("m-d-Y", strtotime($note->created_at)) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
        </div>
    </div>
</body>
</html>