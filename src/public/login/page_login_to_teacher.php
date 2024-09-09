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
        <h2>Login Professor</h2>
    </header>
    <main>
        <section>
            <div class="login_teacher_base">
                <div>
                    <form action="page_login_to_teacher.php" method="post">
                        <label for="email">Email:</label>
                        <input type="text" id="email" name="email">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha">
                        <input class="button_teacher_login" type="submit" value="Entrar">
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script src="assets_login/scripts/script_login.js"></script>
</body>

</html>

<?php

use Admin\Consulta\controller\process_login_teacher\Login_Teacher;

require_once("../../../vendor/autoload.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['senha'])){
        $login = new Login_Teacher($_POST['email'], $_POST['senha']);
    }
}

?>