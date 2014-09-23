<?php

class AccesBD
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('sqlite:' . dirname(__FILE__) . '/database.sqlite');
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo "Impossible d'accéder à la base de données SQLite : " . $e->getMessage();
            die();
        }
    }

    public function creerTableEmployes()
    {
        $this->pdo->query("CREATE TABLE IF NOT EXISTS users (
                              id INTEGER PRIMARY KEY AUTOINCREMENT,
                              nom_complet VARCHAR(250),
                              username VARCHAR (50),
                              password VARCHAR (50),
                              couleur VARCHAR (10)
                              );
                          ");
    }

    public function insererEmploye($nom_complet, $username, $password, $couleur)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (nom_complet, username, password, couleur)
                                     VALUES (:nom_complet, :username, :password, :couleur)");
        $stmt->execute(array(
            'nom_complet' => $nom_complet,
            'username' => $username,
            'password' => $password,
            'couleur' => $couleur
        ));
    }

    public function recupererIdUtilisateur($username)
    {
        return $this->recupererUtilisateur($username)["id"];
    }

    public function utilisateurValide($username, $password)
    {
        return $this->recupererUtilisateur($username)['password'] === $password;
    }

    public function creerAdministrateur()
    {
        $this->insererEmploye("Administrateur", "admin", "admin", "#ff0000");
    }

    private function recupererUtilisateur($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = '" . $username . "'");
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0];
    }
}