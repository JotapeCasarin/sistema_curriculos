<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../login.php");
    exit();
}
?>
<?php
include '../db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Exclui primeiro as dependências (formações, experiências, idiomas)
    $stmt = $pdo->prepare("DELETE FROM formacoes WHERE dados_pessoais_id = ?");
    $stmt->execute([$id]);

    $stmt = $pdo->prepare("DELETE FROM experiencias_profissionais WHERE dados_pessoais_id = ?");
    $stmt->execute([$id]);

    $stmt = $pdo->prepare("DELETE FROM idiomas WHERE dados_pessoais_id = ?");
    $stmt->execute([$id]);

    // Agora exclui os dados pessoais
    $stmt = $pdo->prepare("DELETE FROM dados_pessoais WHERE id = ?");
    $stmt->execute([$id]);

    // Redireciona de volta pra index
    header("Location: index.php");
    exit;
} else {
    echo "ID inválido!";
}
?>
