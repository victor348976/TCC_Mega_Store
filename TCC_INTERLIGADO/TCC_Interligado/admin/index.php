<?php
require_once __DIR__ . '/../geral/auth.php';
require_admin();

header('Location: cadastro_produto.php');
exit;
