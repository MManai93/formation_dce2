<?php
namespace Entity;

use \OCFram\Entity;

class Member extends Entity
{
    protected   $login,
                $password,
                $passwordcheck,
                $email,
                $dateRegistration,
                $groupe_id, //pour les droits entre admin
                $birthday,
                $adress,
                $city,
                $country;

    const LOGIN_INVALIDE= 1;
    const MDP_INVALIDE=2;

    public function isValid()
    {
        return (!(empty($this->login) || empty($this->password)|| empty($this->email)));

    }

    public function setLogin($login)
    {
        if (!is_string($login) || empty($login))
        {
            $this->erreurs[] = self::LOGIN_INVALIDE;
        }
        $this->login = $login;
    }

    public function setPassword($password)
    {
        if (empty($password))
        {
            $this->erreurs[] = self::MDP_INVALIDE;
        }

        $this->password = $password;
    }

    public function setPasswordcheck($password)
    {
        if (empty($password))
        {
            $this->erreurs[] = self::MDP_INVALIDE;
        }

        $this->passwordcheck = $password;
    }

    public function setEmail($email)
    {
        $this->email=$email;
    }

    public function setDateRegistration(\DateTime $dateRegistration)
    {
        $this->dateRegistration = $dateRegistration;
    }

    public function setGroupe_id($groupe_id)
    {
        $this->groupe_id=$groupe_id;
    }

    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    public function setAdress($adress)
    {
        $this->adress=$adress;
    }

    public function setCity($city)
    {
        $this->city=$city;
    }

    public function setCountry($country)
    {
        $this->country=$country;
    }

    public function login()
    {
        return $this->login;
    }

    public function password()
    {
        return $this->password;
    }

    public function passwordcheck()
    {
        return $this->passwordcheck;
    }

    public function email()
    {
       return $this->email;
    }

    public function dateRegistration()
    {
        return $this->dateRegistration;
    }

    public function groupe_id()
    {
       return $this->groupe_id;
    }

    public function birthday()
    {
       return $this->birthday;
    }

    public function adress()
    {
        return $this->adress;
    }

    public function city()
    {
        return $this->city;
    }

    public function country()
    {
        return $this->country;
    }
}