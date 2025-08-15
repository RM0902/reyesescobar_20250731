<?php

$host_aceptados = array('localhost', '127.0.0.1');
$metodo_aceptado = 'POST';
$usuario_correcto = "Admin";
$password_correcto = "Admin";

$txt_usuario = ( (isset($_POST["txt_usuario"])) ? $_POST["txt_usuario"] : null);
$txt_password = ( (isset($_POST["txt_password"])) ? $_POST["txt_password"] : null);
$token = "";

if(in_array($_SERVER["HTTP_HOST"], $host_aceptados) ){
    // La direccion ip es aceptada
    if($_SERVER["REQUEST_METHOD"] == $metodo_aceptado){
        // Se acepta el metodo usado por el usuario
        if (isset($txt_usuario) && !empty($txt_usuario)){
            // Si se enviaron los valores en el campo usuario
            if(isset($txt_password) && !empty($txt_password)){
                // Si se envio el valor de la contrasena
                if($txt_usuario==$usuario_correcto){
                    // El valor ingresado del campo usuario es correcto
                    if($txt_password==$password_correcto){
                        // El valor ingresado del campo password es correcto
                        $ruta = "welcome.php";
                        $msg = "";
                        $codigo_estado = 200;
                        $texto_estado = "Ok";
                        list($usec, $sec) = explode(' ', microtime());
                        $token = base64_encode(date("Y-m-d H:i:s",$sec).substr($user,1));

                    }else{
                        // El valor ingresado del campo password no es correcto
                         $ruta = "";
                        $msg = "SU PASSWORD ES INCORRECTA";
                        $codigo_estado = 400;
                        $texto_estado = "Bad Request";
                        $token = "";
                    }
                }else{
                    // El valor ingresado del campo usuario no es correcto
                    $ruta = "";
                    $msg = "NO SE RECONOCE EL USUARIO INGRESADO";
                    $codigo_estado = 401;
                    $texto_estado = "Unauthorized";
                    $token = "";
                }
            }else{
                // El campo password esta vacio
                $ruta = "";
                $msg = "EL CAMPO DE PASSWORD ESTA VACIO";
                $codigo_estado = 401;
                $texto_estado = "Unauthorized";
                $token = "";
            }
        }else{
                // El campo usuario esta vacio
                $ruta = "";
                $msg = "EL CAMPO DE USUARIO ESTA VACIO";
                $codigo_estado = 401;
                $texto_estado = "Unauthorized";
                $token = "";
        }
    }else{
        // El metodo usado por el usuario no es aceptado
        $ruta = "";
        $msg = "EL METODO NO ES PERMITIDO";
        $codigo_estado = 405;
        $texto_estado = "Method Not Allowed";
        $token = "";
    }
}else{
        // La direccion IP no es aceptada
    $ruta = "";
    $msg = "SU EQUIPO NO ESTA AUTORIZADO PARA REALIZAR ESTA PETICION";
    $codigo_estado = 403;
    $texto_estado = "Forbidden";
    $token = "";
}

    $arreglo_respuesta = array(
        "status" => ( (intval($codigo_estado) == 200) ? "Ok": "Error" ),
        "error" => ( (intval($codigo_estado) == 200) ? "": array("code"=>$codigo_estado,"message"=>$msg) ),
        "data" => array(
            "url"=>$ruta,
            "token"=>$token
        ),
        "count"=>1
    );

    header("HTTP/1.1 ".$codigo_estado." ".$texto_estado);
    header("Content-Type: Application/json");
    echo(json_encode($arreglo_respuesta));
?>
