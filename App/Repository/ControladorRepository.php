<?php
	require_once('App/Repository/MySql.php');
	class ControladorRepository
	{
		public function getByField($fieldName, $value)
		{
			$query = 'SELECT * FROM `tb_controladores` WHERE '.$fieldName.' = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($value));
			if($sql->rowCount() >= 1)
			{
				$controlador = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $controlador;
			}
			else
			{
				return false;
			}
		}
		
		public function selectById($id)
		{
			$query = 'SELECT * FROM `tb_controladores` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($id));
			if($sql->rowCount() == 1)
			{
				$controlador = $sql->fetch(PDO::FETCH_ASSOC);
				return $controlador;
			}
			else
			{
				return false;
			}
		}

		public function selectAllWithRelationship($idExterno)
		{
			$query = 'SELECT * FROM `tb_controladores` WHERE idControladorExterno = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($idExterno));
			if($sql->rowCount() >= 1)
			{
				$controlador = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $controlador;
			}
			else
			{
				return false;
			}
		}

		public function selectByUser($idUser)
		{
			$query = 'SELECT C.* FROM `tb_controladores` AS C INNER JOIN `tb_usuarios` AS U ON U.`idOrganizacao` = C.`idOrganizacao` WHERE U.`id` = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($idUser));
			if($sql->rowCount() >= 1)
			{
				$controlador = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $controlador;
			}
			else
			{
				return false;
			}

		}

		public function selectField($fieldName, $id)
		{
			$query = 'SELECT '.$fieldName.' FROM `tb_controladores` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($id));
			if($sql->rowCount() == 1)
			{
				$controlador = $sql->fetch(PDO::FETCH_ASSOC);
				return $controlador;
			}
			else
			{
				return false;
			}
		}

		public function updateField($id, $field, $value)
		{
			$query = 'UPDATE `tb_controladores` SET '.$field.' = ? WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(array($value, $id)))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function selectAll()
		{
			$query = 'SELECT * FROM `tb_controladores`';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function insert(Array $controlador)
		{
			$query = 'INSERT INTO `tb_controladores` (mac, descricao, status, statusLink, idControladorExterno, idRadio, idOrganizacao, percentimetro, rotacao, agua, pressaoPonta, pressaoBase) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($controlador['mac'], $controlador['descricao'], $controlador['status'], $controlador['statusLink'], $controlador['idControladorExterno'], $controlador['idRadio'], $controlador['idOrganizacao'], $controlador['percentimetro'], $controlador['rotacao'], $controlador['agua'], $controlador['pressaoPonta'], $controlador['pressaoBase'])))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		private function buildUpdate($arrayDados, $arrayCondicao)
		{   
    
	       // Inicializa variáveis   
	       $sql = "";   
	       $valCampos = "";   
	       $valCondicao = "";   
	              
	       // Loop para montar a instrução com os campos e valores   
	       foreach($arrayDados as $chave => $valor):   
	          $valCampos .= $chave . '=?, ';   
	       endforeach;   
	              
	       // Loop para montar a condição WHERE   
	       foreach($arrayCondicao as $chave => $valor):   
	          $valCondicao .= $chave . '? AND ';   
	       endforeach;   
	              
	       // Retira vírgula do final da string   
	       $valCampos = (substr($valCampos, -2) == ', ') ? trim(substr($valCampos, 0, (strlen($valCampos) - 2))) : $valCampos ;    
	              
	       // Retira vírgula do final da string   
	       $valCondicao = (substr($valCondicao, -4) == 'AND ') ? trim(substr($valCondicao, 0, (strlen($valCondicao) - 4))) : $valCondicao ;    
	              
	        // Concatena todas as variáveis e finaliza a instrução   
	        $sql .= "UPDATE `tb_controladores` SET " . $valCampos . " WHERE " . $valCondicao;   
	              
	        // Retorna string com instrução SQL
	        return trim($sql);
    	}   



		public function update($id, Array $controlador)
		{
			$query = 'UPDATE `tb_controladores` SET descricao = ?, status = ?, statusLink = ?, idControladorExterno = ?, idRadio = ?, idOrganizacao = ?, percentimetro = ?, rotacao = ?, agua = ?, pressaoPonta = ?, pressaoBase = ? WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($controlador['descricao'], $controlador['status'], $controlador['statusLink'], $controlador['idControladorExterno'], $controlador['idRadio'], $controlador['idOrganizacao'], $controlador['percentimetro'], $controlador['rotacao'], $controlador['agua'], $controlador['pressaoPonta'], $controlador['pressaoBase'], $id)))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function updateFieldByRelationship($idControladorExterno, $field, $value)
		{
			$query = 'UPDATE `tb_controladores` SET `tb_controladores`.'.$field.' = ? WHERE `tb_controladores`.`idControladorExterno` = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($value, $idControladorExterno)))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function delete($id)
		{
			$query = 'DELETE FROM `tb_controladores` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($id)))
			{
				return true;
			}
			else
			{
				return false;
			}	
		}
	}
?>