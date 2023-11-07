<?php

session_start();
require_once "dbconfig.php";

if (!isset( $_SESSION["id"]) || !isset($_SESSION["username"]))
{
    header("Location: login.php");
    exit();
}

$id = $_GET["id"];
$errors = [];

if (isset($_GET["id"]))
{
    $sql = "SELECT * FROM notes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $note = $stmt->fetch(PDO::FETCH_OBJ);
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $title = $_POST["title"];
    $description = $_POST["description"];

    if (empty(trim($title))) $errors["title"] = "Title must not be empty.";
    if (empty(trim($description))) $errors["description"] = "Description must not be empty.";

    if (count($errors) === 0)
    {
        $sql = "UPDATE notes SET title = ?, description = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $status = $stmt->execute([$title, $description, $id]);

        if ($status)
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body class="h-full font-['Inter']">

    <?php require "partials/nav.php" ?>

    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center gap-x-3">
            <a href="index.php" class="text-slate-900">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Update Note</h1>
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
                        value="<?= htmlspecialchars($note->title) ?>"
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
                    ><?= htmlspecialchars($note->description) ?></textarea>
                    <?php if(isset($errors["description"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["description"] ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <button class="bg-emerald-600 hover:bg-emerald-500 text-slate-200 py-2 px-6 rounded-md transition shadow-md">Update</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>