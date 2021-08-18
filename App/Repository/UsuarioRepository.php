<?php
require_once('App/Repository/MySql.php');
	class UsuarioRepository
	{

		public function getByField($fieldName, $value)
		{
			$query = 'SELECT * FROM `tb_usuarios` WHERE '.$fieldName.' = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($value));
			if($sql->rowCount() >= 1)
			{
				$user = $sql->fetchAll(PDO::FETCH_ASSOC);
				return $user;
			}
			else
			{
				return false;
			}
		}

		public function selectById($id)
		{
			$query = 'SELECT * FROM `tb_usuarios` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute(array($id));
			if($sql->rowCount() == 1)
			{
				$user = $sql->fetch(PDO::FETCH_ASSOC);
				return $user;
			}
			else
			{
				return false;
			}
		}

		public function updateField($id, $field, $value)
		{
			$query = 'UPDATE `tb_usuarios` SET '.$field.' = ? WHERE id = ?';
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
			$query = 'SELECT * FROM `tb_usuarios`';
			$sql = MySql::conectar()->prepare($query);
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}

		public function insert(Array $user)
		{
			$query = 'INSERT INTO `tb_usuarios` (login, senha, dataInsert, nome, sobrenome, idOrganizacao, idCargo) VALUES (?, ?, ?, ?, ?, ?, ?)';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($user['login'], $user['senha'], $user['dataInsert'], $user['nome'], $user['sobrenome'], $user['idOrganizacao'], $user['idCargo'])))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function update($id, Array $user)
		{
			$query = 'UPDATE `tb_usuarios` SET login = ?, senha = ?, nome = ?, sobrenome = ?, idOrganizacao = ?, idCargo = ? WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(Array($user['login'], $user['senha'], $user['nome'], $user['sobrenome'], $user['idOrganizacao'], $user['idCargo'], $id)))
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
			$query = 'DELETE FROM `tb_usuarios` WHERE id = ?';
			$sql = MySql::conectar()->prepare($query);
			if($sql->execute(array($id)))
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