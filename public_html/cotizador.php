<?php
// Where the file is going to be placed 
$target_path = "uploads/";

/* Add the original filename to our target path.  
Result is "uploads/filename.extension" */
$target_path = $target_path . date("Y-m-d H:i:s") . " - " . $_POST['email'] . " - " . basename( $_FILES['uploadedfile']['name']); 

ini_set('memory_limit', '10240M'); // Agranda la memoria para leer el archivo.

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
$x_max = 0;
$y_max = 0;
$z_max = 0;
$x_min = 9999999999999999;
$y_min = 9999999999999999;
$z_min = 9999999999999999;

$PARAMETRO_CURVA_1 =	0.1;
$PARAMETRO_CURVA_2 = 0.93;
$INCIDENCIA_TIEMPO = 0.9;
$INCIDENCIA_MATERIAL = 0.1;
$COSTO_ABS = 338; 
$CAPAS_POR_MIN = 2.54;
$CANT_CAPAS_POR_HORA = CAPAS_POR_MIN*60;
$COSTOS_FIJOS_DIARIO = 176.60; 
$cant_capas_pieza = 0;
$RESOLUCION = 0.254;
$DENSIDAD_ABS = 0.00105;
$PARAMETRO_X = 0.6;
$MOTO = 30;



$filepath = $target_path;
$fp = fopen($filepath, "rb");
$section = @file_get_contents($filepath, NULL, NULL, 0, 79);
fseek($fp, 80);
$data = fread($fp, 4);
$numOfFacets = unpack("I", $data);
for ($i = 0; $i < $numOfFacets[1]; $i++){
	//Start Normal Vector
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$normalVectorsX[$i] = $hold[1];
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$normalVectorsY[$i] = $hold[1];
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$normalVectorsZ[$i] = $hold[1];
	//End Normal Vector
	//Start Vertex1
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex1X[$i] = $hold[1];
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex1Y[$i] = $hold[1];
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex1Z[$i] = $hold[1];
	//End Vertex1
	//Start Vertex2
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex2X[$i] = $hold[1];
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex2Y[$i] = $hold[1];
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex2Z[$i] = $hold[1];
	//End Vertex2
	//Start Vertex3
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex3X[$i] = $hold[1];
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex3Y[$i] = $hold[1];
	$data = fread($fp, 4);
	$hold = unpack("f", $data);
	$vertex3Z[$i] = $hold[1];
	//End Vertex3
	//Attribute Byte Count
	$data = fread($fp, 2);
	$hold = unpack("S", $data);
	$abc[$i] = $hold[1];
	
	$x_vals = array($vertex1X[$i], $vertex2X[$i], $vertex3X[$i]);
	$y_vals = array($vertex1Y[$i], $vertex2Y[$i], $vertex3Y[$i]);
	$z_vals = array($vertex1Z[$i], $vertex2Z[$i], $vertex3Z[$i]);
	if(max($x_vals) > $x_max) {
		$x_max = max($x_vals);
	}
	if(max($y_vals) > $y_max) {
		$y_max = max($y_vals);
	}	
	if(max($z_vals) > $z_max) {
		$z_max = max($z_vals);
	}	
	if(min($x_vals) < $x_min) {
		$x_min = min($x_vals);
	}
	if(min($y_vals) < $y_min) {
		$y_min = min($y_vals);
	}	
	if(min($z_vals) < $z_min) {
		$z_min = min($z_vals);
	}	
	
}
$x_dim = $x_max - $x_min;
$y_dim = $y_max - $y_min;
$z_dim = $z_max - $z_min;


$volume = $x_dim*$y_dim*$z_dim;



$cant_capas_pieza = $z_dim/$RESOLUCION;


$a = ($COSTO_ABS/1000*$volume*$DENSIDAD_ABS*0.4*(1+$INCIDENCIA_MATERIAL) + $COSTOS_FIJOS_DIARIO*$cant_capas_pieza/$CAPAS_POR_MIN/9/60*(1+$INCIDENCIA_TIEMPO));

if((1-$PARAMETRO_CURVA_1*log10($z_dim/10))/(1-$PARAMETRO_CURVA_1*log10($PARAMETRO_X))*$PARAMETRO_CURVA_2 > 0.96)
{
	$b = 0.96;
}
else
{
	$b = (1-$PARAMETRO_CURVA_1*log10($z_dim/10))/(1-$PARAMETRO_CURVA_1*log10($PARAMETRO_X))*$PARAMETRO_CURVA_2;
}
$precio = ($a / (1-$b))+ $MOTO;

