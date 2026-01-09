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
        <form method="get" action="Logon.php">
            <table border="0">
                <tr>
                    <td align="center" colspan="2">
                      <h2><strong>Login Usuário</strong></h2>
                    </td>
                </tr>
                <tr>
                    <td align="right" width="25%">Email:
                    </td>
                    <td align="left" width="57%">
                        <input type="mail" value="" name="mail"
                        placeholder="Digite o Usuário">
                    </td>
                </tr>
                <tr>
                 <td align="right" width="25%">Senha: </td>
                 <td align="left" width="57%">
                  <div name="logsenha">
                  <input type="password" name="logsenha" placeholder="Digite a Senha">
                  <img src="olhorisc.png" name="logsenha">
                  </div>
                 </td>
                </tr>
                <tr>
                 <td align="center" colspan="2">
                  <br>
                  <input type="submit" value="Entrar" name="Entrar">
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <input type="button" onclick="location.replace('logon.php');" value="Limpar"><br>
                 </td>
                </tr>
            </table>
        </form>
        <script src="logsenha.js"></script>
        <?php
             if(isset($_GET["Entrar"])){
                $email=$_GET["mail"];
                $senha=$_GET["logsenha"];
                $erro='';
                if($email==''){
                    $erro="Digite o Usuário<br>";
                }
                if($senha==''){
                    $erro.="Digite a Senha<br>";
                } 
                if($erro==''){
                    $sql = "SELECT * 
                            FROM tb_usuario 
                            WHERE email = '$email'";
                    $r = mysqli_query($con, $sql);
                    if (mysqli_num_rows($r) > 0) {
                     $usuario = mysqli_fetch_assoc($r);
                        if($senha == $usuario['senha']){  
                            session_start();
                            $_SESSION['id_usuario'] = $usuario['id_usuario'];
                            echo "Login realizado com sucesso!";
                            //header("Location: menu_principal.php");
                        }else{
                            $erro.="Senha incorreta!";
                        }
                    } else {
                        $erro.="Usuário não encontrado!";
                    }
                    if($erro==''){

                    }else{
                        echo"<font color='red' size='4'>$erro</font>";
                    }
                }else{
                    echo"<font color='red' size='4'>$erro</font>";
                }
             }
             if(!isset($_GET['Entrar'])){
                echo"<form method='GET' action='Cad_User.php'>
                        <table border='0'>
                            <tr>
                                <td align='center'>
                                    <font color='blue' size='2'>Cadastro Usuário</font>
                                </td>
                            </tr>
                            <tr>
                                <td align='center'>
                                    <input  type='submit' value='Cadastrar'> 
                                </td>
                            </tr>
                        </table>
                    </form>";
             }
        ?>
    </center>
</body>
</html>