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

Este README oferece uma visão geral do esquema do banco de dados e suas principais funcionalidades, projetado para gerenciar eficientemente as interações entre professores, alunos, cursos, salas e localizações.
