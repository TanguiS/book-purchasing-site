<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vente de Livres - Enregistrement</title>
    <link rel="shortcut icon" href="../resources/icon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../css/form.css">
    <script src="./register.js" async defer></script>
</head>
<body>
    <h1 class="main-title">Enregistrement - Vente de Livres chez pk / st</h1>
    <form action="javascript:;" onsubmit="return handlerRegister(this);", method="POST" class="information-style">
        <div class="grid-container">
            <div class="grid-item">
                <label for="first-name">Prenom :</label>
                <input type="text" id="first-name" name="first-name" required>
            </div>
            <div class="grid-item">
                <label for="last-name">Nom :</label>
                <input type="text" id="last-name" name="last-name" required>
            </div>
            <div class="grid-item">
                <label for="addr">Adresse :</label>
                <input type="text" id="addr" name="addr" required>
            </div>
            <div class="grid-item">
                <label for="cp">Code Postal :</label>
                <input type="number" id="cp" name="cp" required>
            </div>
            <div class="grid-item">
                <label for="city">Ville : </label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="grid-item">
                <label for="country">Pays :</label>
                <input type="text" id="country" name="country" required>
            </div>
        </div>
        <input type="submit" id="root" value="S'enregistrer" title="Submit" class="btn-style1">
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
        <p>Vous avez déjà un compte chez nous?
        <a class="btn-style1" href="../login/login.php">Se connecter</a>
        </p>
    </div>

</body>
</html>