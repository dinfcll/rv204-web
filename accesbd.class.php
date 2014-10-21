<?php

include 'employedao.class.php';
include_once('constants.php');

class AccesBD
{
    private $pdo;
    private $employeDao;

    public function __construct()
    {
        try {
            $this->pdo = new PDO('sqlite:' . dirname(__FILE__) . BD_PATH);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo "Impossible d'accéder à la base de données SQLite : " . $e->getMessage();
            die();
        }

        $this->employeDao = new EmployeDao();
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
                              email VARCHAR (100),
                              rpiIpLastInteger INTEGER
                              );
                          ");
    }

    public function utilisateurValide($username, $enteredPassword)
    {
        $storedPassword = $this->employeDao->getByUsername($username)->getPassword();

        return password_verify($enteredPassword, $storedPassword);
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

    public function supprimerTable()
    {
        $this->pdo->query("DROP TABLE IF EXISTS users;");
    }

    public function creerUsagersStandards()
    {
        $admin = (new EmployeBuilder())
            ->id(0)
            ->username("admin")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->isAdmin(true)
            ->build();

        $olivier = (new EmployeBuilder())
            ->username("lafleuro")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#0074D9")
            ->email("olivier.lafleur@cll.qc.ca")
            ->firstName("Olivier")
            ->lastName("Lafleur")
            ->isAdmin(true)
            ->image(file_get_contents("http://placekitten.com/300/401"))
            ->rpiIpLastInteger(1)
            ->build();

        $guillaume = (new EmployeBuilder())
            ->username("michaudg")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#7FDBFF")
            ->email("michaudg@cll.qc.ca")
            ->firstName("Guillaume")
            ->lastName("Michaud")
            ->isAdmin(true)
            ->image(file_get_contents("http://placekitten.com/330/440"))
            ->rpiIpLastInteger(127)
            ->build();

        $melissa = (new EmployeBuilder())
            ->username("clermontm")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#2ECC40")
            ->email("melissa.clermont@cll.qc.ca")
            ->firstName("Mélissa")
            ->lastName("Clermont")
            ->image(file_get_contents("http://placekitten.com/363/484"))
            ->build();

        $gilles = (new EmployeBuilder())
            ->username("champagneg")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#01FF70")
            ->email("gilles.champagne@cll.qc.ca")
            ->firstName("Gilles")
            ->lastName("Champagne")
            ->image(file_get_contents("http://placekitten.com/399/532"))
            ->build();

        $josee = (new EmployeBuilder())
            ->username("lainessej")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#FFDC00")
            ->email("josee.lainesse@cll.qc.ca")
            ->firstName("Josée")
            ->lastName("Lainesse")
            ->image(file_get_contents("http://placekitten.com/440/585"))
            ->build();

        $marc = (new EmployeBuilder())
            ->username("deslandesm")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#FF851B")
            ->email("marc.deslandes@cll.qc.ca")
            ->firstName("Marc")
            ->lastName("Deslandes")
            ->image(file_get_contents("http://placekitten.com/399/533"))
            ->build();

        $lise = (new EmployeBuilder())
            ->username("provencherl")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#FF4136")
            ->email("lise.provencher@cll.qc.ca")
            ->firstName("Lise")
            ->lastName("Provencher")
            ->image(file_get_contents("http://placekitten.com/300/402"))
            ->build();

        $serge = (new EmployeBuilder())
            ->username("levesques")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#F012BE")
            ->email("serge.levesque@cll.qc.ca")
            ->firstName("Serge")
            ->lastName("Lévesque")
            ->image(file_get_contents("http://placekitten.com/363/485"))
            ->build();

        $this->employeDao->insert($admin);
        $this->employeDao->put($olivier);
        $this->employeDao->put($guillaume);
        $this->employeDao->put($melissa);
        $this->employeDao->put($gilles);
        $this->employeDao->put($josee);
        $this->employeDao->put($marc);
        $this->employeDao->put($lise);
        $this->employeDao->put($serge);
    }
}
