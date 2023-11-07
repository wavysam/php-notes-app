<?php

session_start();

if (!isset($_SESSION["id"]) || !isset($_SESSION["username"]))
{
    header("Location: login.php");
    exit();
}

$title = $description = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $title = $_POST["title"];
    $description = $_POST["description"];
    $user_id = $_SESSION["id"];

    if (empty(trim($title))) $errors["title"] = "Title must not be empty.";
    if (empty(trim($description))) $errors["description"] = "Description must not be empty.";

    if (count($errors) === 0)
    {
        require_once "dbconfig.php";
        $sql = "INSERT INTO notes (title, description, user_id) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $description, $user_id]);

        if ($stmt->rowCount() > 0)
        {
            header("Location: index.php");
            exit();
        }
    }
}

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
        <div class="flex items-center gap-x-3">
            <a href="index.php" class="text-slate-900">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Create new note</h1>
        </div>

        <div class="lg:max-w-lg my-8">
            <form method="post">
                <div class="mb-4 flex flex-col">
                    <label for="title" class="text-sm font-medium tracking-wide mb-1">Title</label>
                    <input 
                        type="text"
                        name="title" 
                        id="title"
                        autofocus
                        value="<?= htmlspecialchars($_POST["title"] ?? "") ?>"
                        class="border py-2 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-sm"
                    >

                    <?php if(isset($errors["title"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["title"] ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4 flex flex-col">
                    <label for="description" class="text-sm font-medium tracking-wide mb-1">Description</label>
                    <textarea 
                        name="description" 
                        id="description"
                        rows="10"
                        class="border py-2 px-3 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 resize-none shadow-sm"
                    ><?= htmlspecialchars($_POST["description"] ?? "") ?></textarea>
                                        
                    <?php if(isset($errors["description"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["description"] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <button class="bg-emerald-600 hover:bg-emerald-500 text-slate-200 py-2 px-6 rounded-md transition shadow-md">Create</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>