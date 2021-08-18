<?php
require_once('App/Service/UsuarioService.php');
class UsuarioController
{
	private $method;
	private $userId;
	private $login;
	private $senha;
	private $service;

	
	public function __construct($method, $userId, $login, $senha)
	{
		$this->method = $method;
		$this->userId = $userId;
		$this->login = $login;
		$this->senha = $senha;
		$this->service = new UsuarioService;
	}

	public function processRequest()
	{
		switch($this->method)
		{
			case 'GET':
				if($this->userId)
				{
					$response = $this->selectById($this->userId);
				}
				elseif($this->login && $this->senha)
				{
					$response = $this->login($this->login, $this->senha);
				}
				else
				{
					$response = $this->selectAllUsers();
				}
				break;
			case 'POST':
				$response = $this->inserirUsuario();
				break;
			case 'PUT':
				if($this->userId)
				{
					$response = $this->atualizaUsuario($this->userId);	
				}
				else
				{
					$response = $this->unprocessableEntityResponse();
				}
				break;
			case 'DELETE':
				if($this->userId)
				{
					$response = $this->deleteUser($this->userId);	
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

	private function inserirUsuario()
	{
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		$result = $this->service->inserir($input);
		if(!$result || !isset($input))
			return $this->unprocessableEntityResponse();
		$response['status_code_header'] = 'HTTP/1.1 201 Created';
		$response['body'] = null;

		return $response;
	}

	private function atualizaUsuario($id)
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

	private function deleteUser($id)
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

	private function selectAllUsers()
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

	private function login($login, $senha)
	{
		$result = $this->service->autenticate($login, $senha);
		if(!$result)
		{
			return $this->notFoundResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode($result);
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