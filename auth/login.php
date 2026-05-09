<?php

// Système de connexion en PHP procédural

// Chemin du fichier JSON
$file = '../utilisateur.json';

// Vérifier si le fichier JSON existe
if (!file_exists($file)) {
    die('Le fichier utilisateur.json est introuvable.');
}

// Charger les utilisateurs existants
$users = json_decode(file_get_contents($file), true);

// Vérifier si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Vérifier si les champs sont remplis
    if (empty($username) || empty($password)) {
        echo 'Veuillez remplir tous les champs.';
    } else {
        $userFound = false;

        // Parcourir les utilisateurs pour vérifier les identifiants
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                $userFound = true;

                // Démarrer une session
                session_start();
                $_SESSION['username'] = $username;

                // Rediriger vers index.php
                header('Location: ../index.php');
                exit;
            }
        }

        if (!$userFound) {
            echo 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    }
}

?>

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: linear-gradient(to bottom, #f0f0f0, #c0c0c0);
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .card {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        width: 350px;
        text-align: center;
    }

    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-top: 20px;
        border: none;
        border-bottom: 1px solid #ccc;
        outline: none;
        font-size: 16px;
    }

    input:focus {
        border-bottom: 1px solid #2d89ef;
    }

    button {
        width: 100%;
        margin-top: 25px;
        padding: 10px;
        background-color: #2d89ef;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:hover {
        background-color: #1b6fc2;
    }
</style>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <div class="card">
        <h1>SGA</h1>
        <form method="POST" action="">
            <input type="text" id="username" name="username" placeholder="Nom d'utilisateur" required><br>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required><br><br>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>