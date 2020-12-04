<?php
        /**
            *@author: Raúl Núñez
            *@since: 28/11/2020
        */ 
            
        require_once "../config/confDBPDO.php"; 
        
            try {
                $miDB = new PDO(DNS,USER,PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = <<<EOD
                        CREATE TABLE IF NOT EXISTS T02_Departamento(
                            T02_CodDepartamento VARCHAR(3) PRIMARY KEY,
                            T02_DescDepartamento VARCHAR(255) NOT NULL,
                            T02_FechaCreacionDepartamento INT NOT NULL,
                            T02_VolumenNegocio FLOAT NOT NULL,
                            T02_FechaBajaDepartamento INT DEFAULT NULL
                        )ENGINE=INNODB;

                        CREATE TABLE IF NOT EXISTS T01_Usuario(
                            T01_CodUsuario VARCHAR(10) PRIMARY KEY,
                            T01_Password VARCHAR(64) NOT NULL,
                            T01_DescUsuario VARCHAR(255) NOT NULL,
                            T01_NumConexiones INT DEFAULT 0,
                            T01_FechaHoraUltimaConexion INT,
                            T01_Perfil enum('administrador', 'usuario') DEFAULT 'usuario',
                            T01_ImagenUsuario mediumblob NULL
                        )ENGINE=INNODB;
EOD;
                
                $miDB->exec($sql);
                
                echo "<h3> <span style='color: green;'>"."Tablas creadas correctamente</span></h3>";
            }
            catch (PDOException $excepcion) {
                $errorExcepcion = $excepcion->getCode();
                $mensajeExcepcion = $excepcion->getMessage();
                
                echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";
                echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;
            } finally {
                unset($miDB);
            }
?>