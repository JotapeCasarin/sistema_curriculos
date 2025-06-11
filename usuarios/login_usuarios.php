<?php
session_start();

// Redireciona se o usuário já estiver logado
if (isset($_SESSION["usuario"])) {
    header("Location: ./curriculos/index.php");
    exit();
}

$msg = ""; // Alterado de $erro para $msg para consistência
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- 1. ALTERAÇÃO: Capturar 'cpf' em vez de 'usuario' ---
    $cpf = $_POST["cpf"];
    $senha = $_POST["senha"];

    // --- 2. NOVO: Limpar formatação do CPF (pontos e traços) ---
    // Isso garante que o CPF seja comparado corretamente com o que está no banco
    $cpf_limpo = preg_replace('/[^0-9]/', '', $cpf);

    // --- 3. ALTERAÇÃO: Consultar a tabela 'usuarios_ext' pelo campo 'cpf' ---
    $stmt = $pdo->prepare("SELECT * FROM usuarios_ext WHERE cpf = :cpf");
    $stmt->execute(['cpf' => $cpf_limpo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica a senha e se o usuário foi encontrado
    if ($user && password_verify($senha, $user['senha'])) {
        // --- 4. MELHORIA: Salvar dados mais úteis na sessão ---
        $_SESSION['usuario'] = $user['nome'] . ' ' . $user['sobrenome']; // Salva o nome completo
        $_SESSION['id'] = $user['id']; // Salva o ID do usuário
        
        header("Location: ./curriculos/index.php");
        exit();
    } else {
        // --- 5. ALTERAÇÃO: Mensagem de erro atualizada ---
        $msg = "CPF ou senha inválidos.";
    }
}
$url = 'http://localhost:9000/usuarios/'; // Define a URL base para seus assets

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <title>Login | Mantis Bootstrap 5 Admin Template</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="<?php echo $url; ?>assets/images/favicon.svg" type="image/x-icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
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
              <h3 class="mb-0"><b>Login</b></h3>
              <a href="criar_usuario.php" class="link-primary">Não é cadastrado?</a>
            </div>

            <?php if (!empty($msg)): ?> <div class="alert alert-danger"><?php echo htmlspecialchars($msg); ?></div>
            <?php endif; ?>

            <form method="post" action="login_usuarios.php">

              <div class="form-group mb-3">
                <label class="form-label">CPF</label>
                <input type="text" name="cpf" id="cpf" class="form-control" placeholder="Digite seu CPF" required maxlength="14">
              </div>

              <div class="form-group mb-3">
                <label class="form-label">Senha</label> <input type="password" name="senha" class="form-control" placeholder="Senha" required>
              </div>

              <div class="d-flex mt-1 justify-content-between">
                <div class="form-check">
                  <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" checked="">
                  <label class="form-check-label text-muted" for="customCheckc1">Manter-me logado</label>
                </div>
                <h5 class="text-secondary f-w-400">Esqueci a senha</h5>
              </div>
              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="../admin/login_admin.php" class="btn btn-warning">Administração</a>
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
    function formatarCPF(cpf) {
      const cpfLimpo = cpf.replace(/\D/g, '');
      const cpfLimitado = cpfLimpo.slice(0, 11);
      let cpfFormatado = cpfLimitado.replace(/(\d{3})(\d)/, '$1.$2');
      cpfFormatado = cpfFormatado.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
      cpfFormatado = cpfFormatado.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
      return cpfFormatado;
    }

    const inputCPF = document.querySelector('#cpf');
    if (inputCPF) {
      inputCPF.addEventListener('input', (e) => {
        e.target.value = formatarCPF(e.target.value);
      });
    }
  </script>

</body>
</html>