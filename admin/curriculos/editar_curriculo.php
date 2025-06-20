<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../login.php");
    exit();
}

include '../db.php';

// Função para tratar datas inválidas
function tratarData($data) {
    return ($data === "" || $data === "0000-00-00") ? null : $data;
}

// Obter ID do currículo
$dados_pessoais_id = $_POST['id'] ?? null;
if (!$dados_pessoais_id) {
    die("ID do currículo não informado.");
}

// Dados pessoais
$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$rg = $_POST['rg'];
$data_nascimento = tratarData($_POST['data_nascimento']);
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$endereco = $_POST['endereco'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];
$nacionalidade = $_POST['nacionalidade'];
$estado_civil = $_POST['estado_civil'];
$genero = $_POST['genero'];

// Formação
$grau = $_POST['grau'];
$curso = $_POST['curso'];
$instituicao = $_POST['instituicao'];
$ano_inicio = $_POST['ano_inicio'];
$ano_conclusao = $_POST['ano_conclusao'] ?? null;
$concluido = $_POST['concluido'];

// Experiência
$empresa = $_POST['empresa'];
$cargo = $_POST['cargo'];
$atividades = $_POST['atividades'];
$data_inicio = tratarData($_POST['data_inicio']);
$data_fim = tratarData($_POST['data_fim']);
$atual = $_POST['atual'];

// Idioma
$idioma = $_POST['idioma'];
$nivel = $_POST['nivel'];

try {
    $pdo->beginTransaction();

    // Atualizar dados pessoais
    $stmt = $pdo->prepare("UPDATE dados_pessoais SET 
        nome = ?, cpf = ?, rg = ?, data_nascimento = ?, email = ?, telefone = ?, endereco = ?, 
        cidade = ?, estado = ?, cep = ?, nacionalidade = ?, estado_civil = ?, genero = ?
        WHERE id = ?");
    $stmt->execute([$nome, $cpf, $rg, $data_nascimento, $email, $telefone, $endereco, $cidade, $estado, $cep, $nacionalidade, $estado_civil, $genero, $dados_pessoais_id]);

    // Atualizar formação
    $stmt = $pdo->prepare("UPDATE formacoes SET 
        grau = ?, curso = ?, instituicao = ?, ano_inicio = ?, ano_conclusao = ?, concluido = ?
        WHERE dados_pessoais_id = ?");
    $stmt->execute([$grau, $curso, $instituicao, $ano_inicio, $ano_conclusao, $concluido, $dados_pessoais_id]);

    // Atualizar experiência
    $stmt = $pdo->prepare("UPDATE experiencias_profissionais SET 
        empresa = ?, cargo = ?, atividades = ?, data_inicio = ?, data_fim = ?, atual = ?
        WHERE dados_pessoais_id = ?");
    $stmt->execute([$empresa, $cargo, $atividades, $data_inicio, $data_fim, $atual, $dados_pessoais_id]);

    // Atualizar idioma
    $stmt = $pdo->prepare("UPDATE idiomas SET 
        idioma = ?, nivel = ?
        WHERE dados_pessoais_id = ?");
    $stmt->execute([$idioma, $nivel, $dados_pessoais_id]);

    $pdo->commit();
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Currículo Atualizado</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="alert alert-success text-center" role="alert">
                <h4 class="alert-heading">Alterações salvas!</h4>
                <p>O currículo foi atualizado com sucesso.</p>
            </div>
            <div class="row justify-content-center mt-4">
                <div class="col-md-3 d-grid mb-2">
                    <a href="formulario_curriculo_edit.php?id=<?= $dados_pessoais_id ?>" class="btn btn-primary">Editar novamente</a>
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
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Erro ao Atualizar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="alert alert-danger text-center" role="alert">
                <h4 class="alert-heading">Erro!</h4>
                <p>Não foi possível atualizar o currículo.</p>
                <hr>
                <p class="mb-0"><?= htmlspecialchars($e->getMessage()) ?></p>
            </div>
            <div class="text-center mt-4">
                <a href="index.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </body>
    </html>

    <?php
}
?>
