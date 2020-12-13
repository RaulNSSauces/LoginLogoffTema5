<?php
session_start(); //Inicializo la sesión existente.

    if(isset($_REQUEST["es"])){ //Si el usuario ha pulsado el botón de España.
        setcookie("idioma", $_REQUEST["es"], time()+172800);
        header("Location: Programa.php"); //Entra en el programa con el idioma en español.
        exit;        
    }
    if(isset($_REQUEST["fr"])){ //Si el usuario ha pulsado el botón de Francia.
        setcookie("idioma", $_REQUEST["fr"], time()+172800);
        header("Location: Programa.php"); //Entra en el programa con el idioma en francés.
        exit;
    }
    
require_once '../config/confDBPDO.php'; //Incluyo el archivo de configuración a la base de datos PDO.

if(!isset($_SESSION["usuarioDAW203AppLoginLogoff"])){ //Compruebo que el usuario ha pasado por el login.
        header("Location: Login.php");
    }

if(isset($_REQUEST['detalle'])) { //Si el usuario pulsa el botón de detalle.
    header('Location: Detalle.php'); //Lo redirijo a Detalle.php.
    exit;
}

if(isset($_REQUEST['cerrarSesion'])){ //Si el usuario pulsa el botón de cerrar sesión.
    session_destroy(); //Destruyo la sesión.
    header('Location: Login.php'); //Y lo redirijo al Login.
    exit;
}
if(isset($_REQUEST['editar'])){ //Si el usuario pulsa el botón de editar.
    header('Location: editarPerfil.php'); //Lo redirijo a editarPerfil.php para que pueda editar su perfil.
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Programa</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
    </head>
    <body>
    <main>
        <header>
            <h1 style="background-color: black; color:white; text-align: center; padding: 30px;">LOGIN LOGOFF TEMA 5</h1>
        </header>
        <?php
            try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.;
                
                $SQL = "Select T01_NumConexiones, T01_DescUsuario from T01_Usuario where T01_CodUsuario=:CodUsuario"; //Hago una consulta para obtener datos de la base de datos T01_Usuario.
                
                $consulta = $miDB -> prepare($SQL); //Preparo la consulta.
                $consulta -> bindParam(":CodUsuario",$_SESSION["usuarioDAW203AppLoginLogoff"]); //Blindeo el parámetro del código del usuario.
                $consulta ->execute(); //Ejecuto la consulta.
                $oRegistro = $consulta->fetchObject(); //Almaceno los objetos que voy a recorres con fetchObject() en una variable que se llama $oRegistro.
                $nConexiones = $oRegistro->T01_NumConexiones; //Almaceno el número de conexiones.
                $descUsuario = $oRegistro->T01_DescUsuario; //Almaceno la descripción del usuario.
                     
            }catch (PDOException $miExcepcionPDO){ //Creo una excepción de errores.
                echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
                echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
            } finally {
                unset($miDB); //Cierro la conexión a la base de datos.
            }
            
            if($_COOKIE["idioma"]=="es"){ //Si la cookie es seleccionada en español.
                echo "<h2>Bienvenido ".$descUsuario."</h2>";
                if($nConexiones==1){
                    echo "<h2>Esta es la primera vez que te conectas, bienvenid@!</h2>";
                }else{
                    echo "<h2>Te has conectado ".$nConexiones." veces</h2>";
                }
                if($nConexiones>1){
                    echo "<h2>Fecha de la última conexión ".date("d-m-Y H:i:s",$_SESSION["FechaHoraUltimaConexionAnterior"])."</h2>";
                }
            }else{ //Si la cookie es seleccionada en francés.
                echo "<h2>Bienvenue ".$descUsuario."</h2>";
                if($nConexiones==1){
                    echo "<h2>C'est la première fois que vous connectez, bienvenue!</h2>";
                }else{
                    echo "<h2>Vous vous êtes connecté ".$nConexiones." fois</h2>";
                }
                if($nConexiones>1){
                    echo "<h2>Date de la dernière connexion ".date("d-m-Y H:i:s",$_SESSION["FechaHoraUltimaConexionAnterior"])."</h2>";
                }
            }
        ?>
            <form name="formulario" method="post" enctype="multipart/form-data">
                <input class="btn" type="submit" value="DETALLE" name="detalle">
                <input class="btn" type="submit" value="EDITAR PERFIL" name="editar">
                <br>
                <br>
                <input class="btn" type="submit" value="CERRAR SESIÓN" name="cerrarSesion">
            </form>
    </body>
    </main>
        <footer>
            <table style="width: 100%;">
                <tr>
                    <td>Raúl Núñez Sebastián &copy; 2020/2021</td>
                    <td><a href=""><img style="width: 30px; height: 30px;" src="../webroot/media/git.png"></a></td>
                </tr>
            </table>
        </footer>
</html>