<?php
	require_once('App/Repository/MySql.php');
	
	class CargoRepository
	{
		public function getByField($fieldName, $value)	
		{
			$query = 'SELECT * FROM `tb_cargos` WHERE '.$fieldName.' = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(Array($value));
			if($sql->rowCount() >= 1)
			{
				$cargo = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $cargo;
			}
			else
			{
				return false;
			}
		}

		public function selectById($id)
		{
			$query = 'SELECT * FROM `tb_cargos` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(Array($id));
			if($sql->rowCount == 1)
			{
				$cargo = $sql->fetch(PDO::FETCH_ASSOC);
				return $cargo;
			}
			else
			{
				return false;
			}
		}

		public function selectField($fieldName, $id)
		{
			$query = 'SELECT '.$fieldName.' FROM `tb_cargos` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($id));
			if($sql->rowCount() >= 1)
			{
				$cargo = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $cargo
			}
			else
			{
				return false;
			}
		}

		public function selectAll()
		{
			$query = 'SELECT * FROM `tb_cargos`';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function updateField($id, $field, $value)
		{
			$query = 'UPDATE `tb_cargos` SET '.$field.'= ? WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($value, $id)))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function update($id, Array $cargos)
		{
			$query = 'UPDATE `tb_cargos` SET descricao = ? WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($cargos['descricao'], $id)))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function insert(Array $cargos)
		{
			$query = 'INSERT INTO `tb_cargos` (descricao) VALUES (?)';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($cargos['descricao'])))
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
			$query = 'DELETE FROM `tb_cargos` WHERE id = ?';
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