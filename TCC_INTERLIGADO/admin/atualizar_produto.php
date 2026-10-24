<?php
mysqli_report(MYSQLI_REPORT_OFF);
include "../geral/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: editar_produto.php?erro=1');
    exit;
}

$idProduto = (int) ($_POST['id_produto'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco = (float) str_replace(',', '.', ($_POST['preco'] ?? '0'));
$categoria = (int) ($_POST['categoria'] ?? 1);
$genero = (int) ($_POST['genero'] ?? 4);
$ativo = isset($_POST['ativo']) ? 1 : 0;
$estoque = (int) ($_POST['estoque'] ?? 0);

if (!$con || $idProduto <= 0 || $nome === '' || $preco < 0 || $estoque < 0) {
    header('Location: editar_produto.php?erro=1');
    exit;
}

$sql = "UPDATE tb_produto SET nome_produto = ?, descricao_produto = ?, preco = ?, id_categoria = ?, id_genero = ?, ativo = ? WHERE id_produto = ?";
$stmt = mysqli_prepare($con, $sql);
if (!$stmt) {
    header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
    exit;
}
mysqli_stmt_bind_param($stmt, 'ssdiiii', $nome, $descricao, $preco, $categoria, $genero, $ativo, $idProduto);

$con->begin_transaction();
if (mysqli_stmt_execute($stmt)) {
    $stmtVar = mysqli_prepare($con, 'SELECT id_variacao FROM tb_variacao_produto WHERE id_produto = ? ORDER BY id_variacao');
    if ($stmtVar) {
        mysqli_stmt_bind_param($stmtVar, 'i', $idProduto);
        if (mysqli_stmt_execute($stmtVar)) {
            $variacoes = mysqli_stmt_get_result($stmtVar);
            $idsVariacoes = [];
            while ($variacao = mysqli_fetch_assoc($variacoes)) {
                $idsVariacoes[] = (int) $variacao['id_variacao'];
            }

            if (!$idsVariacoes) {
                $stmtInsertVar = mysqli_prepare($con, "INSERT INTO tb_variacao_produto (id_produto, tamanho, cor, estoque) VALUES (?, 'Único', '#000000', ?)");
                $estoqueSalvo = $stmtInsertVar !== false;
                if ($estoqueSalvo) {
                    mysqli_stmt_bind_param($stmtInsertVar, 'ii', $idProduto, $estoque);
                    $estoqueSalvo = mysqli_stmt_execute($stmtInsertVar);
                }
            } else {
                $stmtUpdateVar = mysqli_prepare($con, 'UPDATE tb_variacao_produto SET estoque = ? WHERE id_variacao = ?');
                $estoqueSalvo = $stmtUpdateVar !== false;
                foreach ($idsVariacoes as $indice => $idVariacao) {
                    if (!$estoqueSalvo) {
                        break;
                    }
                    $estoqueVariacao = intdiv($estoque, count($idsVariacoes))
                        + ($indice < ($estoque % count($idsVariacoes)) ? 1 : 0);
                    mysqli_stmt_bind_param($stmtUpdateVar, 'ii', $estoqueVariacao, $idVariacao);
                    $estoqueSalvo = mysqli_stmt_execute($stmtUpdateVar);
                }
            }

            if ($estoqueSalvo && $con->commit()) {
                header('Location: editar_produto.php?id=' . $idProduto . '&sucesso=1');
                exit;
            }
        }
    }
}

$con->rollback();
header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
exit;
