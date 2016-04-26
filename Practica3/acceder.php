<!DOCTYPE>
<html>
<head>
	<title>Procesando pedido</title>
	<meta charset="UTF-8">
</head>

<body>
<h1>Pedidos a DianaYada</h1>
<?php 
	session_start();
    if(isset($_POST['nombre'])){
      echo "<h2>Hola ". $_POST['nombre']."</h2>";
      $_SESSION['nombre']=$_POST['nombre'];
    }elseif(isset($_SESSION['nombre'])){
      echo "<h2>Hola ". $_SESSION['nombre']."</h2>";
    };
?>
<br><br>
<form action="agregar.php" method="post">
  <p>Seleccione el producto:
	  <select name="producto">
	  	<option value="-">----------</option>
		  <?php 
			    $_SESSION["prod"] = array();
			    $_SESSION["cant"] = array();
			    $_SESSION["prec"] = array();
			   /* código que rellena una lista desplegable a partir del contenido del almacén. 
			    El código es ilustrativo: el alumno debe razonar si esta interfaz es la más 
			    adecuada y sustituirla si lo considera oportuno*/
			    $file = fopen("almacen.txt", "r");
			    if (flock($file,LOCK_SH)){
			      while ($linea = fscanf($file, "%s\t%s\t%s\n")) {
			  	// list divide un array en partes y las asigna a variables individuales
			        list($producto, $cantidad, $precio) = $linea;
			        $_SESSION["prod"][]=$producto;
			        $_SESSION["cant"][]=$cantidad;
			        $_SESSION["prec"][]=$precio;
			        if($cantidad>0){
			          print ("<option name='producto' value=\"$producto\">$producto</option>\n");
			        }

			      }
			      flock($file,LOCK_UN);
			    }
			   fclose($file);
   ?>
	   </select>
	</p>
   <p>Cantidad: <input type="number" name="cantidad" min="1"></p>
  <blockquote> <input type="submit" name="pedir" value="Añadir al carro"></blockquote>


</form>
<blockquote><a href="carrito.php"><input type="submit" name="ver" value="Ver carrito"></a> </blockquote>

<?php //mensajes de error
    if(isset($_GET["e"]) ) {
      if($_GET["e"]==1){
?>
        <div >
          <h3 ><?php echo "El producto <strong> ".$_GET["p"]."</strong> solo tiene <strong>".$_GET["c"]."</strong> unidades"?></h3>
        </div>
  <?php
      }
    }

    if(isset($_GET["t"]) ) {
       echo "<h3 >Se han agregado al carrito: ".$_GET["c"]." ".$_GET["p"]." </h3>";
    }
  ?>
<br> 
</body>
</html>
