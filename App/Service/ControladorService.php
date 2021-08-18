<?php
	require_once('App/Repository/ControladorRepository.php');
	require_once('App/Client/ControladorClient.php');

	class ControladorService
	{
		private $repo;

		function __construct()
		{
			$this->repo = new ControladorRepository;
		}

		public function selecionarTodos()
		{
			return $this->repo->selectAll();
		}

		public function selecionarPorCentralizador($idCentralizador)
		{
			return $this->repo->selectAllWithRelationship($idCentralizador);
		}

		public function selecionarPorId($id)
		{
			return $this->repo->selectById($id);
		}

		public function selecionarPorUsuario($idUsuario)
		{
			return $this->repo->selectByUser($idUsuario);
		}

		private function validateInsert(Array $input)
		{
			if(!isset($input['mac']))
				return false;
			if(!isset($input['descricao']))
				return false;
			if(!isset($input['status']))
				return false;
			if(!isset($input['statusLink']))
				return false;
			if(!isset($input['idControladorExterno']))
				return false;
			if(!isset($input['idRadio']))
				return false;
			if(!isset($input['idOrganizacao']))
				return false;
			if(!isset($input['percentimetro']))
				return false;
			if(!isset($input['rotacao']))
				return false;
			if(!isset($input['agua']))
				return false;
			if(!isset($input['pressaoPonta']))
				return false;
			if(!isset($input['pressaoBase']))
				return false;
			return true;
		}

		public function inserir(Array $controlador)
		{
			if($this->validateInsert($controlador))
			{
				return $this->repo->insert($controlador);
			}
			return false;
		}

		public function atualizar($id, Array $controlador)
		{
			if($this->validateUpdate($controlador))
			{
				return $this->repo->update($id, $controlador);
			}
			return false;
		}
		
		private function validateUpdate(Array $input)
		{
			if(!isset($input['descricao']))
				return false;
			if(!isset($input['status']))
				return false;
			if(!isset($input['statusLink']))
				return false;
			if(!isset($input['idControladorExterno']))
				return false;
			if(!isset($input['idRadio']))
				return false;
			if(!isset($input['idOrganizacao']))
				return false;
			if(!isset($input['percentimetro']))
				return false;
			if(!isset($input['rotacao']))
				return false;
			if(!isset($input['agua']))
				return false;
			if(!isset($input['pressaoPonta']))
				return false;
			if(!isset($input['pressaoBase']))
				return false;
			return true;
		}
		
		public function getStatus($id)
		{
			return $this->repo->selectField('status', $id);
		}

		private function switchPower($id)
		{
			$statusPower = $this->repo->selectField('status', $id);
			if(!$statusPower)
			{
				return false;
			}
			if($statusPower['status'] = 1)
				return $this->turnOn($id);
			return $this->turnOff($id);
		}

		public function turnOn($id)
		{
			//SELECIONAR POR ID
			//SE SELECIONAR RETORNAR CONTINUAR, SE NÃO RETORNAR FALSE,

			if($this->repo->updateField($id, 'dateLastOn', date('Y-m-d H:i:s')))
				return $this->repo->updateField($id, 'status', 1);
			else
				return false;
		}

		public function turnOff($id)
		{
			if($this->repo->updateField($id, 'dateLastOff', date('Y-m-d H:i:s')))
				return $this->repo->updateField($id, 'status', 0);
			else
				return false;
		}

		public function getStatusLink($id)
		{
			return $this->repo->selectField('statusLink', $id);
		}

		public function setLinkOn($id)
		{
			return $this->repo->updateField($id, 'statusLink', 1);
		}

		public function setLinkOff($id)
		{
			return $this->repo->updateField($id, 'statusLink', 0);
		}

		public function changeDescription($id, $description)
		{
			return $this->repo->updateField($id, 'descricao', $description);
		}

		public function setIdRadio($id, $idRadio)
		{
			$controladorMaster = $this->repo->selectField('idControladorExterno', $id);
			if(!$controladorMaster)
			{
				return false;
			}
			$controladores = $this->repo->selectAllWithRelationship($controladorMaster['idControladorExterno']);
			if(!$controladores)
			{
				return false;
			}
			foreach ($controladores as $key => $value) 
			{
				if($key['idRadio'] == $idRadio)
				{
					return false;
				}
			}
			return $this->repo->updateField($id, 'idRadio', $idRadio);
		}

		public function turnAllOn($idControladorExterno)
		{
			return $this->repo->updateFieldByRelationship($idControladorExterno, 'status', 1);
		}

		public function turnAllOff($idControladorExterno)
		{
			return $this->repo->updateFieldByRelationship($idControladorExterno, 'status', 0);
		}

		public function deletar($id)
		{
			return $this->repo->delete($id);
		}
	}
?>