<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../login.php");
    exit();
}
?>
<?php
// 1. Conexão com o banco de dados
include 'db.php';

// 2. Coletar dados do formulário
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];
$data_nascimento = $_POST['data_nascimento'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];
$nacionalidade = $_POST['nacionalidade'];
$estado_civil = $_POST['estado_civil'];
$genero = $_POST['genero'];

// 3. Dados de formação
$grau = $_POST['grau'];
$curso = $_POST['curso'];
$instituicao = $_POST['instituicao'];
$ano_inicio = $_POST['ano_inicio'];
$ano_conclusao = isset($_POST['ano_conclusao']) && !empty($_POST['ano_conclusao']) ? $_POST['ano_conclusao'] : null;
$concluido = $_POST['concluido'];

// 4. Dados de experiência
$empresa = $_POST['empresa'];
$cargo = $_POST['cargo'];
$atividades = $_POST['atividades'];
$data_inicio = $_POST['data_inicio'];
$data_fim = isset($_POST['data_fim']) && !empty($_POST['data_fim']) ? $_POST['data_fim'] : null;
$atual = $_POST['atual'];

// 5. Dados de idioma
$idioma = $_POST['idioma'];
$nivel = $_POST['nivel'];

try {
    $pdo->beginTransaction();

    // Inserir dados pessoais
    $stmt = $pdo->prepare("INSERT INTO dados_pessoais (nome, cpf, rg, data_nascimento, email, telefone, endereco, cidade, estado, cep, nacionalidade, estado_civil, genero) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $cpf, $rg, $data_nascimento, $email, $telefone, $endereco, $cidade, $estado, $cep, $nacionalidade, $estado_civil, $genero]);
    $dados_pessoais_id = $pdo->lastInsertId();

    // Inserir formação
    $stmt = $pdo->prepare("INSERT INTO formacoes (dados_pessoais_id, grau, curso, instituicao, ano_inicio, ano_conclusao, concluido) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$dados_pessoais_id, $grau, $curso, $instituicao, $ano_inicio, $ano_conclusao, $concluido]);

    // Inserir experiência
    $stmt = $pdo->prepare("INSERT INTO experiencias_profissionais (dados_pessoais_id, empresa, cargo, atividades, data_inicio, data_fim, atual) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$dados_pessoais_id, $empresa, $cargo, $atividades, $data_inicio, $data_fim, $atual]);

    // Inserir idioma
    $stmt = $pdo->prepare("INSERT INTO idiomas (dados_pessoais_id, idioma, nivel) 
        VALUES (?, ?, ?)");
    $stmt->execute([$dados_pessoais_id, $idioma, $nivel]);

    $pdo->commit();
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Currículo Salvo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="alert alert-success text-center" role="alert">
                <h4 class="alert-heading">Sucesso!</h4>
                <p>Currículo salvo com sucesso.</p>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-md-3 d-grid mb-2">
                    <a href="formulario_curriculo.php" class="btn btn-primary">Cadastrar outro</a>
                </div>
                <div class="col-md-3 d-grid mb-2">
                    <a href="index.php" class="btn btn-secondary">Retornar à página inicial</a>
                </div>
            </div>
        </div>
    </body>
    </html>

    <?php
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro ao salvar currículo: " . $e->getMessage());
}
?>
