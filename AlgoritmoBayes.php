<?php
class AlgoritmoBayes{

			public $tipo_vehiculo;
			public $color;
			public $raza;
			public $genero;
			public $estado;
      function __construct($p1, $p2, $p3, $p4, $p5){
				$this->tipo_vehiculo = $p1;
				$this->color = $p2;
				$this->raza = $p3;
			  $this->genero = $p4;
			  $this->estado = $p5;
      }

      public function getPeso($violationType, $nInfractions){
							$conexion = new ConexionPGSQL();
							$countViolationType = $conexion->getCountViolationType($violationType);
							$PA1 = 1;
							$PA2 = 1;
							$PA3 = 1;
							$PA4 = 1;
							$PA5 = 1;


							//vehicletype
							if($this->tipo_vehiculo != ''){
								$A1 = $conexion->getCountParameterType("vehicletype", $this->tipo_vehiculo, $violationType);
								$PA1 = $A1 / $countViolationType;
							}
							//color
							if($this->color != ''){
								$A2 = $conexion->getCountParameterType("color", $this->color, $violationType);
								$PA2 = $A2 / $countViolationType;
							}
							//race
							if($this->raza != ''){
								$A3 = $conexion->getCountParameterType("race", $this->raza, $violationType);
								$PA3 = $A3 / $countViolationType;
							}
							//gender
							if($this->genero != ''){
								$A4 = $conexion->getCountParameterType("gender", $this->genero, $violationType);
								$PA4 = $A4 / $countViolationType;
							}
							//state
							if($this->estado != ''){
								$A5 = $conexion->getCountParameterType("state", $this->estado, $violationType);
								$PA5 = $A5 / $countViolationType;
							}

							$PViolation = ($countViolationType/$nInfractions)*$PA1*$PA2*$PA3*$PA4*$PA5;

							return $PViolation;
			}

			public function getMayorPeso($V1, $V2, $V3, $V4){
				 if($V1 >= $V2 && $V1 >= $V3 && $V1 >= $V4){
					 $res = "Citation";
				 }
				 if($V2 >= $V1 && $V2 >= $V3 && $V2 >= $V4){
					 $res = "Warning";
				 }
				 if($V3 >= $V1 && $V3 >= $V2 && $V3 >= $V4){
					 $res = "SERO";
				 }
				 if($V4 >= $V1 && $V4 >= $V2 && $V4 >= $V3){
					 $res = "ESERO";
				 }
				 return $res;
			}
}

?>
