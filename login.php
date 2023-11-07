<?php

session_start();
require_once "dbconfig.php";

$username_or_email = $password = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $username_or_email = $_POST["username_or_email"];
    $password = $_POST["password"];

    if (empty(trim($username_or_email))) $errors["username_or_email"] = "Username or email must not be empty.";
    if (empty(trim($password))) $errors["password"] = "Password must not be empty.";

    if (count($errors) === 0)
    {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username_or_email, $username_or_email]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result && password_verify($password, $result->password))
        {
            $_SESSION["id"] = $result->id;
            $_SESSION["username"] = $result->username;

            header("Location: index.php");
            exit;
        }
        else
        {
            $errors["authentication"] = "Invalid credentials.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes App - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full font-['Inter']">
    <div class="flex justify-center flex-col items-start min-h-full">
        <div class="max-w-xl mx-auto w-full px-4 sm:px-0">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold mb-1 tracking-wide w-full">Signin an account</h1>
                <p class="text-slate-600">Fill out this form to signin your account.</p>
            </div>

            <form method="post">
                <div class="mb-3 flex flex-col">
                    <label for="username_or_email" class="text-sm mb-1 font-medium">Username or email</label>
                    <input 
                        type="text" 
                        name="username_or_email" 
                        id="username_or_email"
                        value="<?= htmlspecialchars($_POST["username_or_email"] ?? "") ?>"
                        class="border py-1.5 px-3 rounded-md focus:outline-none ring-emerald-500 focus:ring-2 focus:ring-offset-2 shadow-sm"
                    >

                    <?php if(isset($errors["username_or_email"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["username_or_email"] ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-3 flex flex-col">
                    <label for="password" class="text-sm mb-1 font-medium">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        value="<?= htmlspecialchars($_POST["password"] ?? "") ?>"
                        class="border py-1.5 px-3 rounded-md focus:outline-none ring-emerald-500 focus:ring-2 focus:ring-offset-2 shadow-sm"
                    >
                    <?php if(isset($errors["password"])) : ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors["password"] ?></p>
                    <?php endif; ?>
                </div>

                <?php if(isset($errors["authentication"])) : ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors["authentication"] ?></p>
                <?php endif; ?>

                <div class="mt-6">
                    <button class="w-full bg-emerald-600 py-2 rounded-md shadow-lg text-slate-100 hover:bg-emerald-500 transition-all">Signin</button>
                </div>

                <div class="mt-3">
                    <p class="text-slate-600">
                        Need an account?
                        <span><a href="register.php" class="underline underline-offset-2 ml-2 text-slate-700">Register</a></span>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>