<?php

// Chemin du fichier JSON
$file = 'utilisateur.json';

// Vérifier si le fichier JSON existe, sinon le créer
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
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
        // Vérifier si l'utilisateur existe déjà
        $userExists = false;
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                $userExists = true;
                break;
            }
        }

        if ($userExists) {
            echo 'Cet utilisateur existe déjà.';
        } else {
            // Ajouter le nouvel utilisateur
            $users[] = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT) // Hachage du mot de passe
            ];

            // Enregistrer les utilisateurs dans le fichier JSON
            file_put_contents($file, json_encode($users));

            echo 'Inscription réussie !';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Formulaire d'inscription</h1>
    <form method="POST" action="">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>