<?php

include 'employedao.class.php';

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
        $employeDao = new EmployeDao();

        $admin = (new EmployeBuilder())
            ->id(0)
            ->username("admin")
            ->password("admin#123")
            ->isAdmin(true)
            ->build();

        $olivier = (new EmployeBuilder())
            ->username("lafleuro")
            ->password("admin#123")
            ->color("#ffff00")
            ->email("olivier.lafleur@cll.qc.ca")
            ->firstName("Olivier")
            ->lastName("Lafleur")
            ->isAdmin(true)
            ->image(file_get_contents("usagers_images/sauvage.png"))
            ->build();

        $guillaume = (new EmployeBuilder())
            ->username("michaudg")
            ->password("admin#123")
            ->color("#ff00ff")
            ->email("michaudg@cll.qc.ca")
            ->firstName("Guillaume")
            ->lastName("Michaud")
            ->isAdmin(true)
            ->image(file_get_contents("usagers_images/grandschtroumpf.png"))
            ->build();

        $melissa = (new EmployeBuilder())
            ->username("clermontm")
            ->password("admin#123")
            ->color("#00ffff")
            ->email("melissa.clermont@cll.qc.ca")
            ->firstName("Mélissa")
            ->lastName("Clermont")
            ->image(file_get_contents("usagers_images/schtroumpfette.png"))
            ->build();

        $gilles = (new EmployeBuilder())
            ->username("champagneg")
            ->password("admin#123")
            ->color("#00ff00")
            ->email("gilles.champagne@cll.qc.ca")
            ->firstName("Gilles")
            ->lastName("Champagne")
            ->image(file_get_contents("usagers_images/crayon.png"))
            ->build();

        $josee = (new EmployeBuilder())
            ->username("lainessej")
            ->password("admin#123")
            ->color("#0000ff")
            ->email("josee.lainesse@cll.qc.ca")
            ->firstName("Josée")
            ->lastName("Lainesse")
            ->image(file_get_contents("usagers_images/bebe.png"))
            ->build();

        $marc = (new EmployeBuilder())
            ->username("deslandesm")
            ->password("admin#123")
            ->color("#ffffff")
            ->email("marc.deslandes@cll.qc.ca")
            ->firstName("Marc")
            ->lastName("Deslandes")
            ->image(file_get_contents("usagers_images/fleur.png"))
            ->build();

        $lise = (new EmployeBuilder())
            ->username("provencherl")
            ->password("admin#123")
            ->color("#000000")
            ->email("lise.provencher@cll.qc.ca")
            ->firstName("Lise")
            ->lastName("Provencher")
            ->image(file_get_contents("usagers_images/tada.png"))
            ->build();

        $serge = (new EmployeBuilder())
            ->username("levesques")
            ->password("admin#123")
            ->color("#ff0000")
            ->email("serge.levesque@cll.qc.ca")
            ->firstName("Serge")
            ->lastName("Lévesque")
            ->image(file_get_contents("usagers_images/vantard.png"))
            ->build();

        $employeDao->insert($admin);
        $employeDao->insert($olivier);
        $employeDao->insert($guillaume);
        $employeDao->insert($melissa);
        $employeDao->insert($gilles);
        $employeDao->insert($josee);
        $employeDao->insert($marc);
        $employeDao->insert($lise);
        $employeDao->insert($serge);
    }
}
