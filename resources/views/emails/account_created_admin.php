<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création d'un nouveau compte</title>
</head>
<body>
    <h2>Bonjour, un nouveau compte a été créé au nom de {{ $user->first_name ?? $user->last_name }},</h2>
    <p>Votre compte a été créé avec succès.</p>
    <p>Voici vos identifiants :</p>
    <ul>
        <li><strong>Email :</strong> {{ $user->email }}</li>
        <li><strong>Mot de passe temporaire :</strong> {{ $password }}</li>
    </ul>
    <p>Veuillez vous connecter et modifier votre mot de passe dès que possible.</p>
    <p><a href="{{ url('/login') }}">Se connecter</a></p>
    <p>Merci,</p>
    <p>L'équipe.</p>
</body>
</html>
