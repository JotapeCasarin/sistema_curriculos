<?php
session_start();
require_once 'db.php'; // Inclui a conexão com o banco

$msg = ""; // Variável para mensagens de sucesso ou erro

// Função para inserir usuário no banco de dados
function inserirUsuario($pdo, $dados)
{
  // A senha já deve vir com hash e o cpf já deve vir limpo
  $sql = "INSERT INTO usuarios_ext (nome, sobrenome, cpf, email, senha) VALUES (:nome, :sobrenome, :cpf, :email, :senha)";

  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':nome' => $dados['nome'],
      ':sobrenome' => $dados['sobrenome'],
      ':cpf' => $dados['cpf'],
      ':email' => $dados['email'],
      ':senha' => $dados['senha']
    ]);
    return true;
  } catch (PDOException $e) {
    // Se houver um erro (ex: cpf/email duplicado), retorna falso
    // error_log($e->getMessage()); // É uma boa prática logar o erro real
    return false;
  }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nome = $_POST["nome"];
  $sobrenome = $_POST["sobrenome"];
  $cpf = $_POST["cpf"];
  $email = $_POST["email"];
  $senha = $_POST["senha"];
  $confirmar_senha = $_POST["confirmar_senha"];

  // 1. Limpa o CPF de caracteres não numéricos (pontos e traços)
  $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);

  // --- Validações ---
  if (empty($nome) || empty($sobrenome) || empty($cpf) || empty($email) || empty($senha)) {
    $msg = "Por favor, preencha todos os campos.";
  } elseif (strlen($cpf_limpo) != 11) {
    $msg = "CPF inválido. Por favor, verifique o número digitado.";
  } elseif ($senha !== $confirmar_senha) {
    $msg = "As senhas não coincidem.";
  } else {
    // 2. Verificar se o CPF ou e-mail já existem
    $stmt = $pdo->prepare("SELECT id FROM usuarios_ext WHERE cpf = :cpf OR email = :email");
    $stmt->execute(['cpf' => $cpf_limpo, 'email' => $email]);
    if ($stmt->fetch()) {
      $msg = "CPF ou e-mail já cadastrado.";
    } else {
      // 3. Criptografar a senha antes de salvar
      $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

      $dados_usuario = [
        'nome' => $nome,
        'sobrenome' => $sobrenome,
        'cpf' => $cpf_limpo, // Salva o CPF limpo
        'email' => $email,
        'senha' => $senha_hash
      ];

      // 4. Chamar a função para inserir no banco
      if (inserirUsuario($pdo, $dados_usuario)) {
        // Redireciona para a página de login com uma mensagem de sucesso
        header("Location: login_usuarios.php?status=success_user_creation");
        exit();
      } else {
        $msg = "Ocorreu um erro ao criar o usuário. Tente novamente.";
      }
    }
  }
}

$url = 'http://localhost:9000/admin/'; // Define a URL base (igual à da sua página de login)
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <title>Criação de Usuário | Mantis Bootstrap 5 Admin Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="<?php echo $url; ?>assets/images/favicon.svg" type="image/x-icon">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    id="main-font-link">
  <link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/tabler-icons.min.css">
  <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css" id="main-style-link">
  <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style-preset.css">
</head>

<body>
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="auth-header">
          <a href="#"><img src="<?php echo $url; ?>assets/images/logo-dark.svg" alt="Mantis admin template logo"></a>
        </div>
        <div class="card my-5">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-end mb-4">
              <h3 class="mb-0"><b>Criar Conta</b></h3>
              <a href="login_usuarios.php" class="link-primary">Já tem uma conta?</a>
            </div>

            <?php if (!empty($msg)): ?>
              <div class="alert alert-danger"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <form method="post" action="criar_usuario.php">

              <div class="form-group mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" placeholder="Seu nome" required>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Sobrenome</label>
                <input type="text" name="sobrenome" class="form-control" placeholder="Seu sobrenome" required>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control" placeholder="000.000.000-00" required
                  maxlength="14">
              </div>
              <div class="form-group mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="senha" class="form-control" placeholder="Crie uma senha forte" required>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Confirmar Senha</label>
                <input type="password" name="confirmar_senha" class="form-control" placeholder="Confirme sua senha"
                  required>
              </div>

              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Criar Conta</button>
              </div>

            </form>
          </div>
        </div>
        <div class="auth-footer row"> ... </div>
      </div>
    </div>
  </div>

  <script src="<?php echo $url; ?>assets/js/plugins/popper.min.js"></script>
  <script src="<?php echo $url; ?>assets/js/plugins/bootstrap.min.js"></script>
  <script>
    // Função que aplica a máscara de CPF
    function formatarCPF(cpf) {
      // 1. Remove tudo que não for dígito
      const cpfLimpo = cpf.replace(/\D/g, '');

      // 2. Limita o tamanho para 11 dígitos
      const cpfLimitado = cpfLimpo.slice(0, 11);

      // 3. Aplica a máscara
      let cpfFormatado = cpfLimitado.replace(/(\d{3})(\d)/, '$1.$2');
      cpfFormatado = cpfFormatado.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
      cpfFormatado = cpfFormatado.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})/, '$1.$2.$3-$4');

      return cpfFormatado;
    }

    // Pega o elemento do campo CPF do seu formulário
    // Certifique-se que o id="cpf" está no seu input
    const inputCPF = document.querySelector('#cpf');

    // Adiciona um "escutador" de eventos que dispara a formatação
    // a cada vez que o usuário digita algo
    if (inputCPF) {
      inputCPF.addEventListener('input', (e) => {
        e.target.value = formatarCPF(e.target.value);
      });
    }
  </script>
</body>

</html>