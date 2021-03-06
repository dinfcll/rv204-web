<?php

include "employe.class.php";
include_once('constants.php');

class EmployeDao
{
    private $pdo;

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
    }

    public function deleteById($id)
    {
        $this->pdo->query("DELETE FROM users
                           WHERE id=" . $id);
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id > 0");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function put(Employe $employe)
    {
        if($employe->getId() != null) {
            $this->update($employe);
        } else {
            $this->insert($employe);
        }
    }

    public function insert(Employe $employe)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (firstName, lastName, username, password, color, isAdmin, email, picture, id, hasRpi)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bindValue(1, $employe->getFirstName());
        $stmt->bindValue(2, $employe->getLastName());
        $stmt->bindValue(3, $employe->getUsername());
        $stmt->bindValue(4, $employe->getPassword());
        $stmt->bindValue(5, $employe->getColor());
        $stmt->bindValue(6, $employe->isAdmin());
        $stmt->bindValue(7, $employe->getEmail());
        $stmt->bindValue(8, $employe->getPicture(), PDO::PARAM_LOB);
        $stmt->bindValue(9, $employe->getId());
        $stmt->bindValue(10, $employe->getHasRpi());

        $stmt->execute();
    }

    private function update(Employe $employe)
    {
        $stmt = $this->pdo->prepare("UPDATE users
                                     SET firstName='" . $employe->getFirstName() . "',
                                         lastName='" . $employe->getLastName() . "',
                                         username='" . $employe->getUsername() . "',
                                         password='" . $employe->getPassword() . "',
                                         isAdmin=" . $employe->isAdmin() . ",
                                         color='" . $employe->getColor() . "',
                                         email='" . $employe->getEmail() . "',
                                         hasRpi='" . $employe->getHasRpi() . "',
                                         picture=?
                                     WHERE id=" . $employe->getId());
        $stmt->bindValue(1, $employe->getPicture(), PDO::PARAM_LOB);
        $stmt->execute();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = " . $id);
        $stmt->execute();

        $result = $stmt->fetchAll();

        if(sizeof($result) > 0) {
            return $this->convertDbToEmploye($result[0]);
        } else {
            return (new EmployeBuilder)->build();
        }
    }

    public function getByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = '" . $username . "'");
        $stmt->execute();

        $result = $stmt->fetchAll();

        if(sizeof($result) > 0) {
            return $this->convertDbToEmploye($result[0]);
        } else {
            return (new EmployeBuilder)->build();
        }
    }

    private function convertDbToEmploye($donnees) {
        $employe = (new EmployeBuilder())
            ->id($donnees['id'])
            ->username($donnees['username'])
            ->password($donnees['password'])
            ->color($donnees['color'])
            ->email($donnees['email'])
            ->firstName($donnees['firstName'])
            ->lastName($donnees['lastName'])
            ->isAdmin($donnees['isAdmin'])
            ->picture($donnees['picture'])
            ->hasRpi($donnees['hasRpi'])
            ->build();

        return $employe;
    }
} 
