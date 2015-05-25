		<?php
			include("php/partes/header.php");
			include("php/funciones.php");
			
		?>
		<ul>
			<a href="noticias.php?quemostrar=todas&num=1">Todas</a><br>
			<a href="noticias.php?quemostrar=lsa&num=1">Sobre Comunidad LSA</a><br>
			<a href="noticias.php?quemostrar=gen&num=1">Interés General</a><br>
			</ul>	
			<?php
			function dibujar_enlaces($x, $y, $z){
				if($x==$y){
					echo $x.' - ';
				}else{
					echo '<a href="noticias.php?quemostrar='.$z.'&num='.$x.'">'.$x.' - </a> ';
				}
			}
            	$conexion = conectar();
		    	//cuantos resultados voy a mostrar
		   		$registros=2;
		    	//controlo la categoria de las noticias a mostrar
		    	if(isset($_GET['quemostrar'])){
            		$quemostrar=$_GET['quemostrar'];
            	}else{
            		$quemostrar="todas";
            	}
            	//controlo el numero de pagina de los resultados obtenidos
            	if(isset($_GET['num'])){
					$pagina=$_GET['num'];
				}else{
					$pagina=1;
				}
				//en donde inicio
				$inicio=($pagina-1)*$registros;
            	//seleccionar contenido de la base de datos para mostrar
            	switch ($quemostrar) {
            		case 'lsa': $consulta=mysqli_query($conexion, "SELECT id FROM noticias WHERE categoria LIKE 'Sobre Comunidad LSA'");
            	            	$mostrar=mysqli_query($conexion,"SELECT fecha,titulo,contenido,categoria FROM noticias WHERE categoria LIKE 'Sobre Comunidad LSA' ORDER BY fecha DESC LIMIT $inicio,$registros" );
            					break;
            		case 'gen': $consulta=mysqli_query($conexion, "SELECT id FROM noticias WHERE categoria LIKE 'Interés General'");
            					$mostrar=mysqli_query($conexion, "SELECT fecha,titulo,contenido,categoria FROM noticias WHERE categoria LIKE 'Interés General' ORDER BY fecha DESC LIMIT $inicio,$registros");
            					break;
            		default: $consulta=mysqli_query($conexion, "SELECT id FROM noticias");
            			 	$mostrar=mysqli_query($conexion, "SELECT fecha,titulo,contenido,categoria FROM noticias ORDER BY fecha DESC LIMIT $inicio,$registros");
            			 	break;
            	}
            	//cuantos registros devolvio la consulta a la base de datos
            	$num_registros=mysqli_num_rows($consulta);
            	//numero de paginas para el indice
           		$num_paginas=ceil($num_registros/$registros);
            	//mostrar
            	while($filas=mysqli_fetch_array($mostrar)){
				    if($filas['categoria']=="Sobre Comunidad LSA"){
                        echo '<br>'.$filas['titulo'].'<pre><i>'.$filas['fecha'].'  -  <a href="noticias.php?quemostrar=lsa&num=1">'.$filas['categoria'].'</a></i></pre><br>'.$filas['contenido'];
                    }else if($filas['categoria']=="Interés General"){
                        echo '<br>'.$filas['titulo'].'<pre><i>'.$filas['fecha'].'  -  <a href="noticias.php?quemostrar=gennum=1">'.$filas['categoria'].'</a></i></pre><br>'.$filas['contenido'];
                    }
			    }
			    //cantidad de paginas que muestro en el paginador
			    $cant=5;
			    //si no tengo tantas paginas para mostrar como quisiera
			    if($cant > $num_paginas){
			    	$cant=$num_paginas;
			    }
			    echo $cant."-". $num_paginas;
			    //dibujo los enlaces a mas resultados solo si tengo mas de una pagina de resultados
                if($num_paginas>1){
                	echo '<br><br><br>Mostrando resultados página : <br>';
                	//si la pagina en la que estoy actualmente no es la primera
                	if($pagina>1){
                		echo ' <a href="noticias.php?quemostrar='.$quemostrar.'&num='.($pagina-1).'"> <<< Anterior - </a> ';
                	}
                	//si lel numero de pagina en la que estoy es mayor que el numero de paginas que quiero mostrar
                	if($pagina - $cant>=1){
                		echo " ... -";
                		//escribir todo con la funcion
                		if($pagina + $cant < $num_paginas){
                			//paginas "intermedias"
                			for($i=$pagina;$i<= $pagina + $cant; $i++){
                				dibujar_enlaces($i, $pagina, $quemostrar);
                			}
                			echo " ... -";
                		}else{
                			//ultimas paginas
                			for($i=$num_paginas-$cant+1;$i<=$num_paginas;$i++){
                				dibujar_enlaces($i, $pagina, $quemostrar);
                			}
                		}
                	}else{
                		//primeras paginas
                		for($i=1;$i<=$cant;$i++){
                			dibujar_enlaces($i,$pagina,$quemostrar);
                		}
                		if($pagina  < $num_paginas && $cant!=$num_paginas){
                			echo " ... -";
                		}
                	}
			    	if($pagina < $num_paginas){
			    		echo ' <a href="noticias.php?quemostrar='.$quemostrar.'&num='.($pagina+1).'"> Siguiente >>></a> ';
			    	}
			    }
			?>
<!-- Este es el pie de pagina -->
		<?php
			include("php/partes/footer.php");
		?>