<?php

namespace Admin\Consulta\controller\process_login_user;

use Admin\Consulta\config\Config_db;
use PDO;

class Login_User
{

    private $email_user;
    private $senha_user;

    public function __construct($email_user, $senha_user)
    {
        $this->setEmailUser($email_user);
        $this->setSenhaUser($senha_user);

        $this->check_user_exists($this->getEmailUser(), $this->getSenhaUser());
    }

    /**
     * Get the value of name_user
     */


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

    public function check_user_exists($user_login, $user_senha)
    {
        $db = new Config_db();
        $pdo = $db->auth_db();

        // Verifica se o email e a senha já existem
        $check_sql = "SELECT ID_user FROM usuario WHERE user_login = :user_login AND user_senha = :user_senha";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindValue(':user_login', $user_login);
        $check_stmt->bindValue(':user_senha', $user_senha);
        $check_stmt->execute();

        $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            session_start();
            $_SESSION['ID_user'] = $result['ID_user'];
            ?> <script>
            window.location.href='../../public/pages/user/page_search_teacher.php';
            </script> <?php
        } else {
            // Usuário não encontrado
            echo "Usuário não encontrado.";
            
        }
    }
}
