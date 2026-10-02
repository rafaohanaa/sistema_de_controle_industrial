<?php
require_once 'config.php';
verificarLogin();

$mensagem = $tipoMensagem = '';

if (isset($_GET['excluir'])) {
    try {
        $stmt = $conn->prepare("UPDATE produtos SET ativo = 0 WHERE id = ?");
        $stmt->execute([$_GET['excluir']]);
        $mensagem = "Produto desativado/excluído com sucesso!";
        $tipoMensagem = "sucesso";
    } catch (PDOException $e) {
        $mensagem = "Erro ao excluir produto: " . $e->getMessage();
        $tipoMensagem = "erro";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 0;

    $data = [
        'codigo'=> ($_POST['codigo'] ?? ''),
        'produto'=> ($_POST['produto'] ?? ''),
        'fabricante'=> ($_POST['fabricante'] ?? ''),
        'preco'=> ($_POST['preco'] ?? 0),
        'estoque_atual'=> ($_POST['estoque_atual'] ?? 0),
        'estoque_minimo'=> ($_POST['estoque_minimo'] ?? 0),
        'ativo'=> ($_POST['ativo'] ?? 1),
        'categoria'=> ($_POST['categoria'] ?? null),
    ];
    
    if ($id > 0) {
        // Removida a vírgula antes do WHERE
        $sql = "UPDATE produtos SET 
            codigo = :codigo,
            produto = :produto,
            fabricante = :fabricante,
            preco = :preco,
            estoque_atual = :estoque_atual,
            estoque_minimo = :estoque_minimo,
            ativo = :ativo,
            categoria = :categoria
        WHERE id = :id";
        $data['id'] = $id;  
    } else {
        $sql = "INSERT INTO produtos (codigo, produto, fabricante, preco, estoque_atual, estoque_minimo, ativo, categoria) 
                VALUES (:codigo, :produto, :fabricante, :preco, :estoque_atual, :estoque_minimo, :ativo, :categoria)";
    }

    try {
        $conn->prepare($sql)->execute($data);
        $mensagem = $id > 0 ? "Produto atualizado com sucesso!" : "Produto salvo com sucesso!";
        $tipoMensagem = "sucesso";
    } catch (PDOException $e) {
        $mensagem = "Erro: " . $e->getMessage();
        $tipoMensagem = "erro";
    }
}

$produtoEditar = null;
if (isset($_GET['editar'])) {
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$_GET['editar']]);
    $produtoEditar = $stmt->fetch();
}

$busca = trim($_GET['busca'] ?? '');
$sql = "SELECT * FROM produtos WHERE ativo = 1" . ($busca ? " AND (produto LIKE :b OR codigo LIKE :b OR categoria LIKE :b)" : "") . " ORDER BY produto";
$stmt = $conn->prepare($sql);
$stmt->execute($busca ? ['b' => "%$busca%"] : []);
$produtos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos - Industrial</title>
</head>
<body>
  <h1>Cadastro de Produtos</h1>
  <p><a href="index.php">⬅️ Voltar</a></p>
  <hr>

  <?php if (!empty($mensagem)): ?>
    <p style="color: <?= $tipoMensagem === 'sucesso' ? 'green' : 'red' ?>;">
        <?= htmlspecialchars($mensagem) ?>
    </p>
  <?php endif; ?>

  <h2><?= $produtoEditar ? 'Editar' : 'Novo' ?> Produto</h2>
  <form method="POST">
      <input type="hidden" name="id" value="<?= $produtoEditar['id'] ?? 0 ?>">
      
      <label> Código: 
          <input type="text" name="codigo" required value="<?= htmlspecialchars($produtoEditar['codigo'] ?? '') ?>">
      </label><br><br>

      <label> Produto: 
          <input type="text" name="produto" size="40" required value="<?= htmlspecialchars($produtoEditar['produto'] ?? '') ?>"> 
      </label><br><br>

      <label> Fabricante: 
          <input type="text" name="fabricante" value="<?= htmlspecialchars($produtoEditar['fabricante'] ?? '') ?>"> 
      </label><br><br>

      <label> Preço: 
          <input type="number" step="0.01" name="preco" required value="<?= htmlspecialchars($produtoEditar['preco'] ?? '0.00') ?>"> 
      </label><br><br>

      <label> Estoque Atual: 
          <input type="number" name="estoque_atual" required value="<?= htmlspecialchars($produtoEditar['estoque_atual'] ?? 0) ?>"> 
      </label><br><br>   

      <label> Estoque Mínimo: 
          <input type="number" name="estoque_minimo" required value="<?= htmlspecialchars($produtoEditar['estoque_minimo'] ?? 0) ?>"> 
      </label><br><br>

      <label>Status:
         <select name="ativo">
            <option value="1" <?= ($produtoEditar['ativo'] ?? 1) == 1 ? 'selected' : '' ?>>Ativo</option>
            <option value="0" <?= ($produtoEditar['ativo'] ?? 1) == 0 ? 'selected' : '' ?>>Inativo</option>
         </select>
      </label><br><br>

      <label> Categoria: 
          <input type="text" name="categoria" size="40" value="<?= htmlspecialchars($produtoEditar['categoria'] ?? '') ?>"> 
      </label><br><br>

      <button type="submit">Salvar Produto</button>
  </form>

  <hr>

  <h2>Lista de Produtos</h2>
  <form method="GET">
      <label>Buscar:
          <input type="text" name="busca" value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>" placeholder="Produto, Código ou Categoria...">
      </label>
      <button type="submit">Buscar</button>
      <?php if ($busca): ?>
          <a href="produtos.php">Limpar</a>
      <?php endif; ?>
  </form>
  <br>

  <?php if ($produtos): ?>
      <table border="1" cellpadding="5" cellspacing="0">
          <thead>
              <tr>
                  <th>Código</th>
                  <th>Produto</th>
                  <th>Fabricante</th>
                  <th>Categoria</th>
                  <th>Preço</th>
                  <th>Estoque Atual</th>
                  <th>Estoque Mínimo</th>
                  <th>Status</th>
                  <th>Ações</th>
              </tr>
          </thead>
          <tbody>
              <?php foreach ($produtos as $p): ?>
                  <tr>
                      <td><?= htmlspecialchars($p['codigo']) ?></td>
                      <td><?= htmlspecialchars($p['produto']) ?></td>
                      <td><?= htmlspecialchars($p['fabricante']) ?></td>
                      <td><?= htmlspecialchars($p['categoria']) ?></td>
                      <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                      <td><?= htmlspecialchars($p['estoque_atual']) ?></td>
                      <td><?= htmlspecialchars($p['estoque_minimo']) ?></td>
                      <td><?= $p['ativo'] ? 'Ativo' : 'Inativo' ?></td>
                      <td>
                          <a href="produtos.php?editar=<?= $p['id'] ?>">Editar</a> |
                          <a href="produtos.php?excluir=<?= $p['id'] ?>" onclick="return confirm('Deseja realmente desativar/excluir este produto?')">Excluir</a>
                      </td> 
                  </tr>
              <?php endforeach; ?>
          </tbody>
      </table>    
  <?php else: ?>
      <p>Nenhum produto encontrado.</p>
  <?php endif; ?>

</body>
</html>