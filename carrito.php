<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Interfaz de pedidos al almacén</title>
</head>

<body>
	<?php 
	session_start();
$nombreCarrito= date("Y")."-".date("m")."-".date("d")."_".$_SESSION['nombre'];
$path=$nombreCarrito.".txt";
    $carrito=fopen($path, "r");

    //$carrito = fopen($nombreCarrito.".txt", "r");
    if (flock($carrito,LOCK_SH)){
      while ($linea = fscanf($carrito, "%s\t%s\t%s\n")) {
    // list divide un array en partes y las asigna a variables individuales
        list($producto, $cantidad, $precio) = $linea;
        $prod[]=$producto;
        $cant[]=$cantidad;
        $prec[]=$precio;
        
      }
      flock($carrito,LOCK_UN);
    }
    fclose($carrito);


//echo $linea;
    ?>
    <h5><?php echo date("Y")."-".date("m")."-".date("d");?></h5>
    <h3>Carrito de compra de <?php echo $_SESSION['nombre'];?></h3>
    <table>
      <tr style="background: gainsboro">
          <td style=' width: 20%;text-align: center;'><b>Cantidad</b></td>
          <td style=' width: 20%;text-align: center;'><b>Concepto</b></td>
          <td style=' width: 20%;text-align: center;'><b>Total</b></td>
      </tr>

    <?php
      for ($j=0; $j < count($prod); $j++) {
    ?>
    <tr>
      <td style=' width: 20%;text-align: center;'><?php echo $cant[$j]; ?></td>
      <td style=' width: 20%;text-align: center;'><?php echo $prod[$j]; ?></td>
      <td style=' width: 20%;text-align: center;'><?php echo $prec[$j]; ?></td>
    </tr>
    <?php
       }
	   ?>
</table> 
<br>
<h3><a href="acceder.php">Regresar</a></h3>
 </body>
 </html>