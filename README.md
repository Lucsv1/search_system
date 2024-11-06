# Descrição do Banco de Dados do Sistema de Gestão Escolar

Este banco de dados é projetado para gerenciar informações sobre professores, alunos (usuários), cursos, salas e localizações. Abaixo está uma descrição de cada tabela e suas funções.

## Tabelas

### 1. Tabela `professor`

Armazena informações sobre os professores.

- **Colunas**:
  - `ID_pro`: Identificador único do professor (chave primária).
  - `pro_email`: E-mail do professor.
  - `pro_nome`: Nome do professor.
  - `pro_tag`: Tags ou observações relacionadas ao professor.

### 2. Tabela `usuario`

Armazena informações sobre os alunos (usuários) que interagem com os professores.

- **Colunas**:
  - `ID_user`: Identificador único do usuário (chave primária).
  - `user_login`: Nome de login do usuário.
  - `user_senha`: Senha do usuário.
  - `user_perission`: Permissões ou papéis do usuário.
  - `ID_pro`: Identificador do professor associado ao usuário (chave estrangeira que referencia `professor.ID_pro`).

### 3. Tabela `localizacao`

Armazena as informações de localização relacionadas aos horários e lugares onde os professores estão.

- **Colunas**:
  - `loc_data`: Data do evento ou aula.
  - `loc_hora`: Hora do evento ou aula.
  - `ID_pro`: Identificador do professor (chave estrangeira que referencia `professor.ID_pro`).
  - `ID_sala`: Identificador da sala onde o evento ocorre (chave estrangeira que referencia `sala.ID_sala`).

### 4. Tabela `sala`

Armazena informações sobre as salas onde ocorrem as aulas e eventos.

- **Colunas**:
  - `ID_sala`: Identificador único da sala (chave primária).
  - `sala_numero`: Número ou identificação da sala.

### 5. Tabela `curso`

Armazena informações sobre os cursos oferecidos.

- **Colunas**:
  - `ID_curso`: Identificador único do curso (chave primária).
  - `curso_name`: Nome do curso.

### 6. Tabela `professor_por_curso`

Estabelece a relação entre professores e cursos. Um professor pode ensinar vários cursos, e um curso pode ser ensinado por vários professores.

- **Colunas**:
  - `ID_curso`: Identificador do curso (chave estrangeira que referencia `curso.ID_curso`).
  - `ID_pro`: Identificador do professor (chave estrangeira que referencia `professor.ID_pro`).

## Relacionamentos

1. **Um professor pode ter muitos usuários associados a ele** (Tabela `usuario`).
2. **Um professor pode estar associado a muitas localizações** (Tabela `localizacao`).
3. **Uma sala pode ter muitas localizações associadas a ela** (Tabela `localizacao`).
4. **Um curso pode ter muitos professores e um professor pode estar associado a muitos cursos** através da tabela intermediária `professor_por_curso`.

---

# Middleware

O middleware deste projeto é responsável por fazer a comunicação entre o servidor (onde todos os serviços estão rodando) e a estrutura de banco de dados. Este middleware foi projetado para identificar os leitores e recuperar os dados enviados pelos microcontroladores. Para este projeto, foi utilizado apenas um microcontrolador, tornando os dados manipuláveis pelo sistema conforme detalhado abaixo.

O middleware foi desenvolvido usando PHP para conexão com o banco de dados MariaDB. Ele roda continuamente em modo daemon, ou seja, o programa está sempre em execução. Quando algum dado é enviado do microcontrolador, o middleware recebe os dados, trata-os e os armazena no banco de dados.

## Estrutura do Middleware

1. **Configuração do Banco de Dados**:
    - Conecta-se ao banco de dados MariaDB usando PHP.

2. **Verificação de Sessão**:
    - Verifica se o ID do usuário ou professor está definido na sessão.

3. **Leitura e Processamento dos Dados**:
    - Lê os dados enviados pelo microcontrolador.
    - Processa e armazena os dados no banco de dados.

### Implementação

Aqui está um exemplo de código que representa o middleware, com base nos códigos fornecidos:

