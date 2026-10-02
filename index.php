<?php
require_once 'config.php';
verificarLogin();

if(isset($_GET['logout'])){
   session_destroy();
    header('Location: login.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle Industrial</title>
</head>
<body>
    <h1>Sistema de Controle Industral</h1>
    <div>
        <p>
            Usuário logado: <?php echo $_SESSION['usuario_name']; ?>
        </p>
    </div>
    <hr>
   <h2>Menu</h2>
   <ul>
       <li><a href="produtos.php">Cadastro de Produtos</a></li>
       <li><a href="estoque.php">Estoque</a></li>
       <li><a href="?logout=1">Sair</a></li>
   </ul>

</body>
</html>