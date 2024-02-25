<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vente de Livres - connexion</title>
    <link rel="stylesheet" href="../css/form.css">
    <link rel="shortcut icon" href="../resources/icon.jpg" type="image/x-icon">
    <script src="./login.js" async defer></script>
</head>
<body>
    <h1 class="main-title">Connexion - Vente de Livres chez pk / st</h1>
    <form action="javascript:;" onsubmit="return handlerLogin(this)", method="POST" class="information-style">
        <label for="name">Veuillez entrer votre prénom : </label>
        <input type="text" id="name" name="name" required>
        <label for="lastName">Veuillez entrer votre nom : </label>
        <input type="text" id="lastName" name="lastName" required>
        <input type="submit" id="submit" value="Connexion"  class="btn-style1">
        <div class="err-msg">
            <?php
                require_once('../UserSession.php');
                $sessionUser = ( new MySession() );
                $error = $sessionUser->getError();
                if ( $error != NULL ) {
                    echo "<p>".$error."</p>";
                    $sessionUser->deleteError();
                }
            ?>
        </div>
    </form>
    <div class="other-connection">
        <p>Vous n'avez pas de compte chez nous?
        <a class="btn-style1" href="../register/register.php">S'enregistrer</a>
        </p>
    </div>
</body>
</html>