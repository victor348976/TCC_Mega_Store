<?php
    session_start();
    if(empty($_SESSION['id_usuario'])){
        echo"POR FAVOR, SE CADASTRE PARA ACESSAR O SITE!";
    }else{
?>
<html>
	<head>
		<title>Menu Escolar</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="../geral/css/style.css" />
	</head>
	<body>

	<nav class="menu">
	  <ul>
				<li><a href="#">Admin</a>
					
							<ul>
								<li><a href="#">Configurações</a></li>
							</ul>
				</li>
				<li><a href="#">auth</a>
					
							<ul>
								<li><a href="../auth/Cad_User.php">Cad_User</a></li>
								<li><a href="../auth/Log_User.php">Log_User</a></li>
							</ul>
				</li>
				<li><a href="#">checkout</a>
							<ul>
								<li><a href="#">Listar Todos</a></li>
							</ul>
				</li>
				<li><a href="#">Geral</a>
							<ul>
								<li><a href="#">Listar Todos</a></li>
								<li><a href="#">Consultar</a></li>
							</ul>
				</li>
				<li><a href="#">Home</a>
							<ul>
								<li><a href="#">Listar Todos</a></li>

							</ul>
				</li>
				<li><a href="#">Info</a>
							<ul>
								<li><a href="#">Listar Todos</a></li>

							</ul>
				</li>
				<li><a href="#">Produtos</a>
							<ul>
								<li><a href="../produtos/Cad_produto3.php">Cadastro de Produtos</a></li>
								<li><a href="../produtos/buscar_produto.php">Busca de Produto</a></li>
								<li><a href="../produtos/Card_produtos.php">Card dos Produto</a></li>

							</ul>
				</li>
				<li><a href="#">Prog_util</a>
							<ul>
								<li><a href="../prog_util/resetar_banco.php">Resetar Banco</a></li>

							</ul>
				</li>
				<li><a href="#">Usuario</a>
							<ul>
								<li><a href="#">Listar Todos</a></li>
	
							</ul>
				</li>
  </ul>
	</nav>
</body>
</html>
<?php
    }
?>