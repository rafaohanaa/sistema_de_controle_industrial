<?php 

$host = '127.0.0.1'; 
$port = '3307';
$dbname = 'simulad_saep_02';
$usuario = 'root';
$senha = 'mysql';

try{
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4;port=$port", $usuario, $senha);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("Erro na conexão: " . $e->getMessage());
}

function verificarLogin(){
    session_start();
    if(!isset($_SESSION['usuario_id'])){
        header('Location: login.php');
        exit();
    }
}


