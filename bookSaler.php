<?php
function getNumberVisit() {
    $filename = "resources/counter.log";
    if ( file_exists( $filename ) ) {
        $filetext = file_get_contents($filename);
    } else {
        $filetext = 0;
    }
    if ( !isset($_COOKIE['seen']) ) {
        $filetext += 1;
        file_put_contents($filename, $filetext);
    }
    setcookie("seen", true, time()+3600);
    return $filetext;
}
?>

<?php
    require_once('UserSession.php');
    $content = getNumberVisit();
    $client = (new MySession());
    if ( !$client->isValid() || !$client->isActive() ) {
        $client->setError("Votre session a expiré.");
        header('Location: login/login.php');
        exit;
    }
?> 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="resources/icon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="css/member.css">
    <link rel="stylesheet" href="css/cart.css">
    <title> Vente de Livres - pk/st </title>
    <script src="mainScript.js" async defer></script>
</head>
<body>
    <header>
        <section id="nb_visit">
            <p> Nombre de visite : <?php
                echo $content;
            ?> 
            </p>
        </section>
        <section id="id_user">
            <h1 id="main-header"> vente de Livres chez pk / st </h1>
        </section>
        <section id="user_info">
            <p>Bienvenue</p>
            <p id="first-name"><?php echo $client->firstName();?></p>
            <p id="last-name"><?php echo $client->lastName();?></p>
            <button id="cartButton" class="btn-style1">Panier</button>
            <dialog id="dialogCart" resizable>
                <h3 id="cartTitle">Panier</h3>
                <div id="cartList" class="table-style">
                    <table id="cartTable">
                        <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Editeur</th>
                            <th>Quantité</th>
                            <th>Prix à l'unité</th>
                        </tr>
                        </thead>
                        <tbody id="tableCart">
                        </tbody>
                    </table>
                </div>
                <p id="totalPrice"></p>
                <button  id="toCommand" class="btn-style1">Commander</button>
                <button  id="toQuit" class="btn-style1">Fermer</button>
            </dialog>
            <button id="emptyCart" class="btn-style1">Vider panier</button>
            <form method="POST" action="handleRequest.php">
                <button type="submit" value="moveToLoginPage" name="action" id="quit" class="btn-style1">Quitter</button>
            </form>
            </section>
    </header>
    <div class="wrapper">
        <nav id="search-section">
            <h2 id="search-text">Recherche :</h2>
            <ul>
                <li>
                    <label for="search_author">Par Auteur : </label>
                    <input type="text" id="search_author" name="search_author" required minlength="1"></input>
                </li>
                <li>
                    <label for="search_title">Par Titre : </label>
                    <input type="text" id="search_title" name="search_title" required minlength="1"></input>
                </li>
            </ul>
        </nav>
        <section id="results-search-section">
            <h2 id="second-header">Bienvenue sur le site de la bibliothèque virtuelle</h2>
            <div class="wrapper" id="result">
                <div>
                    <p id="author">Auteurs</p>
                    <div id="list1"></div>
                </div>
                <div>
                    <p id="works">Ouvrages</p>
                    <div id="list2"></div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>