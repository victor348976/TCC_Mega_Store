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
		<link rel="stylesheet" href="css/style.css" />
	</head>
	<body>

	<nav class="menu">
	  <ul>
				<li><a href="#">Usuarios</a>
					
							<ul>
								<li><a href="listar.php">Configurações</a></li>
								<li><a href="Card_Dos_Produtos/Cards.php">Produtos</a></li>
								<li><a href="cadastro.php">Cadastrar</a></li>
								<li><a href="alterar.php">Alterar</a></li>
                                <li><a href="excluir.php">Excluir</a></li>
							</ul>
				</li>
				<li><a href="#">Administradores</a>
					
							<ul>
								<li><a href="#">Listar Todos</a></li>
								<li><a href="#">Consultar</a></li>
								<li><a href="#">Cadastrar</a></li>
								<li><a href="#">Alterar</a></li>
                                <li><a href="#">Excluir</a></li>
							</ul>
				</li>
				<li><a href="#">Não logado</a>
							<ul>
								<li><a href="#">Listar Todos</a></li>
								<li><a href="#">Consultar</a></li>
								<li><a href="#">Cadastrar</a></li>
								<li><a href="#">Alterar</a></li>
                                <li><a href="#">Excluir</a></li>
							</ul>
				</li>
  </ul>
	</nav>
</body>
</html>
<?php
    }
?>