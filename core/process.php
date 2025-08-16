<?php
$ip_aceptadas = array("127.0.0.1", "::1"); 
$metodo_aceptado = 'POST';
$usuario_correcto = "Admin";
$password_correcto = "Admin";

$txt_usuario = $_POST["txt_usuario"] ?? null;
$txt_password = $_POST["txt_password"] ?? null;
$token = "";

if (in_array($_SERVER["REMOTE_ADDR"], $ip_aceptadas)) {
    if ($_SERVER["REQUEST_METHOD"] == $metodo_aceptado) {
        if (!empty($txt_usuario)) {
            if (!empty($txt_password)) {
                if ($txt_usuario == $usuario_correcto) {
                    if ($txt_password == $password_correcto) {
                        $ruta = "welcome.php";
                        $msg = "";
                        $codigo_estado = 200;
                        $texto_estado = "Ok";
                        list($usec, $sec) = explode(' ', microtime());
                        $token = base64_encode(date("Y-m-d H:i:s",$sec).substr($txt_usuario,1));
                    } else {
                        $ruta = "";
                        $msg = "SU PASSWORD ES INCORRECTA";
                        $codigo_estado = 400;
                        $texto_estado = "Bad Request";
                    }
                } else {
                    $ruta = "";
                    $msg = "NO SE RECONOCE EL USUARIO INGRESADO";
                    $codigo_estado = 401;
                    $texto_estado = "Unauthorized";
                }
            } else {
                $ruta = "";
                $msg = "EL CAMPO DE PASSWORD ESTA VACIO";
                $codigo_estado = 401;
                $texto_estado = "Unauthorized";
            }
        } else {
            $ruta = "";
            $msg = "EL CAMPO DE USUARIO ESTA VACIO";
            $codigo_estado = 401;
            $texto_estado = "Unauthorized";
        }
    } else {
        $ruta = "";
        $msg = "EL METODO NO ES PERMITIDO";
        $codigo_estado = 405;
        $texto_estado = "Method Not Allowed";
    }
} else {
    $ruta = "";
    $msg = "SU EQUIPO NO ESTA AUTORIZADO PARA REALIZAR ESTA PETICION";
    $codigo_estado = 403;
    $texto_estado = "Forbidden";
}

$arreglo_respuesta = array(
    "status" => ($codigo_estado == 200 ? "Ok": "Error"),
    "error" => ($codigo_estado == 200 ? "" : array("code"=>$codigo_estado,"message"=>$msg)),
    "data" => array(
        "url"=>$ruta,
        "token"=>$token
    ),
    "count"=>1
);

header("HTTP/1.1 ".$codigo_estado." ".$texto_estado);
header("Content-Type: application/json");
echo json_encode($arreglo_respuesta);
?>
