
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="assets_login/styles/style_login.css">
</head>

<body>
    <header>
        <h2>Login Aluno</h2>
    </header>
    <main>
        <section>
            <div>
                <div>
                    <form action="page_login_to_user.php" method="post">
                        <label for="usuario">Nome de Usuario</label>
                        <input type="text" id="usuario" name="usuario">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha">
                        <input type="submit" value="Entrar">
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script src="assets_login/scripts/script_login.js"></script>
</body>

</html>

<?php ?>

<?php

use Admin\Consulta\controller\process_login_user\Login_User;

require_once("../../../vendor/autoload.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['usuario']) && isset($_POST['senha'])){
        new Login_User($_POST['usuario'], $_POST['senha']);
    }
}