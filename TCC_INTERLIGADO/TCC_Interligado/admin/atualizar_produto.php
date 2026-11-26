<?php
mysqli_report(MYSQLI_REPORT_OFF);
require_once __DIR__ . '/../geral/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: editar_produto.php?erro=1');
    exit;
}

if (isset($_POST['excluir_variacao']) || isset($_POST['limpar_variacoes_sem_estoque'])) {
    $idProdutoAcao = filter_var($_POST['id_produto'] ?? null, FILTER_VALIDATE_INT);
    $limparSemEstoque = isset($_POST['limpar_variacoes_sem_estoque']);
    $idVariacaoAcao = $limparSemEstoque
        ? null
        : filter_var($_POST['excluir_variacao'], FILTER_VALIDATE_INT);

    if (!$con || $idProdutoAcao === false || $idProdutoAcao < 1 || (!$limparSemEstoque && ($idVariacaoAcao === false || $idVariacaoAcao < 1))) {
        header('Location: editar_produto.php?erro=1');
        exit;
    }

    if (!$con->begin_transaction()) {
        header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
        exit;
    }

    if ($limparSemEstoque) {
        $stmtAlvos = mysqli_prepare($con, 'SELECT id_variacao FROM tb_variacao_produto WHERE id_produto = ? AND estoque <= 0 FOR UPDATE');
        if ($stmtAlvos) {
            mysqli_stmt_bind_param($stmtAlvos, 'i', $idProdutoAcao);
            $alvosCarregados = mysqli_stmt_execute($stmtAlvos);
        } else {
            $alvosCarregados = false;
        }
    } else {
        $stmtAlvos = mysqli_prepare($con, 'SELECT id_variacao FROM tb_variacao_produto WHERE id_produto = ? AND id_variacao = ? FOR UPDATE');
        if ($stmtAlvos) {
            mysqli_stmt_bind_param($stmtAlvos, 'ii', $idProdutoAcao, $idVariacaoAcao);
            $alvosCarregados = mysqli_stmt_execute($stmtAlvos);
        } else {
            $alvosCarregados = false;
        }
    }

    $alvos = [];
    if ($alvosCarregados) {
        $resultadoAlvos = mysqli_stmt_get_result($stmtAlvos);
        if ($resultadoAlvos) {
            while ($alvo = mysqli_fetch_assoc($resultadoAlvos)) {
                $alvos[] = (int) $alvo['id_variacao'];
            }
        } else {
            $alvosCarregados = false;
        }
    }
    if (!$alvosCarregados || (!$limparSemEstoque && !$alvos)) {
        $con->rollback();
        header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
        exit;
    }

    $tabelaCarrinho = null;
    $stmtTabela = mysqli_prepare(
        $con,
        'SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?'
    );
    if (!$stmtTabela) {
        $con->rollback();
        header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
        exit;
    }
    foreach (['tb_carinho', 'tb_carrinho'] as $tabelaPossivel) {
        mysqli_stmt_bind_param($stmtTabela, 's', $tabelaPossivel);
        if (!mysqli_stmt_execute($stmtTabela)) {
            $con->rollback();
            header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
            exit;
        }
        $resultadoTabela = mysqli_stmt_get_result($stmtTabela);
        if (!$resultadoTabela) {
            $con->rollback();
            header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
            exit;
        }
        if (mysqli_num_rows($resultadoTabela) > 0) {
            $tabelaCarrinho = $tabelaPossivel;
            break;
        }
    }

    $stmtVenda = mysqli_prepare($con, 'SELECT COUNT(*) AS total FROM tb_produto_venda WHERE id_variacao = ?');
    $stmtCarrinho = $tabelaCarrinho
        ? mysqli_prepare($con, 'SELECT COUNT(*) AS total FROM `' . $tabelaCarrinho . '` WHERE id_variacao = ?')
        : null;
    $stmtExcluirImagens = mysqli_prepare($con, 'DELETE FROM tb_imagem_produto WHERE id_variacao = ?');
    $stmtExcluirVariacao = mysqli_prepare($con, 'DELETE FROM tb_variacao_produto WHERE id_variacao = ? AND id_produto = ?');
    if (!$stmtVenda || ($tabelaCarrinho && !$stmtCarrinho) || !$stmtExcluirImagens || !$stmtExcluirVariacao) {
        $con->rollback();
        header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
        exit;
    }

    $excluidas = 0;
    $preservadas = 0;
    foreach ($alvos as $idVariacaoAlvo) {
        mysqli_stmt_bind_param($stmtVenda, 'i', $idVariacaoAlvo);
        $referenciasVerificadas = mysqli_stmt_execute($stmtVenda);
        $resultadoVendas = $referenciasVerificadas ? mysqli_stmt_get_result($stmtVenda) : false;
        if (!$resultadoVendas) {
            $referenciasVerificadas = false;
            $emVenda = false;
        } else {
            $emVenda = (int) mysqli_fetch_assoc($resultadoVendas)['total'] > 0;
        }

        $emCarrinho = false;
        if ($referenciasVerificadas && $stmtCarrinho) {
            mysqli_stmt_bind_param($stmtCarrinho, 'i', $idVariacaoAlvo);
            $referenciasVerificadas = mysqli_stmt_execute($stmtCarrinho);
            $resultadoCarrinho = $referenciasVerificadas ? mysqli_stmt_get_result($stmtCarrinho) : false;
            if (!$resultadoCarrinho) {
                $referenciasVerificadas = false;
            } else {
                $emCarrinho = (int) mysqli_fetch_assoc($resultadoCarrinho)['total'] > 0;
            }
        }

        if (!$referenciasVerificadas) {
            $con->rollback();
            header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
            exit;
        }
        if ($emVenda || $emCarrinho) {
            $preservadas++;
            if (!$limparSemEstoque) {
                $con->rollback();
                header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=variacao_em_uso');
                exit;
            }
            continue;
        }

        mysqli_stmt_bind_param($stmtExcluirImagens, 'i', $idVariacaoAlvo);
        if (!mysqli_stmt_execute($stmtExcluirImagens)) {
            $con->rollback();
            header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
            exit;
        }
        mysqli_stmt_bind_param($stmtExcluirVariacao, 'ii', $idVariacaoAlvo, $idProdutoAcao);
        if (!mysqli_stmt_execute($stmtExcluirVariacao) || mysqli_stmt_affected_rows($stmtExcluirVariacao) !== 1) {
            $con->rollback();
            header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
            exit;
        }
        $excluidas++;
    }

    if (!$con->commit()) {
        $con->rollback();
        header('Location: editar_produto.php?id=' . $idProdutoAcao . '&erro=1');
        exit;
    }

    if ($limparSemEstoque) {
        header('Location: editar_produto.php?id=' . $idProdutoAcao . '&limpeza=' . $excluidas . '&preservadas=' . $preservadas);
    } else {
        header('Location: editar_produto.php?id=' . $idProdutoAcao . '&excluida=1');
    }
    exit;
}

