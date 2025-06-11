<?php
include("../config.php");
require_once("../db.php");
include("../head.php");
include("../header.php");


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do currículo inválido.");
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT dp.*, f.grau, f.curso, f.instituicao, f.ano_inicio, f.ano_conclusao, f.concluido,
           e.empresa, e.cargo, e.atividades, e.data_inicio, e.data_fim, e.atual,
           i.idioma, i.nivel
    FROM dados_pessoais dp
    LEFT JOIN formacoes f ON f.dados_pessoais_id = dp.id
    LEFT JOIN experiencias_profissionais e ON e.dados_pessoais_id = dp.id
    LEFT JOIN idiomas i ON i.dados_pessoais_id = dp.id
    WHERE dp.id = ?
");
$stmt->execute([$id]);
$curriculo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curriculo) {
    die("Currículo não encontrado.");
}

?>

<?php
include("../../main_format.php");
?>
      
<div class="container mt-5 mb-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Currículo de <?php echo htmlspecialchars($curriculo['nome']); ?></h4>
        </div>
        <div class="card-body">
            <h5 class="text-secondary border-bottom pb-1 mb-3">Dados Pessoais</h5>
            <p><strong>CPF:</strong> <?php echo $curriculo['cpf']; ?></p>
            <p><strong>RG:</strong> <?php echo $curriculo['rg']; ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo $curriculo['data_nascimento']; ?></p>
            <p><strong>Email:</strong> <?php echo $curriculo['email']; ?></p>
            <p><strong>Telefone:</strong> <?php echo $curriculo['telefone']; ?></p>
            <p><strong>Endereço:</strong> <?php echo $curriculo['endereco'] . ", " . $curriculo['cidade'] . " - " . $curriculo['estado'] . ", CEP " . $curriculo['cep']; ?></p>
            <p><strong>Nacionalidade:</strong> <?php echo $curriculo['nacionalidade']; ?></p>
            <p><strong>Estado Civil:</strong> <?php echo $curriculo['estado_civil']; ?></p>
            <p><strong>Gênero:</strong> <?php echo $curriculo['genero']; ?></p>

            <hr>
            <h5 class="text-secondary border-bottom pb-1 mb-3">Formação</h5>
            <p><strong>Grau:</strong> <?php echo $curriculo['grau']; ?></p>
            <p><strong>Curso:</strong> <?php echo $curriculo['curso']; ?></p>
            <p><strong>Instituição:</strong> <?php echo $curriculo['instituicao']; ?></p>
            <p><strong>Ano de Início:</strong> <?php echo $curriculo['ano_inicio']; ?></p>
            <p><strong>Ano de Conclusão:</strong> <?php echo $curriculo['ano_conclusao'] ?? 'N/A'; ?></p>
            <p><strong>Concluído:</strong> <?php echo $curriculo['concluido'] === 'sim' ? 'Sim' : 'Não'; ?></p>

            <hr>
            <h5 class="text-secondary border-bottom pb-1 mb-3">Experiência Profissional</h5>
            <p><strong>Empresa:</strong> <?php echo $curriculo['empresa']; ?></p>
            <p><strong>Cargo:</strong> <?php echo $curriculo['cargo']; ?></p>
            <p><strong>Atividades:</strong> <?php echo $curriculo['atividades']; ?></p>
            <p><strong>Data de Início:</strong> <?php echo $curriculo['data_inicio']; ?></p>
            <p><strong>Data de Término:</strong> <?php echo $curriculo['data_fim'] ?? 'Atual'; ?></p>

            <hr>
            <h5 class="text-secondary border-bottom pb-1 mb-3">Idioma</h5>
            <p><strong>Idioma:</strong> <?php echo $curriculo['idioma']; ?></p>
            <p><strong>Nível:</strong> <?php echo $curriculo['nivel']; ?></p>
        </div>
    </div>

    <div class="row text-center mt-4 g-2">
        <div class="col-md-4 d-grid">
            <a href="index.php" class="btn btn-secondary">Voltar à Página Inicial</a>
        </div>
        <div class="col-md-4 d-grid">
            <a href="formulario_curriculo_edit.php?id=<?php echo $id; ?>" class="btn btn-warning text-white">Editar Currículo</a>
        </div>
        <div class="col-md-4 d-grid">
            <a href="excluir_curriculo.php?id=<?php echo $id; ?>" class="btn btn-danger"
               onclick="return confirm('Tem certeza que deseja excluir este currículo?');">
                Excluir Currículo
            </a>
        </div>
    </div>
</div>


<!-- editar ate aqui -->
    </div></div></div>

    <?php
include("../footer.php");

?>