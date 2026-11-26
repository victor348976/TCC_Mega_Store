<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/conexao.php';

function current_user(): ?array
{
    global $con;

    $idUsuario = (int) ($_SESSION['id_usuario'] ?? 0);
    if ($idUsuario <= 0) {
        return null;
    }

    $stmt = mysqli_prepare($con, 'SELECT id_usuario, nome_usuario, email, numero, tipo, data_cadastro FROM tb_usuario WHERE id_usuario = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $idUsuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);

    if (!$usuario) {
        unset($_SESSION['id_usuario'], $_SESSION['usuario_logado']);
        return null;
    }

    return $usuario;
}

function require_login(): array
{
    $usuario = current_user();
    if (!$usuario) {
        header('Location: ../auth/Cadastro_mega_store.php');
        exit;
    }

    return $usuario;
}

function require_admin(): array
{
    $usuario = require_login();
    if ((int) $usuario['tipo'] !== 1) {
        header('Location: ../usuario/perfil.php?acesso=negado');
        exit;
    }

    return $usuario;
}
