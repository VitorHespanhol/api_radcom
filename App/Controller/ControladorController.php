<?php
require_once('App/Service/ControladorService.php');

class ControladorController
{
	private $method;
	private $userId;
	private $controladorId;
	private $controladorExternoId;
	private $service;
	private $relationship;

	function __construct($method, $userId, $controladorId, $field, $controladorExternoId)
	{
		$this->method = $method;
		$this->userId = $userId;
		$this->controladorExternoId = $controladorExternoId;
		$this->controladorId = $controladorId;
		$this->field = $field;
		$this->service = new ControladorService;
	}

	public function processRequest()
	{
		switch ($this->method) 
		{
			case 'GET':
				if($this->controladorId)
				{
					if($this->field == 'status')
					{
						$response = $this->getStatus($this->controladorId);
					}
					elseif($this->field == 'link')
					{
						$response = $this->getStatusLink($this->controladorId);
					}
					elseif(!isset($this->field))
					{
						$response = $this->selectById($this->controladorId);
					}
					else
					{
						$response = $this->unprocessableEntityResponse();
					}
				}
				elseif($this->userId)
				{
					$response = $this->selectByUser($this->userId);
				}
				elseif($this->controladorExternoId)
				{
					$response = $this->selectByCentralizer($this->controladorExternoId);
				}
				else
				{
					$response = $this->selectAllContorllers();
				}
				break;

			case 'POST':
				$response = $this->inserir();				
				break;

			case 'PATCH':
				if($this->controladorId)
				{
					if($this->field == 'status')
					{
						$response = $this->switchPower($this->controladorId);
					}
					elseif($this->field == 'linkup')
					{
						$response = $this->setLinkOn($this->controladorId);
					}
					elseif($this->field == 'linkdown')
					{
						$response = $this->setLinkOff($this->controladorId);
					}
					elseif($this->field == 'radio')
					{
						$response = $this->setIdRadio($this->controladorId);
					}
					elseif($this->field == 'descricao')
					{
						$response = $this->changeDescription($this->controladorId);
					}
					else
					{
						$response = $this->notFoundResponse();
					}
				}
				else
				{
					$response = $this->unprocessableEntityResponse();
				}
			case 'PUT':
				if($this->controladorId)
				{
					$response = $this->updateController($this->controladorId);
				}
				else
				{
					$response = $this->unprocessableEntityResponse();
				}
				break;

			case 'DELETE':
				if($this->controladorId)
				{
					$response = $this->delete($this->controladorId);
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

	private function updateController($id)
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

	private function selectAllContorllers()
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

	private function selectByCentralizer($idCentralizer)
	{
		$result = $this->service->selecionarPorCentralizador($idCentralizer);
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

	private function selectByUser($id)
	{		
		$result = $this->service->selecionarPorUsuario($id);
		if(!$result)
		{
			return $this->notFoundResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode($result);
		return $response;
	}

	private function getStatus($id)
	{
		$result = $this->service->getStatus($id);
		if(!$result)
		{
			return $this->notFoundResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode($result);
		return $response;
	}

	private function switchPower($id) 
	{
		$result = $this->service->selecionarPorId($id);
		if(!$result)
		{
			return $this->notFoundResponse();
		}
		$result = $this->service->switchPower($id);
		if(!$result)
		{
			return $this->unprocessableEntityResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
	}

	private function getStatusLink($id)
	{
		$result = $this->service->getStatusLink($id);
		if(!$result)
		{
			return $this->notFoundResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode($result);
		return $response;
	}

	private function setLinkOn($id)
	{
		$result = $this->service->selecionarPorId($id);
		if(!$result)
		{
			return $this->notFoundResponse();
		}
		$result = $this->service->setLinkOn($id);
		if(!$result)
		{
			return $this->unprocessableEntityResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
	}

	private function setLinkOff($id)
	{
		$result = $this->service->selecionarPorId($id);
		if(!$result)
		{
			return $this->notFoundResponse();
		}
		$result = $this->service->setLinkOff($id);
		if(!$result)
		{
			return $this->unprocessableEntityResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
	}

	private function changeDescription($id)
	{
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if(!isset($input['descricao']))
		{
			return $this->unprocessableEntityResponse();
		}

		$description = $input['descricao'];
		$result = $this->service->selecionarPorId($id);
		if(!$result)
		{
			return $this->notFoundResponse();
		}
		$result = $this->service->changeDescription($id, $description);
		if(!$result)
		{
			return $this->unprocessableEntityResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
	}

	private function setIdRadio($id)
	{
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if(!isset($input['idRadio']))
		{
			return $this->unprocessableEntityResponse();
		}
		$idRadio = $input['idRadio'];
		$result = $this->service->setIdRadio($id, $idRadio);
		if(!$result)
		{
			return $this->unprocessableEntityResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
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