<?php
if (isset($_POST['enviar_x'])) {
	$mensaje="";
	// datos ingresados en el formulario
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$email = $_POST['email'];
	$comentario = $_POST['comentario'];
	
	$to = 'contacto@am3d.com.ar';
	$subject = 'Consulta enviada desde la pagina AM3D.COM.AR';
	$body = "Nombre: " . $nombre . " " . $apellido . "\r\n" . "Email: " . $email . "\r\n" . "Comentario: ". $comentario;
	$headers = 'From: ' . $email;
	
	if (@mail($to, $subject, $body, $headers))
	{ 
		$mensaje = "Su consulta se envi&oacute; correctamente";
	}
	else 
	{
		$mensaje = "Error. Intente en unos minutos";
	} 
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Contactate con nosotros</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="Stylesheet" type="text/css" href="css/estilos.css">
	<style type="text/css">
	.needsfilled {
		font-size:10px;
		font-weight: bold;
		color:red;
	}	
	</style>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/validation.js"></script>
	
	
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-38446831-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="950" height="717" border="0" cellpadding="0" cellspacing="0" align="center" style="margin-top:10px; margin-bottom:20px">
	<tr>
		<td id="header" colspan="3" style="background-image:url('images/web_contacto_01.jpg')" width="950" height="276" alt=""></td>
	</tr>
	<tr>
		<td id="buttons">
			<div>
				<ul id="nav">
					<li id="botonEmpresa"><a href="empresa.html"></a></li>
					<li id="botonContacto"><a href="contacto.php"></a></li>
					<li id="botonCotizador"><a href="cotizador.html"></a></li>
					<li id="botonFotos"><a href="fotos.html"></a></li>
				</ul>
			</div>
		</td>
		<td style="background-image:url('images/web_contacto_03.jpg')" width="590" height="441" align="center">
			<form id="theform" method="post" action="contacto.php">
				<div style="width:200px; height:400px; margin-left:auto; margin-right:auto;text-align:center;">
					<input type="text" id="nombre" name="nombre" class="loginbox" style="margin-top:48px; width:140px; height:16px;text-align:center;" />
					<input type="text" id="apellido" name="apellido" class="loginbox" style="margin-top:37px; width:140px; height:16px;text-align:center;" />
					<input type="text" id="email" name="email" class="loginbox" style="margin-top:33px; width:170px; height:16px;text-align:center;"/>
					<textarea rows="7" id="comentario" name="comentario" class="loginbox" style="resize: none; overflow: hidden;margin-top:50px; width:170px; height:100px;text-align:left;"></textarea>
					<input type="image" src="images/boton_enviar.png" name="enviar" id="enviar" value="enviado" onMouseOut="this.src='images/boton_enviar.png'" onMouseOver="this.src='images/boton_enviar_over.png'"/>
				</div>
				<span id="mensaje"><?php echo $mensaje ?></span>
			</form>
		</td>
		<td rowspan="4" style="background-image:url('images/web_contacto_04.jpg')" width="89" height="441" alt=""></td>
	</tr>
</table>
</body>
</html>