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
                              picture BLOB,
                              isAdmin INTEGER,
                              email VARCHAR (100),
                              hasRpi INTEGER
                              );
                          ");
    }

    public function creerConfigurations()
    {
        $this->pdo->query("CREATE TABLE IF NOT EXISTS config (
                              id INTEGER PRIMARY KEY AUTOINCREMENT,
                              rpiNetwork VARCHAR(20),
                              mailgunDomain VARCHAR(100),
                              mailgunApiKey VARCHAR(100),
                              db_updated INTEGER
                              );
                          ");

        $this->pdo->query("INSERT INTO config (id, rpiNetwork, mailgunDomain, mailgunApiKey, db_updated)
                            VALUES (1, '192.168.0.0', 'dinf.qc.to', 'key-8gn080hwlfdud6ctmtm1d66ulwg4p0t9', 1)");

        $this->pdo->query("CREATE TRIGGER update_db_insert AFTER INSERT ON users
                            BEGIN
                                UPDATE config SET db_updated = 1;
                            END;
                            ");

        $this->pdo->query("CREATE TRIGGER update_db_update AFTER UPDATE ON users
                            BEGIN
                                UPDATE config SET db_updated = 1;
                            END;
                            ");

        $this->pdo->query("CREATE TRIGGER update_db_delete AFTER DELETE ON users
                            BEGIN
                                UPDATE config SET db_updated = 1;
                            END;
                            ");
    }

    public function modifierRpiNetwork($rpiNetwork)
    {
        $this->pdo->query("UPDATE config
                            SET rpiNetwork='".$rpiNetwork."'
                            WHERE id=1");
    }

    public function getRpiNetwork()
    {
        $stmt = $this->pdo->prepare("SELECT rpiNetwork FROM config WHERE id = 1");
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['rpiNetwork'];
    }

    public function modifierMailgunDomain($mailgunDomain)
    {
        $this->pdo->query("UPDATE config
                            SET mailgunDomain='".$mailgunDomain."'
                            WHERE id=1");
    }

    public function getMailgunDomain()
    {
        $stmt = $this->pdo->prepare("SELECT mailgunDomain FROM config WHERE id = 1");
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['mailgunDomain'];
    }

    public function modifierMailgunApiKey($mailgunApiKey)
    {
        $this->pdo->query("UPDATE config
                            SET mailgunApiKey='".$mailgunApiKey."'
                            WHERE id=1");
    }

    public function getMailgunApiKey()
    {
        $stmt = $this->pdo->prepare("SELECT mailgunApiKey FROM config WHERE id = 1");
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result[0]['mailgunApiKey'];
    }

    public function utilisateurValide($username, $enteredPassword)
    {
        $storedPassword = $this->employeDao->getByUsername($username)->getPassword();

        return password_verify($enteredPassword, $storedPassword);
    }

    public function genererImage($id) {
        $stmt = $this->pdo->prepare("SELECT picture FROM users WHERE id = " . $id);
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result[0]['picture'];
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

    public function pasDeConfig()
    {
        try {
            $this->pdo->prepare("SELECT 1 FROM config LIMIT 1");
        } catch (Exception $e) {
            return true;
        }

        return false;
    }

    public function supprimerTables()
    {
        $this->pdo->query("DROP TABLE IF EXISTS users;");
        $this->pdo->query("DROP TABLE IF EXISTS config;");
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
            ->picture(file_get_contents("usagers_images/sauvage.png"))
            ->hasRpi(true)
            ->build();

        $guillaume = (new EmployeBuilder())
            ->username("michaudg")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#7FDBFF")
            ->email("guillaume.michaud@cll.qc.ca")
            ->firstName("Guillaume")
            ->lastName("Michaud")
            ->isAdmin(true)
            ->picture(file_get_contents("usagers_images/gargamel.jpeg"))
            ->hasRpi(true)
            ->build();

        $melissa = (new EmployeBuilder())
            ->username("clermontm")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#2ECC40")
            ->email("melissa.clermont@cll.qc.ca")
            ->firstName("Mélissa")
            ->lastName("Clermont")
            ->picture(file_get_contents("usagers_images/schtroumpfette.png"))
            ->build();

        $gilles = (new EmployeBuilder())
            ->username("champagneg")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#01FF70")
            ->email("gilles.champagne@cll.qc.ca")
            ->firstName("Gilles")
            ->lastName("Champagne")
            ->picture(file_get_contents("usagers_images/grandschtroumpf.png"))
            ->build();

        $josee = (new EmployeBuilder())
            ->username("lainessej")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#FFDC00")
            ->email("josee.lainesse@cll.qc.ca")
            ->firstName("Josée")
            ->lastName("Lainesse")
            ->picture(file_get_contents("usagers_images/bebe.png"))
            ->build();

        $marc = (new EmployeBuilder())
            ->username("deslandesm")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#FF851B")
            ->email("marc.deslandes@cll.qc.ca")
            ->firstName("Marc")
            ->lastName("Deslandes")
            ->picture(file_get_contents("usagers_images/fleur.png"))
            ->build();

        $lise = (new EmployeBuilder())
            ->username("provencherl")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#FF4136")
            ->email("lise.provencher@cll.qc.ca")
            ->firstName("Lise")
            ->lastName("Provencher")
            ->picture(file_get_contents("usagers_images/tada.png"))
            ->build();

        $serge = (new EmployeBuilder())
            ->username("levesques")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#F012BE")
            ->email("serge.levesque@cll.qc.ca")
            ->firstName("Serge")
            ->lastName("Lévesque")
            ->picture(file_get_contents("usagers_images/vantard.png"))
            ->build();

        $yvan = (new EmployeBuilder())
            ->username("morrisseyy")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#0074D9")
            ->email("yvan.morrissey@cll.qc.ca")
            ->firstName("Yvan")
            ->lastName("Morrissey")
            ->picture(file_get_contents("usagers_images/tada.png"))
            ->build();

        $mers = (new EmployeBuilder())
            ->username("merciers")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#7FDBFF")
            ->email("stephane.mercier@cll.qc.ca")
            ->firstName("Stéphane")
            ->lastName("Mercier")
            ->picture(file_get_contents("usagers_images/fleur.png"))
            ->build();

        $christian = (new EmployeBuilder())
            ->username("asselinc")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#2ECC40")
            ->email("christian.asselin@cll.qc.ca")
            ->firstName("Christian")
            ->lastName("Asselin")
            ->picture(file_get_contents("usagers_images/vantard.png"))
            ->build();

        $nelson = (new EmployeBuilder())
            ->username("marceaun")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#01FF70")
            ->email("nelson.marceau@cll.qc.ca")
            ->firstName("Nelson")
            ->lastName("Marceau")
            ->picture(file_get_contents("usagers_images/bebe.png"))
            ->build();

        $jocelyne = (new EmployeBuilder())
            ->username("lapointej")
            ->password(password_hash("admin#123", PASSWORD_DEFAULT))
            ->color("#FFDC00")
            ->email("jocelyne.lapointe@cll.qc.ca")
            ->firstName("Jocelyne")
            ->lastName("Lapointe")
            ->picture(file_get_contents("usagers_images/schtroumpfette.png"))
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
        $this->employeDao->put($yvan);
        $this->employeDao->put($mers);
        $this->employeDao->put($christian);
        $this->employeDao->put($nelson);
        $this->employeDao->put($jocelyne);
    }
}
