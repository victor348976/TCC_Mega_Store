<?php
mysqli_report(MYSQLI_REPORT_OFF);
include "../geral/conexao.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$preco = (float) str_replace(',', '.', ($_POST['preco'] ?? '0'));
$categoria = (int) ($_POST['categoria'] ?? 1);
$genero = (int) ($_POST['genero'] ?? 4);
$ativo = isset($_POST['ativo']) ? 1 : 0;
$estoque = (int) ($_POST['estoque'] ?? 0);
$dataHoje = date('Y-m-d');

if (!$con || $nome === '' || $preco < 0 || $estoque < 0) {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

$categoriaExiste = mysqli_prepare($con, 'SELECT id_categoria FROM tb_categoria WHERE id_categoria = ?');
if (!$categoriaExiste) {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}
mysqli_stmt_bind_param($categoriaExiste, 'i', $categoria);
mysqli_stmt_execute($categoriaExiste);
$categoriaValida = mysqli_stmt_get_result($categoriaExiste);
if (!$categoriaValida || mysqli_num_rows($categoriaValida) === 0) {
    header('Location: cadastro_produto.php?erro=1');
    exit;
}

$caminhoImagem = null;
$arquivoImagem = $_FILES['imagem'] ?? null;
if ($arquivoImagem && $arquivoImagem['error'] !== UPLOAD_ERR_NO_FILE) {
    $tiposPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    $tipoImagem = $arquivoImagem['tmp_name'] !== '' ? mime_content_type($arquivoImagem['tmp_name']) : '';
    if ($arquivoImagem['error'] !== UPLOAD_ERR_OK || $arquivoImagem['size'] > 5 * 1024 * 1024 || !isset($tiposPermitidos[$tipoImagem])) {
        header('Location: cadastro_produto.php?erro=1');
        exit;
    }

    $diretorioUploads = __DIR__ . '/../produtos/uploads';
    if (!is_dir($diretorioUploads) && !mkdir($diretorioUploads, 0755, true)) {
        header('Location: cadastro_produto.php?erro=1');
        exit;
    }
    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $tiposPermitidos[$tipoImagem];
    if (!move_uploaded_file($arquivoImagem['tmp_name'], $diretorioUploads . '/' . $nomeArquivo)) {
        header('Location: cadastro_produto.php?erro=1');
        exit;
    }
    $caminhoImagem = 'uploads/' . $nomeArquivo;
}

$sql = "INSERT INTO tb_produto (nome_produto, descricao_produto, preco, id_categoria, id_genero, ativo, data_cadastro)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$con->begin_transaction();
$stmt = mysqli_prepare($con, $sql);
if (!$stmt) {
    $con->rollback();
    if ($caminhoImagem !== null) {
        @unlink($diretorioUploads . '/' . $nomeArquivo);
    }
    header('Location: cadastro_produto.php?erro=1');
    exit;
}
mysqli_stmt_bind_param($stmt, 'ssdiiis', $nome, $descricao, $preco, $categoria, $genero, $ativo, $dataHoje);

if (mysqli_stmt_execute($stmt)) {
    $idProduto = mysqli_insert_id($con);
    $tamanhos = array_values(array_unique(array_filter(array_map('trim', $_POST['tam'] ?? []), static function ($tamanho) {
        return $tamanho !== '' && mb_strlen($tamanho) <= 5;
    })));
    if (!$tamanhos) {
        $tamanhos = ['Único'];
    }

    $salvouVariacoes = true;
    foreach ($tamanhos as $tamanho) {
        $cor = '#000000';
        $stmtVar = mysqli_prepare($con, 'INSERT INTO tb_variacao_produto (id_produto, tamanho, cor, estoque) VALUES (?, ?, ?, ?)');
        if (!$stmtVar) {
            $salvouVariacoes = false;
            break;
        }
        mysqli_stmt_bind_param($stmtVar, 'issi', $idProduto, $tamanho, $cor, $estoque);
        if (!mysqli_stmt_execute($stmtVar)) {
            $salvouVariacoes = false;
            break;
        }

        if ($caminhoImagem !== null) {
            $idVariacao = mysqli_insert_id($con);
            $stmtImagem = mysqli_prepare($con, 'INSERT INTO tb_imagem_produto (id_variacao, caminho_imagem) VALUES (?, ?)');
            if (!$stmtImagem) {
                $salvouVariacoes = false;
                break;
            }
            mysqli_stmt_bind_param($stmtImagem, 'is', $idVariacao, $caminhoImagem);
            if (!mysqli_stmt_execute($stmtImagem)) {
                $salvouVariacoes = false;
                break;
            }
        }
    }

    if ($salvouVariacoes && $con->commit()) {
        header('Location: cadastro_produto.php?sucesso=1');
        exit;
    }
}

$con->rollback();
if ($caminhoImagem !== null) {
    @unlink($diretorioUploads . '/' . $nomeArquivo);
}
header('Location: cadastro_produto.php?erro=1');
exit;
