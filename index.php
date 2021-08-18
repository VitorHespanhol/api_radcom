<?php
	include('config.php');
	require_once('App/Controller/UsuarioController.php');
	require_once('App/Controller/ControladorController.php');
	require_once('App/Controller/OrganizacaoController.php');

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE,PATCH");
	header("Access-Control-Max-Age: 3600");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri = explode('/', $uri);

	$requestMethod = $_SERVER["REQUEST_METHOD"];
	
	switch ($uri[2]) {
		case 'usuarios':
			$userId = null;
			$login = null;
			$senha = null;
			if(isset($uri[3]))
			{
				if(is_numeric($uri[3]))
				{
					if(!isset($uri[4]))
					{
						$userId = $uri[3];
					}
					else
					{
						$login = $uri[3];
						$senha = $uri[4];
					}
				}
				else
				{
					if(isset($uri[4]))
					{
						$login = $uri[3];
						$senha = $uri[4];
					}
					else
					{
						header("HTTP/1.1 422 Unprocessable Entity");
						die(json_encode(['error' => 'Invalid Request!']));
					}
				}
			}
			$controller = new UsuarioController($requestMethod, $userId, $login, $senha);
			$controller->processRequest();
			break;
		case 'controladores':
			$userId = null;
			$controladorId = null;
			$field = null;
			$controladorExternoId = null;
			if (isset($uri[3]))
			{
				if(is_numeric($uri[3]))
				{
					$controladorId = $uri[3];
				}
				else
				{
					if(isset($uri[4]) && is_numeric($uri[4]))
					{
						switch ($uri[3]) 
						{
							case 'usuarios':
								$userId = $uri[4];
								break;

							case 'centralizadores':
								$controladorExternoId = $uri[4];
								break;

							case 'status':
								$field = $uri[3];
								$controladorId = $uri[4];
								break;

							case 'poweron':
								$field = $uri[3];
								$controladorId = $uri[4];
								break;

							case 'poweroff':
								$field = $uri[3];
								$controladorId = $uri[4];
								break;

							case 'link':
								$field = $uri[3];
								$controladorId = $uri[4];
							break;

							case 'linkup':
								$field = $uri[3];
								$controladorId = $uri[4];
							break;

							case 'linkdown':
								$field = $uri[3];
								$controladorId = $uri[4];
							break;

							case 'radio':
								$field = $uri[3];
								$controladorId = $uri[4];
							break;

							case 'descricao':
								$field = $uri[3];
								$controladorId = $uri[4];
							break;

							default:
								header("HTTP/1.1 422 Unprocessable Entity");
								die(json_encode(['error' => 'Invalid Request!']));
								break;
						}
					}
					else
					{
						header("HTTP/1.1 422 Unprocessable Entity");
						die(json_encode(['error' => 'Invalid Request!']));
					}
				}
			}

			$controller = new ControladorController($requestMethod, $userId, $controladorId, $field, $controladorExternoId);
			$controller->processRequest();
			break;

		case 'organizacoes':
			$organizacaoId = null;
			$relationship = null;
			if(isset($uri[3]))
			{
				if(is_numeric($uri[3]))
				{
					$organizacaoId = $uri[3];
				}
				else
				{
					if($uri[3] == 'usuarios')
					{
						if(isset($uri[4]) && is_numeric($uri[4]))
						{
							$relationship = $uri[3];
							$organizacaoId = $uri[4];
						}
						else
						{
							header("HTTP/1.1 422 Unprocessable Entity");
							die(json_encode(['error' => 'Invalid Request!']));		
						}
					}
					else
					{
						header("HTTP/1.1 422 Unprocessable Entity");
						die(json_encode(['error' => 'Invalid Request!']));
					}
				}
			}
			$controller = new OrganizacaoController($requestMethod, $organizacaoId, $relationship);
			$controller->processRequest();
			break;

		default:
			header("HTTP/1.1 404 Not Found");
			echo json_encode(['error' => 'Route Not Found!']);
    		exit();
			break;
	}
?>