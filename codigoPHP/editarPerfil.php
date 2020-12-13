<?php
session_start(); //Inicializo la sesión existente.

if(!isset($_SESSION["usuarioDAW203AppLoginLogoff"])){ //Compruebo que el usuario ha pasado por el login.
    header("Location: Login.php"); //Si no se ha autenticado lo redirijo al login.
}
if(isset ($_REQUEST["cancelar"])){ //Si el usuario le da al botón de cancelar.
    header('Location: Programa.php'); //Lo redirijo al programa.
}

    require_once '../core/libreriaValidacion.php'; //Incluyo el archivo de la librería de validación para hacer comprobaciones posteriormente.
    require_once '../config/confDBPDO.php'; //Incluyo el archivo de configuración a la base de datos PDO.
    
    try{
        $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
        $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.
        
        $campos = "SELECT T01_DescUsuario, T01_NumConexiones, T01_FechaHoraUltimaConexion FROM T01_Usuario WHERE T01_CodUsuario=:CodUsuario"; //Hago una consulta SQL para sacar datos de la base de datos. 
        
        $consulta=$miDB->prepare($campos); //Preparo la consulta.
        $consulta -> bindParam(":CodUsuario",$_SESSION["usuarioDAW203AppLoginLogoff"]); //Blindeo el codigo del usuario, que en este caso es el nombre de usuario.
        $consulta ->execute(); //Ejecuto la consulta.
            $oRegistro = $consulta->fetchObject(); //Almaceno los objetos que voy a recorres con fetchObject() en una variable que se llama $oRegistro.
            //Los datos que recorro los almaceno en variables para utilizarlos después.
            $descUsuario = $oRegistro->T01_DescUsuario;
            $nConexiones = $oRegistro->T01_NumConexiones;
            $fechaHora = $oRegistro->T01_FechaHoraUltimaConexion;
                     
    }catch(PDOException $miExcepcionPDO){
        echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
        echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
    }finally{
        unset($miDB); //Cierro la conexión a la base de datos.
    }
    if(isset($_REQUEST['eliminar'])){
        try{
        $miDB=new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
        $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.
        
        $eliminarUsuario="DELETE FROM T01_Usuario where T01_CodUsuario=:CodUsuario"; //Hago una sentencia SQL para cargarme el usuario.
        
        $consulta=$miDB->prepare($eliminarUsuario); //Preparo la consulta.
        $consulta->bindParam(":CodUsuario",$_SESSION["usuarioDAW203AppLoginLogoff"]); //Blindeo el código del usuario que es el nombre de usuario de la cuenta.
        $consulta->execute(); //Ejecuto la consulta.
        
        session_destroy(); //Destruyo la sesión.
        header('Location: Login.php'); //Redirijo al usuario al login.
        exit();
        
        }catch(PDOException $miExcepcionPDO){
            echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
            echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
        } finally {
            unset($miDB); //Cierro la conexión a la base de datos.
        }
    }
    
    define ('OBLIGATORIO',1); //Creo una constante $OBLIGATORIO y le asigno un 1.
    define ('MAX_FLOAT', 3.402823466E+38); //Creo una constante del máximo permitido en un campo float.
    define ('MIN_FLOAT', -3.402823466E+38); //Creo una constante del mínimo permitido en un campo float.
    
    $error = null;
    $entradaOk=true;
    
    if(isset($_REQUEST["aceptar"])){ //Si el usuario le da al botón de aceptar.
        $error= validacionFormularios::comprobarAlfabetico($_REQUEST["DescUsuario"], 50, 4, OBLIGATORIO); //Compruebo que el campo DescUsuario lo ha introducido correctamente.
        
        if($error!=null){
            $entradaOk=false;
        }
    }else{
        $entradaOk=false;
    }
    if($entradaOk){ //Si todo está correcto.
        try{
            $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
            $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.
            
            $actualizacion="UPDATE T01_Usuario SET T01_DescUsuario = :DescUsuario WHERE T01_CodUsuario = :CodUsuario"; //Hago un update en la base de datos para cambiar el campo de la descripción del usuario.
            
            $consulta=$miDB->prepare($actualizacion); //Preparo la consulta.
            $parametros =[":DescUsuario" => $_REQUEST["DescUsuario"], //Almaceno en una variable los parámetros que le voy a pasar a la consulta previamente preparada.
                          ":CodUsuario" => $_SESSION["usuarioDAW203AppLoginLogoff"]];
            $consulta->execute($parametros); //Ejecuto la consulta pasándole los parámetros.
            
            header('Location: Programa.php'); //Redirijo al usuario al programa.
            
        }catch(PDOException $miExcepcionPDO){
            echo "<p style='color:red;'>Error ".$miExcepcionPDO->getMessage()."</p>"; //Muestro el mensaje de la excepción de errores.
            echo "<p style='color:red;'>Código de error ".$miExcepcionPDO->getCode()."</p>"; //Muestro el código del error.
        }finally{
            unset($miDB); //Cierro la conexión a la base de datos.
        }
    }else{
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar perfil</title>
        <link rel="stylesheet" href="../webroot/css/estilos.css">
    </head>
    <body>
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <div>
                        <label style="font-size: 20px; font-family: cursive; font-weight: bold;" for="CodUsuario">Código usuario: </label>
                        <input style="width: 5%;
                                      text-decoration: none;
                                      padding: 10px;
                                      font-weight: 600;
                                      font-size: 15px;
                                      border-radius: 6px;
                                      background-color:#ec7063" type="text" name="CodUsuario" value="<?php echo $_SESSION["usuarioDAW203AppLoginLogoff"]; ?>" readonly>
                    </div>
                <br>
                    <div>
                        <label style="font-size: 20px; font-family: cursive; font-weight: bold;" for="DescUsuario">Descripción del usuario: </label>
                        <input style="width: 10%;
                                      text-decoration: none;
                                      padding: 10px;
                                      font-weight: 600;
                                      font-size: 15px;
                                      border-radius: 6px;
                                      background-color:#48c9b0" type="text" name="DescUsuario" value="<?php 
                                if(isset($_REQUEST["DescUsuario"]) && $error == null){
                                    echo $_REQUEST["DescUsuario"];
                                }else{
                                   echo $descUsuario;
                                }
                                ?>">
                        <span style="color:red">
                            <?php
                                if ($error != null){
                                    echo $error;
                                }
                            ?>
                        </span>
                    </div>
                <br>
                    <div>
                        <label style="font-size: 20px; font-family: cursive; font-weight: bold;" for="NumConexiones">Número de conexiones: </label>
                        <input style="width: 2%;
                                      text-decoration: none;
                                      padding: 10px;
                                      font-weight: 600;
                                      font-size: 15px;
                                      border-radius: 6px;
                                      background-color:#ec7063" type="text" name="NumConexiones" value="<?php echo $nConexiones?>" readonly>
                    </div>
                <br>
                    <div>
                        <?php
                            if($nConexiones>1){
                        ?>
                            <label style="font-size: 20px; font-family: cursive; font-weight: bold;" for="FechaHoraUltimaConexion">Última conexión: </label>
                            <input style="width: 9%;
                                          text-decoration: none;
                                          padding: 10px;
                                          font-weight: 600;
                                          font-size: 15px;
                                          border-radius: 6px;
                                          background-color:#ec7063" type="text" name="FechaHoraUltimaConexion" value="<?php echo date("d-m-Y H:i:s",$_SESSION["FechaHoraUltimaConexionAnterior"])?>" readonly>
                         <?php
                            }
                        ?>
                    </div>
                <br>
                    <a style="font-size: 20px; font-family: cursive; font-weight: bold; text-decoration: none;" href="cambiarPassword.php">¿Quiéres cambiar la contraseña? Haz clic aquí</a>
                <br>
                <br>
                    <button class="btn" type="submit" name="aceptar">ACEPTAR</button>
                    <button class="btn" type="submit" name="cancelar">CANCELAR</button>
                    <br>
                    <br>
                    <button class="btn" type="submit" name="eliminar">ELIMINAR USUARIO</button>
        </form>
    </body>
</html>
<?php
    }
?>