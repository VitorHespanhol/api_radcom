<?php
	require_once('App/Service/OrganizacaoService.php');
	
	class OrganizacaoController
	{
		private $method;
		private $organizacaoId;
		private $relationship;
		private $service;

		function __construct($method, $organizacaoId, $relationship)
		{
			$this->method = $method;
			$this->organizacaoId = $organizacaoId;
			$this->relationship = $relationship;
			$this->service = new OrganizacaoService();
		}

		public function processRequest()
		{
			switch ($this->method) 
			{
				case 'GET':
					if($this->organizacaoId)
					{
						if($this->relationship == 'usuarios')
						{
							$response = $this->selectUsers($this->organizacaoId);
						}
						elseif(!$this->relationship)
						{
							$response = $this->selectById($this->organizacaoId);
						}
						else
						{
							$response = $this->notFoundResponse();
						}
					}
					else
					{
						$response = $this->selectAll();
					}
					break;
				case 'POST':
					$response = $this->insert();
					break;
				case 'PATCH':
					$response = $this->unprocessableEntityResponse();
					break;
				case 'PUT':
					if($this->organizacaoId)
					{
						$response = $this->update($this->organizacaoId);
					}
					else
					{
						$response = $this->unprocessableEntityResponse();
					}
					break;
				case 'DELETE':
					if($this->organizacaoId)
					{
						$response = $this->delete($this->organizacaoId);
					}
					else
					{
						$response = $this->unprocessableEntityResponse();
					}
					break;
				
				default:
					$response = $this->notFoundResponse();
					break;
			}
			header($response['status_code_header']);
			if($response['body'])
			{
				echo $response['body'];
			}
		}

		private function selectAll()
		{
			$result = $this->service->selecionarTodos();
			if(!$result)
			{
				return $this->notFoundResponse();
			}
			$response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = json_encode($result);
			return $response;
		}

		private function selectById($id)
		{
			$result = $this->service->selecionarPorId($id);
			if(!$result)
			{
				return $this->notFoundResponse();
			}
			$response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = json_encode($result);
			return $response;
		}

		private function selectUsers($id)
		{
			$result = $this->service->selecionarUsuarios($id);
			if(!$result)
			{
				return $this->notFoundResponse();
			}
			$response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = json_encode($result);
			return $response;
		}

		private function insert()
		{
			$input = (array) json_decode(file_get_contents('php://input'), TRUE);
			$result = $this->service->inserir($input);
			if(!$result || !isset($input))
				return $this->unprocessableEntityResponse();
			$response['status_code_header'] = 'HTTP/1.1 201 Created';
			$response['body'] = null;

			return $response;
		}

		private function update($id)
		{
			$input = (array) json_decode(file_get_contents('php://input'), TRUE);
			if(!isset($input))
			{
				return $this->unprocessableEntityResponse();
			}
			$result = $this->service->selecionarPorId($id);
			if(!$result)
			{
				return $this->notFoundResponse();
			}
			$result = $this->service->atualizar($id, $input);
			if(!$result)
			{
				return $this->unprocessableEntityResponse();
			}
			$response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = null;

			return $response;
		}

		private function delete($id)
		{
			$result = $this->service->selecionarPorId($id);
			if(!$result)
			{
				return $this->notFoundResponse();
			}
			$result = $this->service->deletar($id);
			if(!$result)
			{
				return $this->unprocessableEntityResponse();
			}
			$response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = null;
		}
		
		private function unprocessableEntityResponse()
		{
			$response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
			$response['body'] = json_encode(['error' => 'Invalid input']);
			return $response;
		}	

		private function notFoundResponse()
	    {
	       	$response['status_code_header'] = 'HTTP/1.1 404 Not Found';
	       	$response['body'] = json_encode(['error' => 'Not Found']);
	       	return $response;
	    }
	}
?>