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
                              couleur VARCHAR (10),
                              image BLOB,
                              isAdmin INTEGER
                              );
                          ");
    }

    public function insererEmploye($nom_complet, $username, $password, $couleur, $isAdmin = 0)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (nom_complet, username, password, couleur, isAdmin)
                                     VALUES (:nom_complet, :username, :password, :couleur, :isAdmin)");
        $stmt->execute(array(
            'nom_complet' => $nom_complet,
            'username' => $username,
            'password' => $password,
            'couleur' => $couleur,
            'isAdmin' => $isAdmin
        ));
    }

    public function majEmploye($id, $password, $couleur, $image, $isAdmin)
    {
        $stmt = $this->pdo->prepare("UPDATE users
                                     SET password='".$password."',
                                         isAdmin=".$isAdmin.",
                                         couleur='".$couleur."',
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
        $this->insererEmploye("Administrateur", "admin", "admin", "#ff0000", 1);
    }

    public function creerOlivier()
    {
        $this->insererEmploye("Olivier Lafleur", "olivier", "olivier", "#00ff00");
    }

    public function creerGuillaume()
    {
        $this->insererEmploye("Guillaume Michaud", "michaudg", "michaudg#123", "#00ff00", 1);
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
}
