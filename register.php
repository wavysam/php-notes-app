<?php

session_start();
require_once "dbconfig.php";

if (isset($_SESSION["id"]) || isset($_SESSION["username"]))
{
    header("Location: index.php");
    exit();
}

$username = $email = $password = $confirm_password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty(trim($username))) $errors["username"] = "Username must not be empty.";
    if (empty(trim($email))) $errors["email"] = "Email must not be empty.";
    if (empty(trim($password))) $errors["password"] = "Password must not be empty.";
    if (empty(trim($confirm_password))) $errors["confirm_password"] = "Confirm password must not be empty.";
    if (!empty($confirm_password) && $confirm_password !== $password) $errors["confirm_password"] = "Password did not match.";

    if (count($errors) === 0)
    {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email]);

        if ($stmt->rowCount() === 0)
        {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $email, $hashedPassword]);

            if ($stmt->rowCount() > 0)
            {
                header("Location: login.php");
                exit();
            }
        }
        else
        {
            $errors["authentication"] = "User already exist.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes App - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full font-['Inter']">
    <div class="flex justify-center flex-col items-start min-h-full">
        <div class="max-w-xl mx-auto w-full px-4 sm:px-0">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold mb-1 tracking-wide w-full">Create an account</h1>
                <p class="text-slate-600">Fill out this form to create an account.</p>
            </div>

            <form method="post">
                <div class="mb-3 flex flex-col">
                    <label for="username" class="text-sm mb-1 font-medium">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username"
                        value="<?= htmlspecialchars($_POST["username"] ?? "") ?>"
                        class="border py-1.5 px-3 rounded-md focus:outline-none focus:ring-emerald-500 focus:ring-2 focus:ring-offset-2 shadow-sm"
                    >

                    <?php if(isset($errors["username"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["username"] ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-3 flex flex-col">
                    <label for="email" class="text-sm mb-1 font-medium">Email</label>
                    <input 
                        type="text" 
                        name="email" 
                        id="email"
                        value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"
                        class="border py-1.5 px-3 rounded-md focus:outline-none focus:ring-emerald-500 focus:ring-2 focus:ring-offset-2 shadow-sm"
                    >
                    <?php if(isset($errors["email"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["email"] ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-3 flex flex-col">
                    <label for="password" class="text-sm mb-1 font-medium">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        value="<?= htmlspecialchars($_POST["password"] ?? "") ?>"
                        class="border py-1.5 px-3 rounded-md focus:outline-none focus:ring-emerald-500 focus:ring-2 focus:ring-offset-2x shadow-sm"
                    >
                    <?php if(isset($errors["password"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["password"] ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-3 flex flex-col">
                    <label for="confirm_password" class="text-sm mb-1 font-medium">Confirm Password</label>
                    <input 
                        type="password" 
                        name="confirm_password" 
                        id="confirm_password"
                        value="<?= htmlspecialchars($_POST["confirm_password"] ?? "") ?>"
                        class="border py-1.5 px-3 rounded-md focus:outline-none focus:ring-emerald-500 focus:ring-2 focus:ring-offset-2 shadow-sm"
                    >
                    <?php if(isset($errors["confirm_password"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["confirm_password"] ?></p>
                    <?php endif; ?>
                </div>

                <?php if(isset($errors["authentication"])) : ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors["authentication"] ?></p>
                <?php endif; ?>

                <div class="mt-6">
                    <button class="w-full bg-emerald-600 py-2 rounded-md shadow-lg text-slate-100 hover:bg-emerald-500 transition-all">Register</button>
                </div>

                <div class="mt-3">
                    <p class="text-slate-600">
                        Already have an account?
                        <span><a href="login.php" class="underline underline-offset-2 ml-2 text-slate-700">Sign in</a></span>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>