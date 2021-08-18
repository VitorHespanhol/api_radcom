<?php
	require_once('App/Repository/CargoRepository.php');
	class CargoService
	{
		private $repo;
		function __construct()
		{
			$this->repo = new CargoRepository();
		}

		public function selecionarTodos()
		{
			return $this->repo->selectAll();
		}

		public function selecionarPorId($id)
		{
			return $this->selecionarPorId($id);
		}

		public function validateInput(Array $input)
		{
			if(!isset($input['descricao']))
				return false;
			return true;
		}

		public function inserir(Array $cargo)
		{
			if($this->validateInput($cargo))
			{
				return $this->repo->insert($cargo);
			}
			return false;
		}

		public function atualizar($id, Array $cargo)
		{
			if($this->validateInput($cargo))
			{
				return $this->repo->update($id, $cargo);
			}
			return false;
		}

		public function deletar($id)
		{
			return $this->repo->delete($id);
		}
	}
?>