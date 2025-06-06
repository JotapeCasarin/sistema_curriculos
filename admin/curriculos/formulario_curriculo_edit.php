<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Cadastro de Currículo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<?php
session_start();
if (!isset($_SESSION["usuario"])) {
  header("Location: ../../login.php");
  exit();
}
?>
<?php
include 'db.php';
$id = $_GET['id'] ?? 0;

// Dados pessoais
$stmt = $pdo->prepare("SELECT * FROM dados_pessoais WHERE id = ?");
$stmt->execute([$id]);
$pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

// Formação
$stmt2 = $pdo->prepare("SELECT * FROM formacoes WHERE dados_pessoais_id = ?");
$stmt2->execute([$id]);
$formacoes = $stmt2->fetch(PDO::FETCH_ASSOC);

// Experiência Profissional
$stmt2 = $pdo->prepare("SELECT * FROM experiencias_profissionais WHERE dados_pessoais_id = ?");
$stmt2->execute([$id]);
$experiencias = $stmt2->fetch(PDO::FETCH_ASSOC);

// Idiomas
$stmt2 = $pdo->prepare("SELECT * FROM idiomas WHERE dados_pessoais_id = ?");
$stmt2->execute([$id]);
$idiomas = $stmt2->fetch(PDO::FETCH_ASSOC);
?>


