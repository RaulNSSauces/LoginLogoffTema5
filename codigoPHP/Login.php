<?php
    if(!isset($_COOKIE["idioma"])){ //Si no está definida la Cookie.
        setcookie("idioma", "es", time()+172800); //Defino la Cookie y le añado los parámetros que quiero que tenga por defecto, en este caso el idioma por defecto será español.
        header("Location: Login.php"); //Una vez definida mando al usuario al login para recargar la página.
        exit;
    }
    
    if(isset($_REQUEST["es"])){ //Si el usuario hace clic en el botón de España.
        setcookie("idioma", $_REQUEST["es"], time()+172800); //Establezco los valores de la cookie a español, y establezco un tiempo de vida de la cookie a 2 días.
        header("Location: Login.php"); //Recargo la página para que surjan efecto los cambios.
        exit; 
    }
    
    if(isset($_REQUEST["fr"])){ //Si el usuario hace clic en el botón de Francia.
        setcookie("idioma", $_REQUEST["fr"], time()+172800); //Establezco los valores de la cookie a francés, y establezco un tiempo de vida de la cookie a 2 días.
        header("Location: Login.php"); //Recargo la página para que surjan efecto los cambios.
        exit;
    }
    if(isset($_REQUEST['registrate'])){ //Si el usuario pulse en registrarse.
        header('Location: registro.php'); //Lo mando al formulario de registrarse.php. 
    }
    
    require_once '../core/libreriaValidacion.php'; //Incluyo la librería de validación para validar los campos que posteriormente añadiré a un array de errores.
    require_once '../config/confDBPDO.php'; //Incluyo el archivo de configuración a la base de datos PDO.
    
    $entradaOk = true; //Creo e inicializo la variable $entradaOk a true.
    
    define("OBLIGATORIO", 1); //Creo la constante OBLIGATORIO y le asigno un 1.
    define("OPCIONAL", 0); //Creo la constante OPCIONAL y le asigno un 0.

    $aErrores = ["NombreUsuario" => null, //Creo un array de errores y lo inicializo a null con los campos NombreUsuario y Contraseña.
                 "Contraseña" => null];
            
    if(isset ($_REQUEST['enviar'])){ //Compruebo que el usuario le ha dado al botón enviar.
                
        $aErrores["NombreUsuario"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST ["NombreUsuario"],16,3, OBLIGATORIO); //Compruebo que el campo NombreUsuario que introduce el usuario es válido.
        $aErrores["Contraseña"] = validacionFormularios::validarPassword($_REQUEST["Contraseña"], 8, 1, 1, OBLIGATORIO); //Compruebo que el campo Contraseña que introduce el usuario es válido.
        
        try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.

                $usuario = $_REQUEST["NombreUsuario"]; //Creo la variable $usuario y le asigno el nombre de usuario que me introduce el usuario al rellenar el campo.
                $password = $_REQUEST["Contraseña"]; //Creo la variable $password y le asigno la contraseña que introduce el usuario al rellanar el campo. 
                $passwordEncriptada = hash('sha256', $usuario . $password); //Creo una variable para encriptar la contraseña que introduce el usuario en el formulario.
                
                $SQL = "SELECT * FROM T01_Usuario WHERE T01_CodUsuario = :usuario AND T01_Password = :password"; //Hago una consulta para sacar el nombre de usuario y la contraseña de la base de datos.
                
                $resultadoSQL = $miDB -> prepare($SQL); //Preparo la consulta.
                $resultadoSQL -> bindParam(":usuario", $usuario); //Blindeamos el usuario.
                $resultadoSQL -> bindParam(":password", $passwordEncriptada); //Blindeamos la contraseña encriptada.
                
                $resultadoSQL -> execute(); //Ejecutamos la consulta.
                
                if($resultadoSQL ->rowCount()==0){ //Si el resultado de la consulta no coincide con ninguno de los campos que hay en la base de datos.
                    //Muestro un mensaje de que las credenciales son incorrectas.
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
                    $entradaOk = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el campo.
                    $_REQUEST[$campo]="";
                }
            }
    }else{
        $entradaOk=false;
    }
        if($entradaOk){ //Si los campos que introduce el usuario son correctos.
            try{
                $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
                $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.

                $SQL = "Select T01_NumConexiones, T01_FechaHoraUltimaConexion from T01_Usuario where T01_CodUsuario=:CodUsuario"; //Saco de la base de datos el número de conexiones y la fecha de la última conexión.
                
                $consulta = $miDB->prepare($SQL); //Preparo la consulta.
                $consulta -> bindParam(":CodUsuario",$_REQUEST["NombreUsuario"]); //Blindeo el código del usuario.
                
                $consulta ->execute(); //Ejecuto la consulta.
                
                $oRegistro = $consulta->fetchObject(); //Creo una variable para recoger objetos y utilizo fetchObject para avanzar el puntero en los campos de la base de datos.

                $fechaHora = $oRegistro->T01_FechaHoraUltimaConexion; //Saco el campo de la fecha de la última conexión de la base de datos.
                $nConexiones = $oRegistro->T01_NumConexiones; //Saco el campo del número de conexiones de la base de datos.
                
                $SQL2="Update T01_Usuario set T01_NumConexiones = T01_NumConexiones+1, T01_FechaHoraUltimaConexion=:FechaHoraUltimaConexion where T01_CodUsuario=:CodUsuario"; //Hago un update para modificar los valores de la base de datos, tanto el número de conexións como la fecha de la última conexión.
                
                $consulta2 = $miDB->prepare($SQL2); //Preparo la consulta.
                
                settype($nConexiones, "integer"); //Cambio el tipo de dato del número de conexiones a un entero.
                $consulta2 -> bindParam(":FechaHoraUltimaConexion",time()); //Blindeo el parámetro de la última conexión y le paso un timestamp.
                $consulta2 -> bindParam(":CodUsuario",$_REQUEST["NombreUsuario"]); //Blindeo el parámetro del nombre de usuario.
                
                $consulta2 ->execute(); //Ejecuto la consulta.
                
                session_start(); //Inicializo la sesión.
                
                $_SESSION["usuarioDAW203AppLoginLogoff"]=$_REQUEST["NombreUsuario"]; //Almaceno en la variable $_session el nombre de usuario.
                $_SESSION["FechaHoraUltimaConexionAnterior"]=$fechaHora; //Almaceno en la variable $_session la fecha de la última conexión.
                
                header("Location: Programa.php"); //Si todo es correcto mando al usuario al Programa.php.
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
        <form style="padding-top: 2%; padding-left: 45%;" name="formulario1" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <legend style="font-family: cursive; font-size: 30px;">Inicio de sesión</legend>
                    <br>
                    <div>
                        <b><label style="font-size: 20px; font-family: cursive;" for="NombreUsuario">Nombre de usuario</label></b><br>
                        <input style="width: 16%; 
                                      text-decoration: none;
                                      padding: 10px;
                                      font-weight: 600;
                                      font-size: 15px;
                                      border-radius: 6px;" type="text" name="NombreUsuario" value="<?php 
                                if($aErrores["NombreUsuario"] == null && isset($_REQUEST["NombreUsuario"])){ //Compruebo  que los campos del array de errores están vacíos y el usuario le ha dado al botón de enviar.
                                    echo $_REQUEST["NombreUsuario"]; //Devuelve el campo que ha escrito previamente el usuario.
                                }
                                ?>">
                    </div>
                <br>
                    <div>
                        <b><label style="font-size: 20px; font-family: cursive;" for="Contraseña">Contraseña</label></b><br>
                        <input style="width: 16%;
                                      text-decoration: none;
                                      padding: 10px;
                                      font-weight: 600;
                                      font-size: 15px;
                                      border-radius: 6px;" type="password" name="Contraseña" value="<?php 
                                if($aErrores["Contraseña"] == null && isset($_REQUEST["Contraseña"])){ //Compruebo  que los campos del array de errores están vacíos y el usuario le ha dado al botón de enviar.
                                    echo $_REQUEST["Contraseña"]; //Devuelve el campo que ha escrito previamente el usuario.
                                }
                                ?>">
                    </div>
                <br>
                    <button class="btn" type="submit" name="enviar">INICIAR SESIÓN</button>
                <br>
                <br>
                <p style="font-size: 20px; font-family: cursive; font-weight: bold;">¿ERES NUEVO?</p>
                    <button class="btn" type="submit" name="registrate">¡Crea tu cuenta aquí!</button>
        </form>
        </main>
        <footer>
            <table style="width: 100%;">
                <tr>
                    <td>Raúl Núñez Sebastián &copy; 2020/2021</td>
                    <td><a href="https://github.com/RaulNSSauces/LoginLogoffTema5"><img style="width: 30px; height: 30px;" src="../webroot/media/git.png"></a></td>
                </tr>
            </table>
        </footer>
    </body>
</html>
<?php
    }
?>