$idProduto = filter_var($_POST['id_produto'] ?? null, FILTER_VALIDATE_INT);
$nomeRecebido = $_POST['nome'] ?? '';
$descricaoRecebida = $_POST['descricao'] ?? '';
$precoRecebido = $_POST['preco'] ?? '';
$nome = is_string($nomeRecebido) ? trim($nomeRecebido) : '';
$descricao = is_string($descricaoRecebida) ? trim($descricaoRecebida) : '';
$precoTexto = is_string($precoRecebido) ? str_replace(',', '.', $precoRecebido) : '';
$preco = is_numeric($precoTexto) ? (float) $precoTexto : -1;
$categoria = filter_var($_POST['categoria'] ?? null, FILTER_VALIDATE_INT);
$genero = filter_var($_POST['genero'] ?? null, FILTER_VALIDATE_INT);
$ativo = isset($_POST['ativo']) ? 1 : 0;
$variacoesRecebidas = $_POST['variacoes'] ?? [];
$novasVariacoesRecebidas = $_POST['novas_variacoes'] ?? [];

if (
    !$con ||
    $idProduto === false ||
    $idProduto < 1 ||
    $nome === '' ||
    mb_strlen($nome) > 255 ||
    mb_strlen($descricao) > 500 ||
    $preco < 0 ||
    !is_finite($preco) ||
    $categoria === false ||
    $categoria < 1 ||
    $genero === false ||
    $genero < 1 ||
    $genero > 4 ||
    !is_array($variacoesRecebidas) ||
    !is_array($novasVariacoesRecebidas)
) {
    header('Location: editar_produto.php?erro=1');
    exit;
}

$stmtProdutoExistente = mysqli_prepare($con, 'SELECT id_produto FROM tb_produto WHERE id_produto = ?');
$stmtCategoria = mysqli_prepare($con, 'SELECT id_categoria FROM tb_categoria WHERE id_categoria = ?');
$stmtVariacoesExistentes = mysqli_prepare($con, 'SELECT id_variacao FROM tb_variacao_produto WHERE id_produto = ?');
if (!$stmtProdutoExistente || !$stmtCategoria || !$stmtVariacoesExistentes) {
    header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
    exit;
}
mysqli_stmt_bind_param($stmtProdutoExistente, 'i', $idProduto);
mysqli_stmt_execute($stmtProdutoExistente);
if (mysqli_num_rows(mysqli_stmt_get_result($stmtProdutoExistente)) === 0) {
    header('Location: editar_produto.php?erro=1');
    exit;
}
mysqli_stmt_bind_param($stmtCategoria, 'i', $categoria);
mysqli_stmt_execute($stmtCategoria);
if (mysqli_num_rows(mysqli_stmt_get_result($stmtCategoria)) === 0) {
    header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
    exit;
}
mysqli_stmt_bind_param($stmtVariacoesExistentes, 'i', $idProduto);
mysqli_stmt_execute($stmtVariacoesExistentes);
$resultadoVariacoes = mysqli_stmt_get_result($stmtVariacoesExistentes);
$idsVariacoesExistentes = [];
while ($row = mysqli_fetch_assoc($resultadoVariacoes)) {
    $idsVariacoesExistentes[(int) $row['id_variacao']] = true;
}

