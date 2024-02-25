<?php

class SQL_Livres {
    private $bdh;
    private $USER="steimetz";
    private $PASS="ensicaen";

    public function __construct() {
        $this->connect();
    }

    private function connect() { 
        try { 
            $this->bdh = new PDO("pgsql:host=postgres.ecole.ensicaen.fr;dbname=livres", $this->USER, $this->PASS);
        } catch (PDOException $e) { 
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    private function statementToJsonData( $stmt ) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $data = array();
        while ( $row = $stmt->fetch() ) {
            $data[]=$row;
        }
        return json_encode($data);
    }

    public function queryAuthorsFromDebName ($name) {
        $query = "SELECT code, nom, prenom FROM auteurs WHERE UPPER(nom) LIKE UPPER('$name%');";
        $stmt = $this->bdh->query($query); 
        return $this->statementToJsonData($stmt);
    }

    public function queryWorkFromTitle($title) {
        $query = "SELECT code, nom FROM ouvrage WHERE UPPER(nom) LIKE UPPER('$title%');";
        $stmt = $this->bdh->query($query);
        return $this->statementToJsonData($stmt);
    }

    public function queryCopyFromCodeWork($code_work) {
        $query = "SELECT editeurs.nom as \"nom\", exemplaire.code as \"code\", prix
        FROM exemplaire
        JOIN editeurs ON exemplaire.code_editeur = editeurs.code
        WHERE exemplaire.code_ouvrage = $code_work;";
        $stmt = $this->bdh->query($query);
        return $this->statementToJsonData($stmt);
    }

    public function queryBooksWorksFromTitle($title) {
        $workFromTitle = $this->queryWorkFromTitle($title);
        $workFromTitle = json_decode($workFromTitle, true);
        $result = array();
        foreach ( $workFromTitle as $element ) {
            $element['exemplaire'] = json_decode($this->queryCopyFromCodeWork($element['code']), true);
            $result[] = $element;
        }
        return json_encode($result);
    }

    public function queryWorkFromCodeAuthor($code_author) {
        $query = "SELECT code, nom FROM ouvrage WHERE code IN (
            SELECT code_ouvrage FROM ecrit_par WHERE code_auteur = $code_author);";
        $stmt = $this->bdh->query($query);
        return $this->statementToJsonData($stmt);  
    }

    public function queryAuthorsBooksFromDebName($name) {
        $authorsFromDebName = $this->queryAuthorsFromDebName($name);
        $authorsFromDebName = json_decode($authorsFromDebName, true);
        $result = array();
        foreach ( $authorsFromDebName as $element ) {
            $element['ouvrages'] = json_decode($this->queryWorkFromCodeAuthor($element['code']), true);
            $result[] = $element;
        }
        return json_encode($result);
    }

    public function register($last_name, $first_name, $addr, $cp, $city, $country) {
        $stmt = $this->bdh->prepare('SELECT inscription_adherent(:nom, :prenom, :adresse, :cp, :ville, :pays)');
    
        $stmt->bindParam(':nom', $last_name);
        $stmt->bindParam(':prenom', $first_name);
        $stmt->bindParam(':adresse', $addr);
        $stmt->bindParam(':cp', $cp);
        $stmt->bindParam(':ville', $city);
        $stmt->bindParam(':pays', $country);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_NUM)[0];
    
        if ($result === 0) {
            return 'no';    
        } 
        if ($result === null) {
            return 'Error';
        } 
        return $result;
    }

    public function isClient( $first_name, $last_name ){
        $query = "SELECT COUNT(id_client) FROM clients WHERE UPPER(nom) = UPPER('$last_name') AND UPPER(prenom) = UPPER('$first_name');";
        $stmt = $this->bdh->query($query);
        return $stmt->fetch(PDO::FETCH_NUM)[0];
    }

    public function getIdClient( $first_name, $last_name ){
        $query = "SELECT DISTINCT id_client FROM clients WHERE UPPER(nom) = UPPER('$last_name') AND UPPER(prenom) = UPPER('$first_name');";
        $stmt = $this->bdh->query($query);
        return $stmt->fetch(PDO::FETCH_NUM)[0];
    }

    public function addToCart($id_client, $code_copy) {
        $stmt = $this->bdh->prepare('SELECT addtocart(:id_client, :code_copy)');
        $stmt->bindParam(':id_client', $id_client);
        $stmt->bindParam(':code_copy', $code_copy);
        $stmt->execute();
    }

    public function uploadCommand($id_client) {
        $stmt = $this->bdh->prepare('SELECT uploadCommand(:id_client)');
        $stmt->bindParam(':id_client', $id_client);
        $stmt->execute();
    }

    public function deleteCart($id_client) {
        $stmt = $this->bdh->prepare('SELECT deleteCart(:id_client)');
        $stmt->bindParam(':id_client', $id_client);
        $stmt->execute();
    }

    public function getCartElement( $id_client ) {
        $query ="SELECT ouvrage.nom as \"titre\", editeurs.nom as \"editeur\", panier.quantite, exemplaire.prix 
                FROM exemplaire 
                JOIN panier ON panier.code_exemplaire = exemplaire.code 
                JOIN editeurs ON exemplaire.code_editeur = editeurs.code 
                JOIN ouvrage ON ouvrage.code = exemplaire.code_ouvrage 
                WHERE panier.id_client = $id_client;"; 
        $stmt = $this->bdh->query($query);
        return $this->statementToJsonData($stmt);
    } 
}