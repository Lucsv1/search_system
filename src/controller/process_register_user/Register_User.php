<?php

namespace Admin\Consulta\controller\process_register_user;

use Admin\Consulta\config\Config_db;

class Register_User
{
    private $name_user;
    private $email_user;
    private $senha_user;
    public function __construct($name_user, $email_user, $senha_user)
    {
        $this->setNameUser($name_user);
        $this->setEmailUser($name_user);
        $this->setSenhaUser($senha_user);
    }

    /**
     * Get the value of name_user
     */
    public function getNameUser()
    {
        return $this->name_user;
    }

    /**
     * Set the value of name_user
     */
    public function setNameUser($name_user): self
    {
        $this->name_user = $name_user;

        return $this;
    }

    /**
     * Get the value of email_user
     */
    public function getEmailUser()
    {
        return $this->email_user;
    }

    /**
     * Set the value of email_user
     */
    public function setEmailUser($email_user): self
    {
        $this->email_user = $email_user;

        return $this;
    }

    /**
     * Get the value of senha_user
     */
    public function getSenhaUser()
    {
        return $this->senha_user;
    }

    /**
     * Set the value of senha_user
     */
    public function setSenhaUser($senha_user): self
    {
        $this->senha_user = $senha_user;

        return $this;
    }

    public function insert_user()
    {

        $db = new Config_db();
        $pdo = $db->auth_db();
        
        $name_user = $this->getNameUser();
        $email_user = $this->getEmailUser();
        $senha_user = $this->getSenhaUser();

        $sql = "INSERT INTO usuario (user_login, user_senha, user_permission)";

    }
}
