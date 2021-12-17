<?php

/////////////////////////////////////////////////////////////////////////
// Módulo de reconocimiento de lenguaje natural mediante pattern matching
// Autor: Mariano González
// Fecha: noviembre 2007
/////////////////////////////////////////////////////////////////////////

// Función que procesa una frase de entrada.
// Devuelve la sentencia sql correspondiente a la frase o la cadena vacía
// si no se encuentra un patrón asociado a la frase.
   function procesa_consulta ($consulta, $conexion, $sql)
   {
      // print ("<P>Consulta original: $consulta</P>\n");
   	     
      // Normalizar la frase de entrada
         $consultaN = normaliza_entrada ($consulta, $conexion);

      // print ("<P>Consulta normalizada: ");
      // foreach ($consultaN as $pal)
      //    print ($pal . " ");
      // print ("</P>\n");

      // Buscar el patrón que corresponde a la frase de entrada
         $patron = busca_patron ($consultaN, $conexion);

      // print ("<P>Patrón: $patron</P>\n");

      // Si se ha encotrado un patrón válido, construir la sentencia sql
         if ($patron > 0)
         {
         	$sql = consulta_patron ($consultaN, $patron, $conexion);
            $resul = true;
         }
         else
         {
         	$sql = "";
         	$resul = false;
         }
         
      // print ("<P>Consulta sql: $sql</P>\n");

         return $resul;
   }
   
// Función que elimina las palabras de la frase de entrada que no están en el
// diccionario y convierte las palabras que sí están a una forma normalizada
   function normaliza_entrada ($entrada, $conexion)
   {
   	  $salida = array();

   // Pasar los valores de la frase de entrada a una tabla auxiliar
      $tablaaux = explode (" ", $entrada);
   // Pasar los valores que están en el diccionario a la tabla de salida
      $j=0;
      foreach ($tablaaux as $pal)
     	if (en_diccionario ($pal, &$palN, $conexion))
      	{
      	   $salida[$j] = $palN;
      	   $j++;
      	}      	
      return $salida;
   }
   
// Función que comprueba si una palabra está en el diccionario.
// En caso afirmativo devuelve la forma normalizada de la palabra.
// Los números se consideran palabras válidas
   function en_diccionario ($pal, $palN, $conexion)
   {
   	   $esta = false;
   	   
   	   if (is_numeric($pal))
   	   {
   	   	   $palN = $pal;
   	   	   $esta = true;
   	   }
   	   else
   	   {
   	      $instruccion = "select palabra, normalizada from ln_diccionario where palabra='$pal'";
          $consulta = mysql_query ($instruccion, $conexion)
             or die ("Fallo en la consulta");
          $nfilas = mysql_num_rows ($consulta);
          if ($nfilas > 0)
          {
             $esta = true;
             $resultado = mysql_fetch_array ($consulta);
             $palN = $resultado['normalizada']; 
          }
   	   }
  	   return $esta;
   }
   
// Función que busca el patrón que corresponde a una frase de entrada.
// Devuelve el número del patrón o 0 si no se encuentra
   function busca_patron ($cadena, $conexion)
   {
      $enc = false;

   // Obtener los patrones de la base de datos
 	  $instruccion = "select patron from ln_patrones";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $nfilas = mysql_num_rows ($consulta);

   // Buscar en todos los patrones
      $i = 0;
      while ($i<$nfilas && !$enc)
      {
         $resultado = mysql_fetch_array ($consulta);
         $patron = $resultado['patron']; 
         $patronT = explode (" ", $patron);

         $j=0;
         $enc = true;

         if (count($patronT) != count($cadena))
            $enc = false;
         else
         {
         // Comparar palabra a palabra el patrón y la frase de entrada
            while ($j<count($cadena) && $enc)
            {
               $cad1 = $patronT[$j];
               $cad2 = $cadena[$j];
              	  if (!coincide($cad1, $cad2, $conexion))
            	     $enc = false;
         	   $j++;
            }
         }

         $i++;
      }
   	
   	  if ($enc)
   	     return $i;
   	  else
   	     return 0;
   }
   
// Función que comprueba si la palabra del patrón y la palabra de la cadena
// que ocupan una misma posición en la frase coinciden   
   function coincide ($pal1, $pal2, $conexion)
   {
   // Las palabras del patrón y la cadena coinciden si:
   // a) Son una misma palabra
   	  if (!strcmp($pal1,$pal2))
   	     $c = true;

   // b) La palabra del patrón es 'numero' y la de la cadena un número
   	  else if (!strcmp($pal1,"numero") && is_numeric($pal2))
   	     $c = true;
   	  
   // c) La palabra del patrón es el nombre de un campo de tipo enum o set
   //    y la palabra de la cadena es un posible valor del campo
   	  else
   	  {
         $tipos = valores_tipo_enumerado ($conexion, "viviendas", "tipo");
         $zonas = valores_tipo_enumerado ($conexion, "viviendas", "zona");
         $extras = valores_tipo_conjunto ($conexion, "viviendas", "extras");
         $pal2 = "'" . $pal2 . "'";
       
         if ( (!strcmp($pal1,"tipo") && in_array($pal2, $tipos)) ||
              (!strcmp($pal1,"zona") && in_array($pal2, $zonas)) ||
              (!strcmp($pal1,"extras") && in_array($pal2, $extras)) )
            $c = true;
     	 else
   	        $c = false;
   	  } 
   	  return $c;
   }
   
// Función que construye la consulta sql asociada a un patrón, reemplazando
// las partes variables del patrón por los valores correspondientes de la
// frase de entrada
   function consulta_patron ($consultaN, $id, $conexion)
   {
   // Obtener la sentencia sql de la base de datos
   	  $instruccion = "select consultasql, patron from ln_patrones where id=$id";
      $consulta = mysql_query ($instruccion, $conexion)
         or die ("Fallo en la consulta");
      $resultado = mysql_fetch_array ($consulta);
      $consultasql = $resultado['consultasql']; 
      $patron = $resultado['patron']; 
      $patronT = explode (" ", $patron);

   // Reemplazar las partes variables de la sentencia sql por los valores
   // correspondientes de la frase de entrada
      $j=1;
      for ($i=0; $i<count($patronT); $i++)
         if ($patronT[$i] != $consultaN[$i])
         {
      	    $consultasql = str_replace("%$j", $consultaN[$i], $consultasql);
      	    $j++;
         }
   	  return $consultasql;
   }
   
// Función que devuelve los valores de un campo de tipo enum
   function valores_tipo_enumerado ($conexion, $tabla, $columna)
   {
      $instruccion = "SHOW columns FROM $tabla LIKE '$columna'";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta);

      $lis = strstr ($row[1], "(");
      $lis = ltrim ($lis, "(");
      $lis = rtrim ($lis, ")");
      $lista = explode (",", $lis);
      return ($lista);
   }

// Función que devuelve los valores de un campo de tipo set
   function valores_tipo_conjunto ($conexion, $tabla, $columna)
   {
      $instruccion = "SHOW columns FROM $tabla LIKE '$columna'";
      $consulta = mysql_query ($instruccion, $conexion);
      $row = mysql_fetch_array ($consulta);

      $lis = strstr ($row[1], "(");
      $lis = ltrim ($lis, "(");
      $lis = rtrim ($lis, ")");
      $lista = explode (",", $lis);
      return ($lista);
   }
?>
