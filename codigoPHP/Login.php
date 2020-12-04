<?php
    if(!isset($_COOKIE["idioma"])){
        setcookie("idioma", "es", time()+172800);
        header("Location: Login.php");
        exit;
    }
    
    if(isset($_REQUEST["es"])){
        setcookie("idioma", $_REQUEST["es"], time()+172800);
        header("Location: Login.php");
        exit; 
    }
    
    if(isset($_REQUEST["fr"])){
        setcookie("idioma", $_REQUEST["fr"], time()+172800);
        header("Location: Login.php");
        exit;
    }
    
    require_once '../core/libreriaValidacion.php';
    require_once '../config/confDBPDO.php';
    
    $entradaOk = true;
    
    define("OBLIGATORIO", 1);
    define("OPCIONAL", 0);

    $aErrores = ["NombreUsuario" => null, //Creo un array de errores y lo inicializo a null con los campos de la tabla Departamentos.
                 "Contraseña" => null];
            
    if(isset ($_REQUEST['enviar'])){ //Compruebo que el usuario le ha dado al botón enviar.
                
        $aErrores["NombreUsuario"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST ["NombreUsuario"],16,3, OBLIGATORIO); //Compruebo que el campo CodDepartamento que introduce el usuario es válido.
        $aErrores["Contraseña"] = validacionFormularios::validarPassword($_REQUEST["Contraseña"], 8, 1, 1, OBLIGATORIO); //Compruebo que el campo DescDepartamento que introduce el usuario es válido.
        
        try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.

                $usuario = $_REQUEST["NombreUsuario"];
                $password = $_REQUEST["Contraseña"];
                $passwordEncriptada = hash('sha256', $usuario . $password);
                
                $SQL = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :usuario AND T01_Password = :password";
                
                $resultadoSQL = $miDB -> prepare($SQL);
                $resultadoSQL -> bindParam(":usuario", $usuario);
                $resultadoSQL -> bindParam(":password", $passwordEncriptada);
                
                $resultadoSQL -> execute();
                
                if($resultadoSQL ->rowCount()==0){
                    $aErrores["NombreUsuario"] = "Error, las credenciales son incorrectas";
                    $aErrores["Contraseña"] = "Error, las credenciales son incorrectas";
                }
                    
            }catch (PDOException $miExcepcionPDO){ //Creo una excepción de errores.
                echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
                echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
            } finally {
                unset($miDB); //Cierro la conexión a la base de datos.
            }
            foreach ($aErrores as $campo => $error){
                if ($error != null) { // Comprobamos que el campo no esté vacio
                    $entradaOk = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario
                    $_REQUEST[$campo]="";
                }
            }
    }else{
        $entradaOk=false;
    }
        if($entradaOk){ //Si los campos son correctos los almaceno y se los muestro al usuario.
            try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.

                $SQL = "Select T01_NumConexiones, T01_FechaHoraUltimaConexion from T01_Usuario where T01_CodUsuario=:CodUsuario";
                
                $consulta = $miDB->prepare($SQL);
                $consulta -> bindParam(":CodUsuario",$_REQUEST["NombreUsuario"]);
                
                $consulta ->execute();
                
                $oRegistro = $consulta->fetchObject();

                $fechaHora = $oRegistro->T01_FechaHoraUltimaConexion;
                $nConexiones = $oRegistro->T01_NumConexiones;
                
                $SQL2="Update T01_Usuario set T01_NumConexiones = T01_NumConexiones+1, T01_FechaHoraUltimaConexion=:FechaHoraUltimaConexion where T01_CodUsuario=:CodUsuario";
                
                $consulta2 = $miDB->prepare($SQL2);
                
                settype($nConexiones, "integer");
                $consulta2 -> bindParam(":FechaHoraUltimaConexion",time());
                $consulta2 -> bindParam(":CodUsuario",$_REQUEST["NombreUsuario"]);
                
                $consulta2 ->execute();
                
                session_start();
                
                $_SESSION["usuarioDAW203AppLoginLogoff"]=$_REQUEST["NombreUsuario"];
                $_SESSION["FechaHoraUltimaConexionAnterior"]=$fechaHora;
                
                header("Location: Programa.php");
                exit;
                
            }catch (PDOException $miExcepcionPDO){ //Creo una excepción de errores.
                echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
                echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
            } finally {
                unset($miDB); //Cierro la conexión a la base de datos.
            }
        }else{  
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Inicio de sesión</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
    </head>
    <body>
    <main>
        <header>
            <h1 style="background-color: black; color:white; text-align: center; padding: 30px;">LOGIN LOGOFF TEMA 5</h1>
        </header>

        <form name="formularioIdioma" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <button class="btn" style="margin-left: 46%;" type="submit" name="es" value="es"><img src="../webroot/media/es.png" width="50px" height="40px"></button>
            <button class="btn" type="submit" name="fr" value="fr"><img src="../webroot/media/fr.png" width="45px" height="40px"></button>
        </form>
        <form style="padding-top: 2%; padding-left: 38%;" name="formulario1" action="<?php echo $_SERVER['PHP_SELF'];//Muestro la información del formulario en la misma página que se está ejecutando en el fichero actual.?>" method="post">
            
                <fieldset style="width: 20%; padding: 10%; background-color:#76d7c4">
                    <legend style="font-family: cursive; font-size: 30px;">Inicio de sesión</legend>
                    <div>
                        <b><label style="font-size: 20px; font-family: cursive;" for="NombreUsuario">Usuario: </label></b>
                        <input style="width: 40%;" type="text" name="NombreUsuario" value="<?php 
                                if($aErrores["NombreUsuario"] == null && isset($_REQUEST["NombreUsuario"])){ //Compruebo  que los campos del array de errores están vacíos y el usuario le ha dado al botón de enviar.
                                    echo $_REQUEST["NombreUsuario"]; //Devuelve el campo que ha escrito previamente el usuario.
                                }
                                ?>">
                    </div>
                <br>
                    <div>
                        <b><label style="font-size: 20px; font-family: cursive;" for="Contraseña">Contraseña: </label></b>
                        <input style="width: 35%;" type="password" name="Contraseña" value="<?php 
                                if($aErrores["Contraseña"] == null && isset($_REQUEST["Contraseña"])){ //Compruebo  que los campos del array de errores están vacíos y el usuario le ha dado al botón de enviar.
                                    echo $_REQUEST["Contraseña"]; //Devuelve el campo que ha escrito previamente el usuario.
                                }
                                ?>">
                    </div>
                <br>
                    <button class="btn" type="submit" name="enviar">INICIAR SESIÓN</button>
            </fieldset>
        </form>
    </body>
    </main>
        <footer>
            <table style="width: 100%;">
                <tr>
                    <td>Raúl Núñez Sebastián &copy; 2020/2021</td>
                    <td><a href="https://github.com/RaulNSSauces/LoginLogoffTema5"><img style="width: 30px; height: 30px;" src="../webroot/media/git.png"></a></td>
                </tr>
            </table>
        </footer>
</html>
<?php
    }
?>