<?php
include("../config.php");
include("../head.php");
include("../header.php");

?>

<?php
include '../db.php';

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

<?php
include("../../main_format.php");
?>

<div style="margin-top: -40px;" class="row">
    <div class="col-sm-12" >
        <div class="card my-5">
            <div class="card-header">
                <h5>Currículos Cadastrados</h5>
            </div>
            <div class="card-body">
                <a href="formulario_curriculo.php" class="btn btn-primary mb-3">Cadastrar Novo Currículo</a>

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
                                        <a href="./visualizar_curriculo.php?id=<?php echo $dado['id']; ?>"
                                            class="btn btn-outline-primary btn-sm">Ver completo</a>
                                        <a href="formulario_curriculo_edit.php?id=<?php echo $dado['id']; ?>"
                                            class="btn btn-outline-warning btn-sm">Editar</a>
                                        <a href="excluir_curriculo.php?id=<?php echo $dado['id']; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Tem certeza que deseja excluir este currículo?');">Excluir</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mt-3">Nenhum currículo cadastrado.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- editar ate aqui -->
</div>
</div>
</div>

<?php
include("../footer.php");

?>