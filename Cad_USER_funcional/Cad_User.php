<?php
  include("conexao.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senha</title>
</head>
<body>
    <center>
        <form method="get" action="Cad_User.php">
            <table border="0">
                <tr>
                    <td align="center" colspan="2">
                      <h2><strong>Cadastro Usuário</strong></h2>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">Usuário:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>
                        <input type="text" value="" name="user"
                        placeholder="Digite seu nome de Usuário">
                      </div>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">Email:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>
                        <input type="text" value="" name="mail"
                        placeholder="Digite seu  Email">
                      </div>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">Telefone:
                    </td>
                    <td align="left" width="57%">
                      <div name='user'>
                        <input type="text" value="" name="tel"
                        placeholder="Digite seu Numero de telefone">
                      </div>
                    </td>
                </tr>
                <tr>
                 <td align="right" width="25%">Senha: </td>
                 <td align="left" width="57%">
                  <div name="senha">
                  <input type="password" name="senha" placeholder="Digite a Senha">
                  <img src="olhorisc.png" name="senha">
                  </div>
                 </td>
                </tr>
                <tr>
                 <td align="right" width="25%">Repita senha: </td>
                 <td align="left" width="57%">
                  <div name="repsenha">
                  <input type="password" name="repsenha" placeholder="Repita a Senha">
                  <img src="olhorisc.png" name="repsenha">
                  </div>
                 </td>
                </tr>
                <tr>
                 <td align="center" colspan="2">
                  <br>
                  <input type="submit" value="Cadastrar" name="cadastrar">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="button" onclick="location.replace('Cad_user.php');" value="Limpar"><br>
                 </td>
                </tr>
            </table>
        </form>
        <script src="senha.js"></script>
    </body>
    <?php
    $data = date('Y/m/d');
    echo "$data";
     if(isset($_GET["cadastrar"])){
       $user=$_GET["user"];
       $mail=$_GET["mail"];
       $tel=$_GET["tel"];
       $senha=$_GET["senha"];
       $repsenha=$_GET["repsenha"];
       $erro='';
       if($user==''){//verifica se o nome de usuario não é vazio
         $erro.="Digite seu nome de Usuário<br>";
       }
       if($mail==''){//verifica se o email não é vazio
         $erro.="Digite seu Email<br>";
       }
       if($tel==''){//verifica se o telefone não é vazio
         $erro.="Digite seu Telefone<br>";
       }
       if($senha==''){//verifica se a senha não é vazia
         $erro.="Digite a Senha<br>";
       } 
       if($repsenha==''){//verifica se a repete senha  não é vazia
         $erro.="Repita a Senha<br>";
       }
       if($senha!=$repsenha and $senha!='' and $repsenha!=''){
         $erro.="Senhas são diferentes";
       } 
       //variaveis para verificar se a senha é valida
        $tamanhoMinimo = false;
        $temMaiuscula = false;
        $temMinuscula = false;
        $temNumero = false;
        $temEspecial = false;
        $temEspaco = false;
       for ($i = 0; $i < strlen($senha); $i++) {
    $char = $senha[$i];
    if (8< strlen($senha)) {//verifica se a senha tem no minimo 8 characteres
        $tamanhoMinimo = true;
    } 
    if (ctype_upper($char)) {//Verifica se possui letra maiuscula
        $temMaiuscula = true;
    } 
    if (ctype_lower($char)) {//Verifica se possui letra minuscula
        $temMinuscula = true;
    } 
    if (ctype_digit($char)) {//Verifica se possui numeros
        $temNumero = true;
    } 
    if (!ctype_alnum($char)) {//Verifica se possui characteres especiais
        $temEspecial = true;
    }
    if ($char == " ") {//Verifica se possui letra maiuscula
        $temEspaco = true;
    }
}
      if(!$tamanhoMinimo){
        $erro.="A senha deve conter no mínimo 8 characteres<br>";
      }
      if(!$temMaiuscula){
        $erro.="A senha deve conter no mínimo 1 letra maiuscula<br>";
      }
      if(!$temMinuscula){
        $erro.="A senha deve conter no mínimo 1 letra minuscula<br>";
      }
      if(!$temNumero){
        $erro.="A senha deve conter no mínimo 1 numero<br>";
      }
      if(!$temEspecial){
        $erro.="A senha deve conter no mínimo 1 character especial<br>";
      }
      if($temEspaco){
        $erro.="A senha não pode conter espaços<br>";
      }
       if($erro==''){
        $sql = "INSERT INTO tb_usuario (nome, email, senha, numero, tipo, data_cadastro) VALUES ('$user', '$mail', '$senha', '$tel', '0', '$data')";
         $r= mysqli_query($con,$sql);
         $usuario = mysqli_fetch_assoc($r);
         session_start();
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
         echo"<font color=green size=4>Usuario Cadastrado com Sucesso</font>";
         //header("Location: menu_principal.php");
       }
     else{
       echo"<font color=red size=4>$erro</font>";
     }
     }
    ?>
    </center>
</html>