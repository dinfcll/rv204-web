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
                              firstName VARCHAR(50),
                              lastName VARCHAR(50),
                              username VARCHAR (50),
                              password VARCHAR (50),
                              color VARCHAR (10),
                              image BLOB,
                              isAdmin INTEGER
                              );
                          ");
    }

    public function insererEmploye($firstName, $lastName, $username, $password, $color, $isAdmin = 0)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (firstName, lastName, username, password, color, isAdmin)
                                     VALUES (:firstName, :lastName, :username, :password, :color, :isAdmin)");
        $stmt->execute(array(
            'firstName' => $firstName,
            'lastName' => $lastName,
            'username' => $username,
            'password' => $password,
            'color' => $color,
            'isAdmin' => $isAdmin
        ));
    }

    public function majEmploye($id, $password, $color, $image, $isAdmin)
    {
        $stmt = $this->pdo->prepare("UPDATE users
                                     SET password='".$password."',
                                         isAdmin=".$isAdmin.",
                                         color='".$color."',
                                         image=?
                                     WHERE id=".$id);
        $stmt->bindValue(1, $image, PDO::PARAM_LOB);
        $stmt->execute();
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
        $this->insererEmploye("Vrai", "Administrateur", "admin", "admin", "#ff0000", 1);
    }

    public function creerOlivier()
    {
        $this->insererEmploye("Olivier", "Lafleur", "olivier", "olivier", "#00ff00");
    }

    public function creerGuillaume()
    {
        $this->insererEmploye("Guillaume", "Michaud", "michaudg", "michaudg#123", "#00ff00", 1);
    }

    public function recupererUtilisateur($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = '" . $username . "'");
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0];
    }

    public function genererImage($id) {
        $stmt = $this->pdo->prepare("SELECT image FROM users WHERE id = " . $id);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result[0]['image'];
    }

    public function applicationNonInstallee()
    {
        try {
            $this->pdo->prepare("SELECT 1 FROM users LIMIT 1");
        } catch (Exception $e) {
            return true;
        }

        return false;
    }

    public function recupererTousUtilisateurs()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function supprimerUsager($id) {
        $this->pdo->query("DELETE FROM users
                           WHERE id=".$id);
    }

    public function supprimerTable()
    {
        $this->pdo->query("DROP TABLE IF EXISTS users;");
    }

    public function creerUsagersStandards()
    {
        $this->insererEmploye("Vrai", "Administrateur", "admin", "admin#123", "#ff0000", 1);
        $this->insererEmploye("Olivier", "Lafleur", "lafleuro", "admin#123", "#ffff00", 1);
        $this->insererEmploye("Guillaume", "Michaud", "michaudg", "admin#123", "#ff00ff", 1);
        $this->insererEmploye("Mélissa", "Clermont", "clermontm", "admin#123", "#00ffff");
        $this->insererEmploye("Gilles", "Champagne", "champagneg", "admin#123", "#00ff00");
        $this->insererEmploye("Josée", "Lainesse", "lainessej", "admin#123", "#0000ff");
        $this->insererEmploye("Marc", "Deslandes", "deslandesm", "admin#123", "#ffffff");
        $this->insererEmploye("Lise", "Provencher", "provencherl", "admin#123", "#000000");

    }
}
