<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: ../../login.php");
    exit();
}
?>
<?php
include 'db.php';

$sql = "SELECT dp.id, dp.nome, 
               f.grau, f.curso, 
               e.cargo, 
               i.idioma, i.nivel
        FROM dados_pessoais dp
        LEFT JOIN formacoes f ON f.dados_pessoais_id = dp.id
        LEFT JOIN experiencias_profissionais e ON e.dados_pessoais_id = dp.id
        LEFT JOIN idiomas i ON i.dados_pessoais_id = dp.id";
$stmt = $pdo->query($sql);

$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Currículos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container my-5">
        <h1 class="mb-4">Currículos Cadastrados</h1>

        <a href="formulario_curriculo.php" class="btn btn-primary mb-4">Cadastrar Novo Currículo</a>

        <?php if (count($dados) > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($dados as $dado): ?>
                    <div class="col">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($dado['nome']); ?></h5>
                                <p class="card-text">
                                    <strong>Formação:</strong>
                                    <?php echo htmlspecialchars($dado['grau'] ?? 'Não informado'); ?><br>
                                    <strong>Cargo:</strong>
                                    <?php echo htmlspecialchars($dado['cargo'] ?? 'Não informado'); ?><br>
                                    <strong>Idioma:</strong>
                                    <?php echo htmlspecialchars($dado['idioma'] ?? 'Não informado') . ' (' . htmlspecialchars($dado['nivel'] ?? '-') . ')'; ?>
                                </p>
                                <a href="visualizar_curriculo.php?id=<?php echo $dado['id']; ?>"
                                    class="btn btn-outline-primary btn-sm">Ver completo</a>
                                <a href="formulario_curriculo_edit.php?id=<?php echo $dado['id']; ?>"
                                    class="btn btn-outline-secondary btn-sm">Editar</a>
                                <a href="excluir_curriculo.php?id=<?php echo $dado['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Tem certeza que deseja excluir este currículo?');">Excluir</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Nenhum currículo cadastrado.</div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS via CDN (opcional, caso use componentes JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>