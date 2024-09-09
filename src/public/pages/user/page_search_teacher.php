<?php

require_once("../../../../vendor/autoload.php");

use Admin\Consulta\config\Config_db;

session_start();

if (!isset($_SESSION['ID_user'])) {
    echo "Acesso negado. Você precisa fazer login.";
    exit();
}

$db = new Config_db();
$pdo = $db->auth_db();

$sql = "SELECT * FROM usuario WHERE ID_user = :ID_user";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':ID_user', $_SESSION['ID_user'], PDO::PARAM_INT);
$stmt->execute();

$aluno = $stmt->fetch(PDO::FETCH_ASSOC);



if ($aluno) :

    $sql_teacher = "SELECT * FROM professor WHERE ID_pro = :ID_pro";
    $stmt_teacher = $pdo->prepare($sql_teacher);

    $stmt_teacher->bindValue(':ID_pro', $aluno['ID_pro']);
    $stmt_teacher->execute();

    $teachers = $stmt_teacher->fetchAll(PDO::FETCH_ASSOC);


    function get_searchs_to_user($pdo, $id_pro)
    {
        $sql_search = "SELECT * FROM localizacao WHERE ID_pro = :ID_pro";
        $stmt_search = $pdo->prepare($sql_search);

        $stmt_search->bindValue(':ID_pro', $id_pro);
        $stmt_search->execute();

        $searchs =  $stmt_search->fetchAll(PDO::FETCH_ASSOC);

        return $searchs;
    }

    function get_number_class($pdo, $id)
    {
        $sql_sala_get_number = "SELECT sala_numero FROM sala WHERE sala_numero = :sala_numero";
        $stmt_get_number = $pdo->prepare($sql_sala_get_number);

        $stmt_get_number->bindValue(':sala_numero', $id);

        $stmt_get_number->execute();

        $number_class = $stmt_get_number->fetch(PDO::FETCH_ASSOC);

        return $number_class['sala_numero'];
    }

    function get_course_by_teacher_id($pdo, $id_pro, $index){
        $sql_course_ = "SELECT ID_curso FROM professor_por_curso WHERE ID_pro = :ID_pro";
        $stmt_course_ = $pdo->prepare($sql_course_);

        $stmt_course_->bindValue(':ID_pro', $id_pro);
        $stmt_course_->execute();

        $get_id_course = $stmt_course_->fetchAll(PDO::FETCH_ASSOC);


        $sql_get_name_course = "SELECT curso_name FROM curso WHERE ID_curso = :ID_curso";
        $stmt_get_name = $pdo->prepare($sql_get_name_course);

        $stmt_get_name->bindValue(':ID_curso', $get_id_course[$index]['ID_curso']);

        $stmt_get_name->execute();

        $get_name_course = $stmt_get_name->fetch(PDO::FETCH_ASSOC);

        return $get_name_course['curso_name'];

    }


    $searchs = get_searchs_to_user($pdo, $aluno['ID_pro'])

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Localização Professor</title>
    </head>

    <body>
        <header>
        <section>
                <h2>Bem vindo: <?php echo htmlspecialchars($aluno['user_login']) ?></h2>
            </section>
        </header>
        <main>
            <section>
                <?php foreach ($teachers as $teacher): ?>
                    <span>Seu Professor: <?php echo $teacher['pro_nome'] ?></span>
                <?php endforeach ?>
            </section>
            <section>
                <h3>Horarios das aulas</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Sala</th>
                            <th>Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchs as $index_s => $search): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($search['loc_data']);  ?></td>
                                <td><?php echo htmlspecialchars($search['loc_hora']);  ?></td>
                                <td><?php echo get_number_class($pdo, $search['ID_sala']) ?></td>
                                <td><?php echo get_course_by_teacher_id($pdo, $search['ID_pro'], $index_s) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </section>
        </main>
    </body>

    </html>

<?php else : ?>
    <div>
        <span>ERRO</span>
    </div>
<?php endif ?>