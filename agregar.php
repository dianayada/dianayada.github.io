<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Interfaz de pedidos al almacén</title>
</head>

<body>
<?php 
  session_start();
  $prod = array();//variables donde se guardan los datos del carrito
  $cant= array();
  $prec = array();
  $nombreCarrito= date("Y")."-".date("m")."-".date("d")."_".$_SESSION['nombre']; 
  $precProdActual;

  //se va a revisar que el numero ingresado sea menor que la cantidad de productos que hay en stock
  for ($i=0; $i < count($_SESSION["prod"]); $i++) {
    //se busca el indice donde se encuentra el producto
    if($_SESSION["prod"][$i]==$_POST['producto']){
      //si se pide una cantidad de producto mayor a la que tenemos, entonces se regresa a "pedios" mostrando un error.
      if ($_SESSION["cant"][$i]<floatval($_POST["cantidad"])||floatval($_POST["cantidad"])<1) {
        header("Location: acceder.php?e=1&p=".$_SESSION["prod"][$i]."&c=".$_SESSION["cant"][$i]."");
      }else{
        //Si todo el correcto entonces disminuimos la cantidad del stock
        $_SESSION["cant"][$i]-=floatval($_POST["cantidad"]);
          $pr=$_SESSION["prec"][$i];
          $pr=$pr*floatval($_POST["cantidad"]);
      //  $carrito = fopen("carrito.txt", "r");
        $file = fopen("almacen.txt", "w");
        
        $texto=getTextoAlmacen();
        if (flock($file,LOCK_EX)){
          fwrite($file, $texto);
          flock($file,LOCK_UN);
        }
        fclose($file);

        checkCarrito($pr);
        //echo $texto." texto";
       // header("Location: localhost/Practica3/index.php");
          header("Location: acceder.php?t=1&p=".$_POST["producto"]."&c=".$_POST["cantidad"]."");
              
        //agregar al carrito
      }
    }
  }


  function agregarCarrito($pr){
    global $prod;
    global $cant;
    global $prec, $nombreCarrito;
    $bool=false;
    $textoCarrito="";
    echo count($prod)."CUENTA   \n";
    $carrito = fopen($nombreCarrito.".txt", "w");
    for ($j=0; $j < count($prod); $j++) {
        echo $_POST["producto"]."====".$prod[$j];
      if($_POST["producto"]==$prod[$j]){
        $cant[$j]=$cant[$j]+$_POST["cantidad"];
        $textoCarrito=$textoCarrito.$prod[$j]."\t".$cant[$j]."\t".$prec[$j]."\n";  
        $bool=true;     
      }else{
        $textoCarrito=$textoCarrito.$prod[$j]."\t".$cant[$j]."\t".$prec[$j]."\n";   
      }
    }
    if(!$bool){
      if($textoCarrito==""){}
      $textoCarrito=$textoCarrito.$_POST["producto"]."\t".$_POST["cantidad"]."\t".$pr."\n";
    }
    if (flock($carrito,LOCK_EX)){
      fwrite($carrito, $textoCarrito);
      flock($file,LOCK_UN);
    }
    fclose($carrito);
    return 1;
  }

  function checkCarrito($pr){
    global $prod;
    global $cant;
    global $prec,$nombreCarrito;
    $path=$nombreCarrito.".txt";
    echo "path".$path;

    $carrito=fopen($path, "r");
    echo "carrito".$carrito;
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
    return agregarCarrito($pr);
  }

  function getTextoAlmacen(){
    $text="";
    for ($j=0; $j < count($_SESSION["prod"]); $j++) {
      $text= $text.$_SESSION["prod"][$j]."\t".$_SESSION["cant"][$j]."\t".$_SESSION["prec"][$j]."\n";
    }
    return $text;
  }

    ?>



</body>
</html>
