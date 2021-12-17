<HTML LANG="es">

<HEAD>
   <TITLE>Búsqueda de vivienda</TITLE>
   <LINK REL="stylesheet" TYPE="text/css" HREF="estilo.css">
   
<?PHP
   require ("ln.php");
?>   

</HEAD>

<BODY>

<H1>Búsqueda de vivienda</H1>

<?PHP
// Obtener valores introducidos en el formulario
   $buscar = $_REQUEST['buscar'];
   $consulta = $_REQUEST['consulta'];

// Conectar con el servidor de base de datos
   $conexion = mysqli_connect ("localhost", "root", "")
      or die ("No se puede conectar con el servidor");

// Seleccionar base de datos
   mysqli_select_db ($conexion, "lindavista")
      or die ("No se puede seleccionar la base de datos");

   $error = false;
   if (isset($buscar))
   {

   // Comprobar errores
   // Consulta
      if (trim($consulta) == "")
      {
         $errores["consulta"] = "Debe introducir una consulta";
      	 $error = true;
      }
      else if (!procesa_consulta ($consulta, $conexion, $sql))
      {
         $errores["consulta"] = "La consulta no es correcta";
      	 $error = true;
      }
      else
         $errores["consulta"] = "";
   
   }

// Si los datos son correctos, procesar formulario
   if (isset($buscar) && $error==false)
   {
      print("<P>Resultados de la búsqueda:</P>\n");

   // Enviar consulta
      $instruccion = $sql;
      $consulta = mysqli_query ($conexion, $instruccion)
         or die ("Fallo en la consulta");

   // Mostrar resultados de la consulta
      $nfilas = mysqli_num_rows ($consulta);
      if ($nfilas > 0)
      {
         print ("<TABLE WIDTH='650'>\n");
         print ("<TR>\n");
         print ("<TH WIDTH='100'>Tipo</TH>\n");
         print ("<TH WIDTH='100'>Zona</TH>\n");
         print ("<TH WIDTH='100'>Dormitorios</TH>\n");
         print ("<TH WIDTH='75'>Precio</TH>\n");
         print ("<TH WIDTH='75'>Tamaño</TH>\n");
         print ("<TH WIDTH='150'>Extras</TH>\n");
         print ("<TH WIDTH='50'>Foto</TH>\n");
         print ("</TR>\n");

         for ($i=0; $i<$nfilas; $i++)
         {
            $resultado = mysqli_fetch_array ($consulta);
            print ("<TR>\n");
            print ("<TD>" . $resultado['tipo'] . "</TD>\n");
            print ("<TD>" . $resultado['zona'] . "</TD>\n");
            print ("<TD>" . $resultado['ndormitorios'] . "</TD>\n");
            print ("<TD>" . $resultado['precio'] . "</TD>\n");
            print ("<TD>" . $resultado['tamano'] . "</TD>\n");
            print ("<TD>" . $resultado['extras'] . "</TD>\n");

            if ($resultado['foto'] != "")
               print ("<TD><A TARGET='_blank' HREF='fotos/" . $resultado['foto'] .
                      "'><IMG BORDER='0' SRC='fotos/ico-fichero.gif' ALT='Foto'></A></TD>\n");
            else
               print ("<TD>&nbsp;</TD>\n");

            print ("</TR>\n");
         }

         print ("</TABLE>\n");
      }
      else
         print ("No hay viviendas disponibles");

      print ("<P>[ <A HREF='busca_vivienda_ln.php'>Buscar otra vivienda</A> ]</P>\n");
   }
   else
   {
?>

<P>Introduzca la consulta:</P>

<FORM CLASS="borde" ACTION="busca_vivienda_ln.php" METHOD="POST">


<P><LABEL>Consulta:</LABEL>
<?PHP
   print("<INPUT TYPE='TEXT' NAME='consulta' SIZE='50' ");
   if (isset($buscar))
      print (" VALUE='$consulta'>\n");
   else
      print (">\n");
   if ($errores["consulta"] != "")
      print ("<BR><SPAN CLASS='error'>" . $errores["consulta"] . "</SPAN>");
?>
</P>

<P><INPUT TYPE="submit" NAME="buscar" VALUE="Buscar vivienda"></P>

</FORM>

<?PHP
   }

// Cerrar conexión
   mysqli_close ($conexion);
?>

</BODY>
</HTML>