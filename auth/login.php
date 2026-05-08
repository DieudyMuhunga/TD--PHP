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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <form method="POST" action="">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>