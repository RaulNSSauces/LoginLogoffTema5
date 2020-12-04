<?php
session_start();

    if(isset($_REQUEST["es"])){
        setcookie("idioma", $_REQUEST["es"], time()+172800);
        header("Location: Programa.php");
        exit;        
    }
    if(isset($_REQUEST["fr"])){
        setcookie("idioma", $_REQUEST["fr"], time()+172800);
        header("Location: Programa.php");
        exit;
    }
    
require_once '../config/confDBPDO.php';

if(!isset($_SESSION["usuarioDAW203AppLoginLogoff"])){
        header("Location: Login.php");
    }

if (isset($_REQUEST['detalle'])) {
    header('Location: Detalle.php');
    exit;
}

if (isset($_REQUEST['cerrarSesion'])) {
    session_destroy();
    header('Location: Login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Detalle</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
    </head>
    <body>
    <main>
        <header>
            <h1 style="background-color: black; color:white; text-align: center; padding: 30px;">LOGIN LOGOFF TEMA 5</h1>
        </header>
            <form name="formularioIdioma" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <button class="btn" type="submit" name="es" value="es"><img src="../webroot/media/es.png" width="50px" height="40px"></button>
                <button class="btn" type="submit" name="fr" value="fr"><img src="../webroot/media/fr.png" width="45px" height="40px"></button>
            </form>
        <?php
            try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.;
                
                $SQL = "Select T01_NumConexiones, T01_DescUsuario from T01_Usuario where T01_CodUsuario=:CodUsuario";
                
                $consulta = $miDB -> prepare($SQL);
                $consulta -> bindParam(":CodUsuario",$_SESSION["usuarioDAW203AppLoginLogoff"]);
                $consulta ->execute();
                $oRegistro = $consulta->fetchObject();
                $nConexiones = $oRegistro->T01_NumConexiones;
                $descUsuario = $oRegistro->T01_DescUsuario;
                     
            }catch (PDOException $miExcepcionPDO){ //Creo una excepción de errores.
                echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
                echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
            } finally {
                unset($miDB); //Cierro la conexión a la base de datos.
            }
            
            if($_COOKIE["idioma"]=="es"){
                echo "<h2>Bienvenido ".$descUsuario."</h2>";
                if($nConexiones==1){
                    echo "<h2>Esta es la primera vez que te conectas, bienvenid@!</h2>";
                }else{
                    echo "<h2>Te has conectado ".$nConexiones." veces</h2>";
                }
                if($nConexiones>1){
                    echo "<h2>Fecha de la última conexión ".date("d-m-Y H:i:s",$_SESSION["FechaHoraUltimaConexionAnterior"])."</h2>";
                }
            }else{
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
                <input class="btn" type="submit" value="CERRAR SESIÓN" name="cerrarSesion">
                <input class="btn" type="submit" value="DETALLE" name="detalle">
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