if (count($variacoesRecebidas) !== count($idsVariacoesExistentes)) {
    header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
    exit;
}

$variacoesValidadas = [];
$combinacoes = [];
foreach ($variacoesRecebidas as $idRecebido => $variacao) {
    $idVariacao = filter_var($idRecebido, FILTER_VALIDATE_INT);
    if (
        $idVariacao === false ||
        !isset($idsVariacoesExistentes[$idVariacao]) ||
        !is_array($variacao) ||
        (int) ($variacao['id_variacao'] ?? 0) !== $idVariacao
    ) {
        header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
        exit;
    }

    $cor = isset($variacao['cor']) && is_string($variacao['cor']) ? trim($variacao['cor']) : '';
    $tamanho = isset($variacao['tamanho']) && is_string($variacao['tamanho']) ? trim($variacao['tamanho']) : '';
    $estoque = filter_var($variacao['estoque'] ?? null, FILTER_VALIDATE_INT);
    $chave = json_encode([mb_strtolower($cor, 'UTF-8'), $tamanho]);
    if (
        $cor === '' ||
        mb_strlen($cor) > 55 ||
        $tamanho === '' ||
        mb_strlen($tamanho) > 5 ||
        $estoque === false ||
        $estoque < 0 ||
        isset($combinacoes[$chave])
    ) {
        header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
        exit;
    }
    $combinacoes[$chave] = true;
    $variacoesValidadas[$idVariacao] = [
        'id_variacao' => $idVariacao,
        'cor' => $cor,
        'tamanho' => $tamanho,
        'estoque' => $estoque,
        'imagem' => null,
    ];
}

if (count($novasVariacoesRecebidas) > 100) {
    header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
    exit;
}
$tamanhosPermitidos = ['PP', 'P', 'M', 'G', 'GG', 'XG'];
foreach ($novasVariacoesRecebidas as $indice => $variacao) {
    if (!is_array($variacao)) {
        header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
        exit;
    }
    $cor = isset($variacao['cor']) && is_string($variacao['cor']) ? trim($variacao['cor']) : '';
    $tamanho = isset($variacao['tamanho']) && is_string($variacao['tamanho']) ? trim($variacao['tamanho']) : '';
    $estoque = filter_var($variacao['estoque'] ?? null, FILTER_VALIDATE_INT);
    $chave = json_encode([mb_strtolower($cor, 'UTF-8'), $tamanho]);
    if (
        $cor === '' ||
        mb_strlen($cor) > 55 ||
        !in_array($tamanho, $tamanhosPermitidos, true) ||
        $estoque === false ||
        $estoque < 0 ||
        isset($combinacoes[$chave])
    ) {
        header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
        exit;
    }
    $combinacoes[$chave] = true;
    $variacoesValidadas['nova-' . $indice] = [
        'id_variacao' => null,
        'cor' => $cor,
        'tamanho' => $tamanho,
        'estoque' => $estoque,
        'imagem' => null,
        'indice_upload' => $indice,
    ];
}

