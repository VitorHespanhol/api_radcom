<?php
	require_once('App/Repository/MySql.php');
	class OrganizacaoRepository
	{
		public function getByField($fieldName, $value)
		{
			$query = 'SELECT * FROM `tb_organizacoes` WHERE '.$fieldName. ' = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($value));
			if($sql->rowCount() >= 1)
			{
				$organizacao = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $organizacao;
			}
			else
			{
				return false;
			}
		}

		public function selectById($id)
		{
			$query ='SELECT * FROM `tb_organizacoes` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($id));
			if($sql->rowCount() == 1)
			{
				$organizacao = $sql->fetch(PDO::FETCH_ASSOC);
				return $organizacao;
			}
			else
			{
				return false;
			}

		}

		public function selectAll()
		{
			$query = 'SELECT * FROM `tb_organizacoes`';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function selectField($fieldName, $id)
		{
			$query = 'SELECT '.$fieldName.' FROM `tb_organizacoes` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($id));
			if($sql->rowCount() == 1)
			{
				$organizacao = $sql->fetch(PDO::FETCH_ASSOC);
				return $organizacao;
			}
			else
			{
				return false;
			}
		}

		public function updateField($id, $field, $value)
		{
			$query = 'UPDATE `tb_organizacoes` SET '.$field.' = ? WHERE id = ?';
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

		public function insert(Array $organizacao)
		{
			$query = 'INSERT INTO `tb_organizacoes` (nome, endereco, numeroEndereco, bairro, idCidade, idEstado) VALUES(?, ?, ?, ?, ?, ?)';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($organizacao['nome'], $organizacao['endereco'], $organizacao['numeroEndereco'], $organizacao['bairro'], $organizacao['idCidade'], $organizacao['idEstado'])))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function update($id, Array $organizacao)
		{
			$query = 'UPDATE `tb_organizacoes` SET nome = ?, endereco = ?, numeroEndereco = ?, bairro = ?, idCidade = ?, idEstado = ? WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($organizacao['nome'], $organizacao['endereco'], $organizacao['numeroEndereco'], $organizacao['bairro'], $organizacao['idCidade'], $organizacao['idEstado'], $id)))
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
			$query = 'DELETE FROM `tb_organizacoes` WHERE id = ?';
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