```php
<?php

require_once("../../../../vendor/autoload.php");

use Admin\Consulta\config\Config_db;

session_start();

// Verifica se o ID do usuário ou professor está na sessão. Se não estiver, bloqueia o acesso.
if (!isset($_SESSION['ID_user']) && !isset($_SESSION['ID_pro'])) {
    echo "Acesso negado. Você precisa fazer login.";
    exit();
}

// Conexão com o Banco de Dados
$db = new Config_db();
$pdo = $db->auth_db();

// Verifica se é um professor
if (isset($_SESSION['ID_pro'])) {
    $id_pro = $_SESSION['ID_pro'];
    
    $sql = "SELECT * FROM professor WHERE ID_pro = :ID_pro";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID_pro', $id_pro, PDO::PARAM_INT);
    $stmt->execute();

    $professor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($professor) {
        // Processa dados relacionados ao professor
        processProfessorData($pdo, $id_pro);
    } else {
        echo "Professor não encontrado.";
    }
}

// Verifica se é um usuário
if (isset($_SESSION['ID_user'])) {
    $id_user = $_SESSION['ID_user'];
    
    $sql = "SELECT * FROM usuario WHERE ID_user = :ID_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();

    $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($aluno) {
        // Processa dados relacionados ao usuário
        processUserData($pdo, $aluno);
    } else {
        echo "Usuário não encontrado.";
    }
}

function processProfessorData($pdo, $id_pro) {
    // Exemplo de processamento de dados do professor
    $sql_teacher = "SELECT * FROM professor WHERE ID_pro = :ID_pro";
    $stmt_teacher = $pdo->prepare($sql_teacher);
    $stmt_teacher->bindValue(':ID_pro', $id_pro);
    $stmt_teacher->execute();

    $teachers = $stmt_teacher->fetchAll(PDO::FETCH_ASSOC);

    // Outras operações relacionadas ao professor
}

function processUserData($pdo, $aluno) {
    // Exemplo de processamento de dados do usuário
    $sql_teacher = "SELECT * FROM professor WHERE ID_pro = :ID_pro";
    $stmt_teacher = $pdo->prepare($sql_teacher);
    $stmt_teacher->bindValue(':ID_pro', $aluno['ID_pro']);
    $stmt_teacher->execute();

    $teachers = $stmt_teacher->fetchAll(PDO::FETCH_ASSOC);

    // Outras operações relacionadas ao usuário
}

// Funções auxiliares usadas para obter e processar dados
function get_searchs_to_user($pdo, $id_pro) {
    $sql_search = "SELECT * FROM localizacao WHERE ID_pro = :ID_pro";
    $stmt_search = $pdo->prepare($sql_search);
    $stmt_search->bindValue(':ID_pro', $id_pro);
    $stmt_search->execute();

    return $stmt_search->fetchAll(PDO::FETCH_ASSOC);
}

function get_number_class($pdo, $id) {
    $sql_sala_get_number = "SELECT sala_numero FROM sala WHERE sala_numero = :sala_numero";
    $stmt_get_number = $pdo->prepare($sql_sala_get_number);
    $stmt_get_number->bindValue(':sala_numero', $id);
    $stmt_get_number->execute();

    return $stmt_get_number->fetch(PDO::FETCH_ASSOC)['sala_numero'];
}

function get_course_by_teacher_id($pdo, $id_pro, $index) {
    $sql_course_ = "SELECT ID_curso FROM professor_por_curso WHERE ID_pro = :ID_pro";
    $stmt_course_ = $pdo->prepare($sql_course_);
    $stmt_course_->bindValue(':ID_pro', $id_pro);
    $stmt_course_->execute();

    $get_id_course = $stmt_course_->fetchAll(PDO::FETCH_ASSOC);
    $sql_get_name_course = "SELECT curso_name FROM curso WHERE ID_curso = :ID_curso";
    $stmt_get_name = $pdo->prepare($sql_get_name_course);
    $stmt_get_name->bindValue(':ID_curso', $get_id_course[$index]['ID_curso']);
    $stmt_get_name->execute();

    return $stmt_get_name->fetch(PDO::FETCH_ASSOC)['curso_name'];
}

?>

```
# Camada Usuário

A camada de usuário é a camada de mais alto nível da aplicação, responsável por fornecer a interface final que os usuários irão interagir. Ela abstrai o funcionamento das camadas inferiores, permitindo que os usuários utilizem o sistema para localizar professores e acessar informações relevantes.

## Aplicação
A aplicação resultante é baseada em consultas SQL que mostram as últimas localizações dos professores. O usuário seleciona a busca desejada, os dados são recuperados do banco de dados através do middleware, e as informações são apresentadas ao usuário final.

### Estrutura da Aplicação
- **Desenvolvimento**: Utilizando PHP 7.x com Apache Server, instalado através do programa XAMPP. O desenvolvimento foi realizado em um ambiente de IDE como o Visual Studio Code.
- **Conexão com Banco de Dados**: A conexão foi estabelecida com o banco de dados MariaDB.
- **Tecnologias Adicionais**: HTML, CSS e JavaScript para a interface do usuário.


#### index.php
- **Função**: Este arquivo é responsável por estabelecer a conexão com o banco de dados e estruturar as páginas. Ele define o design e a estrutura base, incluindo o cabeçalho, links de navegação e rodapé. As demais páginas são incluídas dinamicamente neste arquivo.

