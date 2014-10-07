<?php

class EmployeBuilder {
    private $id;
    private $firstName = "";
    private $lastName = "";
    private $username = "";
    private $password = "";
    private $color = "";
    private $isAdmin = false;
    private $email = "";
    private $image = "";

    public function color($color)
    {
        $this->color = $color;
        return $this;
    }

    public function email($email)
    {
        $this->email = $email;
        return $this;
    }

    public function firstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function id($id)
    {
        $this->id = $id;
        return $this;
    }

    public function image($image)
    {
        $this->image = $image;
        return $this;
    }

    public function isAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function lastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function password($password)
    {
        $this->password = $password;
        return $this;
    }

    public function username($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsername()
    {
        return $this->username;
    }

    function build() {
        return new Employe($this);
    }
}

class Employe {

    private $id;
    private $firstName;
    private $lastName;
    private $username;
    private $password;
    private $color;
    private $isAdmin;
    private $email;
    private $image;

    function __construct(EmployeBuilder $employeBuilder)
    {
        $this->id = $employeBuilder->getId();
        $this->firstName = $employeBuilder->getFirstName();
        $this->lastName = $employeBuilder->getLastName();
        $this->username = $employeBuilder->getUsername();
        $this->password = $employeBuilder->getPassword();
        $this->color = $employeBuilder->getColor();
        $this->isAdmin = $employeBuilder->getIsAdmin();
        $this->email = $employeBuilder->getEmail();
        $this->image = $employeBuilder->getImage();
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function isAdmin()
    {
        return $this->isAdmin;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getUsername()
    {
        return $this->username;
    }


} 
