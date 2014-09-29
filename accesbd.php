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
                              isAdmin INTEGER,
                              email VARCHAR (100)
                              );
                          ");
    }

    public function insererEmploye($id, $firstName, $lastName, $username, $password, $color, $email, $isAdmin = 0, $image = "")
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (firstName, lastName, username, password, color, isAdmin, email, image, id)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bindValue(1, $firstName);
        $stmt->bindValue(2, $lastName);
        $stmt->bindValue(3, $username);
        $stmt->bindValue(4, $password);
        $stmt->bindValue(5, $color);
        $stmt->bindValue(6, $isAdmin);
        $stmt->bindValue(7, $email);
        $stmt->bindValue(8, $image, PDO::PARAM_LOB);
        $stmt->bindValue(9, $id);

        $stmt->execute();
    }

    public function majEmploye($id, $password, $color, $image, $isAdmin, $email)
    {
        $stmt = $this->pdo->prepare("UPDATE users
                                     SET password='".$password."',
                                         isAdmin=".$isAdmin.",
                                         color='".$color."',
                                         email='".$email."',
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
        $this->insererEmploye(null, "Vrai", "Administrateur", "admin", "admin", "#ff0000", 1);
    }

    public function creerOlivier()
    {
        $this->insererEmploye(null, "Olivier", "Lafleur", "olivier", "olivier", "#00ff00", "olivier.lafleur@cll.qc.ca");
    }

    public function creerGuillaume()
    {
        $this->insererEmploye(null, "Guillaume", "Michaud", "michaudg", "michaudg#123", "#00ff00", "michaudg@cll.qc.ca", 1);
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
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id > 0");
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
        $this->insererEmploye(0, "", "", "admin", "admin#123", "", "", 1);
        $this->insererEmploye(null, "Olivier", "Lafleur", "lafleuro", "admin#123", "#ffff00", "olivier.lafleur@cll.qc.ca", 1, file_get_contents("usagers_images/sauvage.png"));
        $this->insererEmploye(null, "Guillaume", "Michaud", "michaudg", "admin#123", "#ff00ff", "michaudg@cll.qc.ca", 1, file_get_contents("usagers_images/grandschtroumpf.png"));
        $this->insererEmploye(null, "Mélissa", "Clermont", "clermontm", "admin#123", "#00ffff", "melissa.clermont@cll.qc.ca", 0, file_get_contents("usagers_images/schtroumpfette.png"));
        $this->insererEmploye(null, "Gilles", "Champagne", "champagneg", "admin#123", "#00ff00", "gilles.champagne@cll.qc.ca", 0, file_get_contents("usagers_images/crayon.png"));
        $this->insererEmploye(null, "Josée", "Lainesse", "lainessej", "admin#123", "#0000ff", "josee.lainesse@cll.qc.ca", 0, file_get_contents("usagers_images/bebe.png"));
        $this->insererEmploye(null, "Marc", "Deslandes", "deslandesm", "admin#123", "#ffffff", "marc.deslandes@cll.qc.ca", 0, file_get_contents("usagers_images/fleur.png"));
        $this->insererEmploye(null, "Lise", "Provencher", "provencherl", "admin#123", "#000000", "lise.provencher@cll.qc.ca", 0, file_get_contents("usagers_images/tada.png"));
        $this->insererEmploye(null, "Serge", "Lévesque", "levesques", "admin#123", "#ff0000", "serge.levesque@cll.qc.ca", 0, file_get_contents("usagers_images/vantard.png"));

    }
}
