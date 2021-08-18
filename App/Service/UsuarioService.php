<?php
	require_once('App/Repository/UsuarioRepository.php');
	class UsuarioService
	{
		private $repo;
		function __construct()
		{
			$this->repo = new UsuarioRepository;
		}

		public function selecionarPorOrganizacao($idOrganizacao)
		{
			return $this->repo->getByField('idOrganizacao', $idOrganizacao);
		}

		public function autenticate($user, $password)
		{
			$dbUser = $this->repo->getByField('login', $user);
			if(!$dbUser)
			{
				return false;
			}
			if($dbUser[0]['senha'] == $password)
			{
				$this->repo->updateField($dbUser[0]['id'], "dataUltimoLogin", date('Y-m-d H:i:s'));
				return $dbUser;
			}
			else
			{
				return false;
			}
		}

		public function selecionarTodos()
		{
			return $this->repo->selectAll();
		}

		public function selecionarPorId($id)
		{
			return $this->repo->selectById($id);
		}

		public function atualizar($id, Array $user)
		{
			if($this->validateUpdate($user))
			{
				return $this->repo->update($id, $user);
			}
			return false;
		}

		public function inserir(Array $user)
		{
			if($this->verifyInsert($user))
			{
				if($this->verifyUser($user['login']))
				{
					$hoje = date('Y-m-d H:i:s');
					$user['dataInsert'] = $hoje;
					return $this->repo->insert($user);
				}
				return false;
			}
			return false;
		}

		public function deletar($id)
		{
			return $this->repo->delete($id);
		}

		private function verifyUser($user)
		{
			$dbUser = $this->repo->getByField("login", $user);
			if(!$dbUser)
			{
				return true;
			}
			return false;
		}

		public function validateUpdate(Array $input)
		{
			if(!isset($input['login']))
				return false;
			if(!isset($input['senha']))
				return false;
			if(!isset($input['nome']))
				return false;
			if(!isset($input['sobrenome']))
				return false;
			if(!isset($input['idOrganizacao']))
				return false;
			if(!isset($input['idCargo']))
				return false;
			return true;
		}

		public function validateInsert(Array $input)
		{
			if(!isset($input['login']))
				return false;
			if(!isset($input['senha']))
				return false;
			if(!isset($input['nome']))
				return false;
			if(!isset($input['sobrenome']))
				return false;
			if(!isset($input['idOrganizacao']))
				return false;
			if(!isset($input['idCargo']))
				return false;
			return true;
		}
	}
?>