
<?php
try {
    $pdo = new PDO("pgsql:host=45.232.39.215;port=5432;dbname=estagio_testes;user=joao_casarin;password=12345678");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit;
}
?>
