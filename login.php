<?php
require_once 'config.php';
session_start();

// Verificar se o usuário está logado
if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
}

//Proceso d Lognin
$mensagemErro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)){
        $mensagemErro = "Por favor, preencha todos os campos.";
    }else{
        try{
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch();

            if(!$usuario){
                // se usuario nao existir
                $mensagemErro = "Email ou senha incorretos.";
            }elseif ($usuario['ativo'] != 1){
                // se coluna do banco ativo for diferente de 1
                $mensagemErro = "Usuario inativo.";
            }elseif ($senha == $usuario['senha']) {
                //sucesso!                
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_name'] = $usuario['produto'];
                $_SESSION['usuario_email'] = $usuario['email'];
                header('location: index.php');
            }

        }catch (PDOException $e){
            $mensagemErro = "Erro ao processar login" . $e->getMessage();

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pet Shop </title>
</head>
<body>
    <h1>Sistema Pet Shop</h1>

    <?php
    if(!empty($mensagemErro)){
        echo $mensagemErro;
    }
    ?>
    
    <form action="" method="POST">
        <div>
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" required>
        </div>
        <br>
        <div>
          <label for="senha">Senha:</label>
          <input type="password" name="senha" id="senha" required>
        </div>
        <br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>    

