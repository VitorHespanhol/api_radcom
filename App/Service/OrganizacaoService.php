<?php
	require_once('App/Repository/OrganizacaoRepository.php');
	require_once('App/Service/UsuarioService.php');
	
	class OrganizacaoService
	{
		private $repo;
		
		function __construct()
		{
			$this->repo = new OrganizacaoRepository();
		}

		public function selecionarTodos()
		{
			return $this->repo->selectAll();
		}

		public function selecionarUsuarios($id)
		{
			$userService = new UsuarioService();
			return $userService->selecionarPorOrganizacao($id);
		}

		public function selecionarPorId($id)
		{
			return $this->repo->selectById($id);
		}

		private function validateInput(Array $input)
		{
			if(!isset($input['nome']))
				return false;
			if(!isset($input['endereco']))
				return false;
			if(!isset($input['numeroEndereco']))
				return false;
			if(!isset($input['bairro']))
				return false;
			if(!isset($input['idCidade']))
				return false;
			if(!isset($input['idEstado']))
				return false;
			return true;
		}

		public function inserir(Array $organizacao)
		{
			if($this->validateInput($organizacao))
			{
				return $this->repo->insert($organizacao);
			}
			return false;
		}

		public function atualizar($id, Array $organizacao)
		{
			if($this->validateInput($organizacao))
			{
				return $this->repo->update($id, $organizacao);
			}
			return false;
		}

		public function deletar($id)
		{
			return $this->repo->delete($id);
		}
	}
?>