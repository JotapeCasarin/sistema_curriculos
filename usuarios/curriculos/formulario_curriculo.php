<?php

include("../config.php");
require_once("../db.php");
include("../head.php");
include("../header.php");


session_start();
if (!isset($_SESSION["usuario"])) {
  header("Location: ../../login.php");
  exit();
}
?>


<?php
include("../../main_format.php");
?>

<div class="container mt-5 mb-5">
  <div class="card shadow border-0">
    <div class="card-header bg-success text-white">
      <h1 class="mb-0">Cadastro de curriculo</h1>
    </div>

      <form action="salvar_curriculo.php" method="POST" class="card p-4 shadow-sm">

        <!-- Dados Pessoais -->
        <h4 class="mb-3">Dados Pessoais</h4>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">CPF</label>
            <input type="text" name="cpf" id="cpf" class="form-control" maxlength="14" required>

            <script>
              const cpfInput = document.getElementById('cpf');

              // Aplica máscara dinâmica ao digitar
              cpfInput.addEventListener('input', () => {
                let value = cpfInput.value.replace(/\D/g, ''); // remove tudo que não é número
                if (value.length > 11) value = value.slice(0, 11); // limita a 11 dígitos
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                cpfInput.value = value;
              });

              // Antes de enviar, limpa a máscara (envia só os números)
              document.querySelector('form').addEventListener('submit', function () {
                cpfInput.value = cpfInput.value.replace(/\D/g, '');
              });
            </script>


          </div>
          <div class="col-md-3">
            <label class="form-label">RG</label>
            <input type="text" name="rg" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Data de Nascimento</label>
            <input type="date" name="data_nascimento" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Telefone</label>
            <input type="text" name="telefone" class="form-control" required>
          </div>
          <div class="col-md-12">
            <label class="form-label">Endereço</label>
            <input type="text" name="endereco" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Cidade</label>
            <input type="text" name="cidade" class="form-control" required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control" maxlength="2" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">CEP</label>
            <input type="text" name="cep" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Nacionalidade</label>
            <input type="text" name="nacionalidade" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Estado Civil</label>
            <input type="text" name="estado_civil" class="form-control" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Gênero</label>
            <input type="text" name="genero" class="form-control" required>
          </div>
        </div>

        <!-- Formação Acadêmica -->
        <hr class="my-4">
        <h4 class="mb-3">Formação Acadêmica</h4>
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">Grau</label>
            <input type="text" name="grau" class="form-control">
          </div>
          <div class="col-md-5">
            <label class="form-label">Curso</label>
            <input type="text" name="curso" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">Instituição</label>
            <input type="text" name="instituicao" class="form-control">
          </div>
          <div class="col-md-2">
            <label class="form-label">Ano Início</label>
            <input type="number" name="ano_inicio" class="form-control">
          </div>
          <div class="col-md-2">
            <label class="form-label">Ano Conclusão</label>
            <input type="number" name="ano_conclusao" class="form-control">
          </div>
          <div class="col-md-2">
            <label class="form-label">Concluído</label>
            <select name="concluido" class="form-select">
              <option value="1">Sim</option>
              <option value="0">Não</option>
            </select>
          </div>
        </div>

        <!-- Experiência Profissional -->
        <hr class="my-4">
        <h4 class="mb-3">Experiência Profissional</h4>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Empresa</label>
            <input type="text" name="empresa" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">Cargo</label>
            <input type="text" name="cargo" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="form-label">Atividades</label>
            <input type="text" name="atividades" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Data Início</label>
            <input type="date" name="data_inicio" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Data Fim</label>
            <input type="date" name="data_fim" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Emprego Atual</label>
            <select name="atual" class="form-select">
              <option value="0">Não</option>
              <option value="1">Sim</option>
            </select>
          </div>
        </div>

        <!-- Idiomas -->
        <hr class="my-4">
        <h4 class="mb-3">Idiomas</h4>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Idioma</label>
            <input type="text" name="idioma" class="form-control">
          </div>
          <div class="col-md-3">
            <label class="form-label">Nível</label>
            <select name="nivel" class="form-select">
              <option value="Básico">Básico</option>
              <option value="Intermediário">Intermediário</option>
              <option value="Avançado">Avançado</option>
              <option value="Fluente">Fluente</option>
            </select>
          </div>
        </div>

        <!-- Botão -->
        <div class="mt-4">
          <button type="submit" class="btn btn-primary">Salvar Currículo</button>
        </div>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <?php
    include("../footer.php");

    ?>