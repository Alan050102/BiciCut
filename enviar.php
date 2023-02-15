<?php  

//Conexion de php
<Location "/">
  AllowMethods GET POST OPTIONS
</Location>

// Formato de la fecha y hora de envio del formulario
date_default_timezone_set('America/Mexico_City');
$fechaActual = date("d/m/Y");
$horaActual = date("g:i A");

// Llamando a los campos
$nombre = $_POST['nombre'];
$codigo = $_POST['codigo'];
//$correo = $_POST['correo'];
$from_email     = filter_var($_POST["correo"], FILTER_SANITIZE_STRING); 
$reply_to_email = filter_var($_POST["correo"], FILTER_SANITIZE_STRING);
$seleccion = $_POST['seleccion'];
$mensaje = $_POST['mensaje'];
//$archivo = $_FILES['archivo'];

// Datos para el correo
$destinatario = "alan.nuno4804@alumnos.udg.mx";
$asunto = "Se ha presentado un problema";

// Datos que se enviaran en el correo
$carta = "Enviado el: $fechaActual a las: $horaActual \n";
$carta .= "De: $nombre \n";
$carta .= "Codigo de estudiante: $codigo \n";
$carta .= "Correo: $from_email \n";
$carta .= "La problematica: $seleccion \n";
$carta .= "Descripcion y Ubicacion: $mensaje";
//$carta .= "Archivo adjunto: $archivo";

    //Obtener datos del archivo subido 
    if($_FILES['my_file']['tmp_name'])
    {
    $file_tmp_name    = $_FILES['my_file']['tmp_name'];
    $file_name        = $_FILES['my_file']['name'];
    $file_size        = $_FILES['my_file']['size'];
    $file_type        = $_FILES['my_file']['type'];

    //Leer el archivo y codificar el contenido para armar el cuerpo del email
    $handle = fopen($file_tmp_name, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $encoded_content = chunk_split(base64_encode($content));
 
    $boundary = md5("pera");

        //Encabezados
        $headers = "MIME-Version: 1.0\r\n"; 
        $headers .= "From:".$from_email."\r\n"; 
        $headers .= "Reply-To: ".$reply_to_email."" . "\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n"; 
        
    //Texto plano
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
    $body .= chunk_split(base64_encode($carta)); 

    //Adjunto
    $body .= "--$boundary\r\n";
    $body .="Content-Type: $file_type; name=".$file_name."\r\n";
    $body .="Content-Disposition: attachment; filename=".$file_name."\r\n";
    $body .="Content-Transfer-Encoding: base64\r\n";
    $body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n"; 
    $body .= $encoded_content; 
    }
    else
    {
    $boundary = md5("pera");

    //Encabezados
    $headers = "MIME-Version: 1.0\r\n"; 
    $headers .= "From:".$from_email."\r\n"; 
    $headers .= "Reply-To: ".$reply_to_email."" . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n"; 
        
    //Texto plano
    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
    $body .= "Content-Transfer-Encoding: base64\r\n\r\n"; 
    $body .= chunk_split(base64_encode($carta)); 

    }

    // Enviando Mensaje
    $sendMail = @mail($destinatario, $asunto, $body, $headers);

    if($sendMail) //Muestro mensajes segun se envio con exito o si fallo
    {
            echo"<script>
            alert('Gracias por llenar el formulario, en un momento se le atendera su problema');
            window.location='index.html'
            </script>";
            //header('Location:index.html');
    }else{
        echo "<script>
        alert('Lo siento, se ha producido un error y no fue posible mandar su problematica');
        window.location='index.html'
        </script>";
    }  

     // Datos para el correo del usuario
    $remitente = "$from_email";
    $asunto2 = "Gracias por mencionar el problema";  
    
    // Datos que se enviaran en el correo
    $carta2 = "Hola, $nombre gracias por avisar su problematica y se le buscara darle solución lo antes posible por favor no se mueva, de donde se encuentra actualmente y gracias por reportar la problematica y usar la pagina de bicicut.";

    // Enviando el Mensaje al usuario
    mail($remitente, $asunto2, $carta2);
?> 