$diretorioUploads = __DIR__ . '/../produtos/uploads';
$imagensCriadas = [];
$salvarImagem = static function ($arquivo) use ($diretorioUploads, &$imagensCriadas) {
    if ($arquivo === null || !isset($arquivo['error']) || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $tiposPermitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (
        $arquivo['error'] !== UPLOAD_ERR_OK ||
        $arquivo['size'] > 5 * 1024 * 1024 ||
        empty($arquivo['tmp_name']) ||
        !is_uploaded_file($arquivo['tmp_name'])
    ) {
        return false;
    }
    $tipoImagem = mime_content_type($arquivo['tmp_name']);
    if (!isset($tiposPermitidos[$tipoImagem])) {
        return false;
    }
    if (!is_dir($diretorioUploads) && !mkdir($diretorioUploads, 0755, true)) {
        return false;
    }
    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $tiposPermitidos[$tipoImagem];
    if (!move_uploaded_file($arquivo['tmp_name'], $diretorioUploads . '/' . $nomeArquivo)) {
        return false;
    }
    $caminho = 'uploads/' . $nomeArquivo;
    $imagensCriadas[] = $caminho;
    return $caminho;
};
$removerImagensCriadas = static function () use (&$imagensCriadas, $diretorioUploads) {
    foreach ($imagensCriadas as $caminho) {
        $arquivo = $diretorioUploads . '/' . basename($caminho);
        if (is_file($arquivo)) {
            unlink($arquivo);
        }
    }
};
$arquivoEnviado = static function ($grupo, $indice) {
    if (!isset($_FILES[$grupo]['error'][$indice]['imagem'])) {
        return null;
    }
    return [
        'name' => $_FILES[$grupo]['name'][$indice]['imagem'] ?? '',
        'type' => $_FILES[$grupo]['type'][$indice]['imagem'] ?? '',
        'tmp_name' => $_FILES[$grupo]['tmp_name'][$indice]['imagem'] ?? '',
        'error' => $_FILES[$grupo]['error'][$indice]['imagem'],
        'size' => $_FILES[$grupo]['size'][$indice]['imagem'] ?? 0,
    ];
};

foreach ($variacoesValidadas as $chaveVariacao => &$variacao) {
    $grupoUpload = $variacao['id_variacao'] === null ? 'novas_variacoes' : 'variacoes';
    $indiceUpload = $variacao['id_variacao'] === null ? $variacao['indice_upload'] : $variacao['id_variacao'];
    $variacao['imagem'] = $salvarImagem($arquivoEnviado($grupoUpload, $indiceUpload));
    if ($variacao['imagem'] === false) {
        $removerImagensCriadas();
        header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
        exit;
    }
}
unset($variacao);

$stmtAtualizarProduto = mysqli_prepare(
    $con,
    'UPDATE tb_produto SET nome_produto = ?, descricao_produto = ?, preco = ?, id_categoria = ?, id_genero = ?, ativo = ? WHERE id_produto = ?'
);
$stmtAtualizarVariacao = mysqli_prepare($con, 'UPDATE tb_variacao_produto SET cor = ?, tamanho = ?, estoque = ? WHERE id_variacao = ? AND id_produto = ?');
$stmtInserirVariacao = mysqli_prepare($con, 'INSERT INTO tb_variacao_produto (id_produto, tamanho, cor, estoque) VALUES (?, ?, ?, ?)');
$stmtAtualizarImagem = mysqli_prepare($con, 'UPDATE tb_imagem_produto SET caminho_imagem = ? WHERE id_variacao = ?');
$stmtInserirImagem = mysqli_prepare($con, 'INSERT INTO tb_imagem_produto (id_variacao, caminho_imagem) VALUES (?, ?)');

if (!$stmtAtualizarProduto || !$stmtAtualizarVariacao || !$stmtInserirVariacao || !$stmtAtualizarImagem || !$stmtInserirImagem) {
    $removerImagensCriadas();
    header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
    exit;
}
if (!$con->begin_transaction()) {
    $removerImagensCriadas();
    header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
    exit;
}

$salvou = mysqli_stmt_bind_param($stmtAtualizarProduto, 'ssdiiii', $nome, $descricao, $preco, $categoria, $genero, $ativo, $idProduto)
    && mysqli_stmt_execute($stmtAtualizarProduto);
foreach ($variacoesValidadas as $variacao) {
    if (!$salvou) {
        break;
    }
    $cor = $variacao['cor'];
    $tamanho = $variacao['tamanho'];
    $estoque = $variacao['estoque'];
    if ($variacao['id_variacao'] !== null) {
        $idVariacao = $variacao['id_variacao'];
        $salvou = mysqli_stmt_bind_param($stmtAtualizarVariacao, 'ssiii', $cor, $tamanho, $estoque, $idVariacao, $idProduto)
            && mysqli_stmt_execute($stmtAtualizarVariacao);
    } else {
        $salvou = mysqli_stmt_bind_param($stmtInserirVariacao, 'issi', $idProduto, $tamanho, $cor, $estoque)
            && mysqli_stmt_execute($stmtInserirVariacao);
        $idVariacao = $salvou ? mysqli_insert_id($con) : 0;
    }
    if ($salvou && $variacao['imagem'] !== null) {
        mysqli_stmt_bind_param($stmtAtualizarImagem, 'si', $variacao['imagem'], $idVariacao);
        $salvou = mysqli_stmt_execute($stmtAtualizarImagem);
        if ($salvou && mysqli_stmt_affected_rows($stmtAtualizarImagem) === 0) {
            mysqli_stmt_bind_param($stmtInserirImagem, 'is', $idVariacao, $variacao['imagem']);
            $salvou = mysqli_stmt_execute($stmtInserirImagem);
        }
    }
}

if ($salvou && $con->commit()) {
    header('Location: editar_produto.php?id=' . $idProduto . '&sucesso=1');
    exit;
}

$con->rollback();
$removerImagensCriadas();
header('Location: editar_produto.php?id=' . $idProduto . '&erro=1');
exit;