<body class="bg-light">

  <div class="container my-5">
    <h1 class="mb-4">Cadastro de Currículo</h1>

    <form action="salvar_curriculo.php" method="POST" class="card p-4 shadow-sm">

      <!-- Dados Pessoais -->
      <h4 class="mb-3">Dados Pessoais</h4>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nome</label>
          <input type="text" name="nome" value="<?php echo $pessoa['nome']; ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">CPF</label>
          <input type="text" name="cpf" value="<?php echo $pessoa['cpf']; ?>" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">RG</label>
          <input type="text" name="rg" value="<?php echo $pessoa['rg']; ?>" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Data de Nascimento</label>
          <input type="date" name="data_nascimento" value="<?php echo $pessoa['data_nascimento']; ?>"
            class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="<?php echo $pessoa['email']; ?>" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Telefone</label>
          <input type="text" name="telefone" value="<?php echo $pessoa['telefone']; ?>" class="form-control" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Endereço</label>
          <input type="text" name="endereco" value="<?php echo $pessoa['endereco']; ?>" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Cidade</label>
          <input type="text" name="cidade" value="<?php echo $pessoa['cidade']; ?>" class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Estado</label>
          <input type="text" name="estado" value="<?php echo $pessoa['estado']; ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">CEP</label>
          <input type="text" name="cep" value="<?php echo $pessoa['cep']; ?>" class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Nacionalidade</label>
          <input type="text" name="nacionalidade" value="<?php echo $pessoa['nacionalidade']; ?>" class="form-control"
            required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Estado Civil</label>
          <input type="text" name="estado_civil" value="<?php echo $pessoa['estado_civil']; ?>" class="form-control"
            required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Gênero</label>
          <input type="text" name="genero" value="<?php echo $pessoa['genero']; ?>" class="form-control" required>
        </div>
      </div>

      <!-- Formação Acadêmica -->
      <hr class="my-4">
      <h4 class="mb-3">Formação Acadêmica</h4>
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Grau</label>
          <input type="text" name="grau" value="<?php echo $formacoes['grau'] ?? ''; ?>" class="form-control" required>
        </div>
        <div class="col-md-5">
          <label class="form-label">Curso</label>
          <input type="text" name="curso" value="<?php echo $formacoes['curso'] ?? ''; ?>" class="form-control"
            required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Instituição</label>
          <input type="text" name="instituicao" value="<?php echo $formacoes['instituicao'] ?? ''; ?>"
            class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Ano Início</label>
          <input type="number" name="ano_inicio" value="<?php echo $formacoes['ano_inicio'] ?? ''; ?>"
            class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Ano Conclusão</label>
          <input type="number" name="ano_conclusao" value="<?php echo $formacoes['ano_conclusao'] ?? ''; ?>"
            class="form-control" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Concluído</label>
          <select name="concluido" class="form-select">
            <option value="1" <?php echo (isset($formacoes['concluido']) && $formacoes['concluido'] == 1) ? 'selected' : ''; ?>>Sim</option>
            <option value="0" <?php echo (isset($formacoes['concluido']) && $formacoes['concluido'] == 0) ? 'selected' : ''; ?>>Não</option>
          </select>
        </div>
      </div>

      <!-- Experiência Profissional -->
      <hr class="my-4">
      <h4 class="mb-3">Experiência Profissional</h4>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Empresa</label>
          <input type="text" name="empresa" value="<?php echo $experiencias['empresa'] ?? ''; ?>" class="form-control"
            required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Cargo</label>
          <input type="text" name="cargo" value="<?php echo $experiencias['cargo'] ?? ''; ?>" class="form-control"
            required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Atividades</label>
          <input type="text" name="atividades" value="<?php echo $experiencias['atividades'] ?? ''; ?>"
            class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Data Início</label>
          <input type="date" name="data_inicio" value="<?php echo $experiencias['data_inicio'] ?? ''; ?>"
            class="form-control" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Data Fim</label>
          <input type="date" name="data_fim" value="<?php echo $experiencias['data_fim'] ?? ''; ?>" class="form-control"
            required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Emprego Atual</label>
          <select name="atual" class="form-select">
            <option value="0" <?php echo (isset($experiencias['atual']) && $experiencias['atual'] == 0) ? 'selected' : ''; ?>>Não</option>
            <option value="1" <?php echo (isset($experiencias['atual']) && $experiencias['atual'] == 1) ? 'selected' : ''; ?>>Sim</option>
          </select>
        </div>
      </div>

      <!-- Idiomas -->
      <hr class="my-4">
      <h4 class="mb-3">Idiomas</h4>
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Idioma</label>
          <select name="idioma" class="form-select" required>
            <?php
            $idiomas_populares = [
              'Inglês',
              'Mandarim',
              'Hindi',
              'Espanhol',
              'Francês',
              'Árabe',
              'Bengali',
              'Russo',
              'Português',
              'Urdu',
              'Indonésio',
              'Alemão',
              'Japonês',
              'Suaíli',
              'Marata',
              'Telugu',
              'Turco',
              'Tâmil',
              'Vietnamita',
              'Coreano'
            ];
            foreach ($idiomas_populares as $idioma) {
              $selected = (isset($idiomas['idioma']) && $idiomas['idioma'] == $idioma) ? 'selected' : '';
              echo "<option value=\"$idioma\" $selected>$idioma</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-3">
          <label class="form-label">Nível</label>
          <select name="nivel" class="form-select">
            <option value="Básico" <?php echo (isset($idiomas['nivel']) && $idiomas['nivel'] == 'Básico') ? 'selected' : ''; ?>>Básico</option>
            <option value="Intermediário" <?php echo (isset($idiomas['nivel']) && $idiomas['nivel'] == 'Intermediário') ? 'selected' : ''; ?>>Intermediário</option>
            <option value="Avançado" <?php echo (isset($idiomas['nivel']) && $idiomas['nivel'] == 'Avançado') ? 'selected' : ''; ?>>Avançado</option>
            <option value="Fluente" <?php echo (isset($idiomas['nivel']) && $idiomas['nivel'] == 'Fluente') ? 'selected' : ''; ?>>Fluente</option>
          </select>
        </div>
      </div>

      <input type="hidden" name="id" value="<?php echo $pessoa['id']; ?>" class="form-control">

      <div class="row mt-4">
        <!-- Botão confirmar -->
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Salvar Currículo</button>
        </div>
        <!-- Botão de Cancelar -->
        <div class="col-auto">
          <a href="index.php" class="btn btn-danger">Cancelar edição</a>
        </div>
      </div>


      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>