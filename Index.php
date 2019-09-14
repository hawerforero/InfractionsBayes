<?php
  //Librerias
  include("Conexion.php");
  include("AlgoritmoBayes.php");
  //instanciación de la clase conexión a postgresql.
  $conexion = new ConexionPGSQL();
  $conexion->conectar();
  if($conexion->conectar()==true){
    echo "conexion exitosa";
  }else{
    echo "no se pudo conectar";
  }

  //Options Select
  $tipos_vehiculos = $conexion->getOptions("vehicletype");
	$colores = $conexion->getOptions("color");
	$razas = $conexion->getOptions("race");
	$generos = $conexion->getOptions("gender");
	$estados = $conexion->getOptions("state");

  if($_POST){
  		$tipo_vehiculo = $_POST['tipo_vehiculo'];
  		$color = $_POST['color'];
  		$raza = $_POST['raza'];
  		$genero = $_POST['genero'];
  		$estado = $_POST['estado'];

      $algoritmo = new AlgoritmoBayes($tipo_vehiculo, $color, $raza, $genero, $estado);
      $nInfractions = $conexion->getCountInfractions();

      $p1 = $algoritmo->getPeso("Citation", $nInfractions);
      $p2 = $algoritmo->getPeso("Warning", $nInfractions);
      $p3 = $algoritmo->getPeso("SERO", $nInfractions);
      $p4 = $algoritmo->getPeso("ESERO", $nInfractions);

      $sumaP = $p1 + $p2 + $p3 + $p4;

      $p1 = $p1/$sumaP;
      $p2 = $p2/$sumaP;
      $p3 = $p3/$sumaP;
      $p4 = $p4/$sumaP;

      $respuesta = $algoritmo->getMayorPeso($p1, $p2, $p3, $p4);


  }
?>

<html>
	<head>
		<title>Teorema de Bayes</title>
		<link rel="stylesheet" type="text/css" href="estilo.css">
	</head>
	<body>
		<section class="contenido">
			<article class="titulo">
			<h1>Calculo infracciones de transito<h1>
			</article>
			<form method="POST" action="Index.php">
				<p>
					<span>Tipo vehiculo:</span>
					<select name="tipo_vehiculo" id="tipo_vehiculo">
						<option value="">Seleccione una</option>
						<?php
							while ($fila=pg_fetch_array($tipos_vehiculos)) {
								echo '<option value="'.$fila['vehicletype'].'">'.$fila['vehicletype'].'</option>';
							}
						?>
					</select>
				</p>
				<p>
					<span>Color:</span>
					<select name="color" id="color">
						<option value="">Seleccione una</option>
						<?php
							while ($fila=pg_fetch_array($colores)) {
								echo '<option value="'.$fila['color'].'">'.$fila['color'].'</option>';
							}
						?>
					</select>
				</p>
				<p>
					<span>Raza:</span>
					<select name="raza" id="raza">
						<option value="">Seleccione una</option>
						<?php
							while ($fila=pg_fetch_array($razas)) {
								echo '<option value="'.$fila['race'].'">'.$fila['race'].'</option>';
							}
						?>
					</select>
				</p>
				<p>
					<span>Genero:</span>
					<select name="genero" id="genero">
						<option value="">Seleccione una</option>
						<?php
							while ($fila=pg_fetch_array($generos)) {
								echo '<option value="'.$fila['gender'].'">'.$fila['gender'].'</option>';
							}
						?>
					</select>
				</p>
				<p>
					<span>Estado:</span>
					<select name="estado" id="estado">
						<option value="">Seleccione una</option>
						<?php
							while ($fila=pg_fetch_array($estados)) {
								echo '<option value="'.$fila['state'].'">'.$fila['state'].'</option>';
							}
						?>
					</select>
				</p>
				<p>
					<input type="submit" value="Calcular" class="boton">
				</p>
			</form>
			<p>
				<span>Resultado:</span>
				<br>
				<section class="resultado">
				<?php
				if($_POST){
					echo '<span style="color: red; font-size: 27px;">!'.$respuesta.'</span>';
				  echo "<br><strong>Nivel confianza</strong>";
					echo "<br>Citation: ".number_format($p1,5,'.','');
					echo "<br>Warning: ".number_format($p2,5,'.','');
					echo "<br>SERO: ".number_format($p3,5,'.','');
					echo "<br>ENSERO: ".number_format($p4,5,'.','');

					echo "<br><br><strong>Atributos</strong>";
					echo "<br>Tipo vehiculo: <em>".$tipo_vehiculo."</em>";
					echo "<br>Color: <em>".$color."</em>";
					echo "<br>Raza: <em>".$raza."</em>";
					echo "<br>Genero: <em>".$genero."</em>";
					echo "<br>Estado: <em>".$estado."</em>";
				}
				?>
				</section>
			</p>
			<p>
				<small>&copy;2019 by @hawer_for</small>
			</p>
		</section>
	<body>
</html>