$mensaje = "Recibirá en su e-mail una cotización budgetaria. \r\n Contáctenos para obtener una cotización firme.\r\n\r\n Precio estimado de la pieza: AR$ ".number_format($precio, 0, '.', ',');

} 

else{
    $mensaje = "El cotizador funciona únicamente con archivos de una sola pieza en formato STL binario.";
}


//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
//GENERACION DEL WORD

// GENERACION DEL NUMERO DE COTIZACION
//--------------------------------------
$file = "registro_q.txt"; 
// check if file exists 
if ( !file_exists($file)) 
    { 
        // if it does not, set counter to 0 
        $n_cotizacion = 1; 
        // create file 
        $handle = fopen($file, 'w') or die("can't open file"); 
        //write the n_cotizacion as 0 
        fwrite ($handle, $n_cotizacion); 
    } 
else     
//if counter file exists open it 
    { 
        $handle = fopen ($file, 'r'); 
        //read the number in the file  
        $n_cotizacion = fread($handle, 5); 
        //add one to it 
        $n_cotizacion++;  
        fclose($handle);     
        //clear the content previously in file and write value of $n_cotizacion 
        $handle = fopen ($file, 'w'); 
        fwrite ($handle, $n_cotizacion); 
    } 
fclose ($handle);
//---------------------------------------

// Lee el template de la cotizaciÃ³n
$plantilla = file_get_contents('Cotizacion.rtf');

// Agregamos los escapes necesarios
$plantilla = addslashes($plantilla);
$plantilla = str_replace('\r','\\r',$plantilla);
$plantilla = str_replace('\t','\\t',$plantilla);

// Datos de la plantilla

$precio_1 = number_format($precio, 0, '.', ',');
//$total = number_format($precio, 0, '.', ',');
$cliente = $_POST['email'];
$Q13 = "Q13";
$aux = "$Q13$n_cotizacion";
$numero_de_cotizacion = $aux;
$nombre_archivo = "$aux-$cliente.rtf";
$archivo_subido = basename( $_FILES['uploadedfile']['name']);

// Procesa la plantilla
eval( '$rtf = <<<EOF_RTF
' . $plantilla . ' 
EOF_RTF;
' );

//esta es la nueva sesscion 
$rtf = str_replace('\\\\','\\',$rtf);
 
if(!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data, $file_append = false) {
      $fp = fopen($filename, (!$file_append ? 'w+' : 'a+'));
        if(!$fp) {
          trigger_error('file_put_contents cannot write in file.', E_USER_ERROR);
          return;
        }
      fputs($fp, $data);
      fclose($fp);
    }
  }
  
// Guarda el RTF generado
file_put_contents("$nombre_archivo",$rtf);

// Enviar mail con adjunto.
$mensaje_mail = "Gracias por probar nuestro cotizador online. Creemos que es una herramienta muy útil en etapas de desarollo. \r\nAdjunta encontrará la cotización de tipo budgetaria para tener una referencia de precios. \r\nSaludos, \r\nA&M";
    $file_size = filesize($nombre_archivo);
    $handle = fopen($nombre_archivo, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($nombre_archivo);
    $header .= "From: A&M 3D <contacto@am3d.com.ar>\r\n"; 
	$header .= "BCC: A&M 3D <contacto@am3d.com.ar>\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
	$header .= $mensaje_mail."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$nombre_archivo."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$nombre_archivo."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";



mail($cliente, "Cotización BUDGET - Impresión 3D - AR$ ".number_format($precio, 0, '.', ','), $mensaje_mail, $header);
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------


?>
<!DOCTYPE html>
<html>
<head>
	<title>Cotizador Online</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="Stylesheet" type="text/css" href="css/estilos.css">
	
	
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
		<td id="header" colspan="3" style="background-image:url('images/web_empresa_01.jpg')" width="950" height="276" alt=""></td>
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
		<td style="background-image:url('images/web_empresa_03.jpg')" width="590" height="441" alt="" valign="top">
			<div id="mensaje" style="width:70%; height:100px; text-align:center; background-color:white; margin-left:auto; margin-right:auto; padding-top:10px">
				<p><?php echo $mensaje?></h1>
			</div>
		</td>
		<td rowspan="4" style="background-image:url('images/web_empresa_04.jpg')" width="89" height="441" alt=""></td>
	</tr>
</table>
</body>
</html>