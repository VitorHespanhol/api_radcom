<?php

	class ControladorClient
	{
        /*
            $servidor = 'http://200.144.12.44:1880/testeLH';

            // Parametros da requisição
            $content = http_build_query(array(
                'MAC' => 'MAC ADDRESS',
                'percentimetro' => '0,00 A 1,00',
                'agua' => '0 ou 1',
                'sentido' => 'horario, antihorario',
                'power' => 'on, off'
            ));

            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => "Connection: close\r\n".
                                "Content-type: application/x-www-form-urlencoded\r\n".
                                "Content-Length: ".strlen($content)."\r\n",
                    'content' => $content 
                )
            ));
            // Realize comunicação com o servidor
            $contents = file_get_contents($servidor, null, $context);            
            $resposta = json_decode($contents);  //Parser da resposta Json
            echo $resposta;
        */

        private $server = 'http://200.144.12.44:1880/testeLH';
        private $method;
        private Array $params;
        
		function __construct($method, Array $params)
		{
			$this->method = $method;
            $this->params = $params;
		}

        private function setContent()
        {
            if(isset($this->params))
            {
                $content = http_build_query($this->params);
                return $content;
            }
            return false;
        }

        private function setContext()
        {
            $content = $this->setContent();
            if($content)
            {
                $context = stream_context_create(array(
                'http' => array(
                    'method' => $this->method,
                    'header' => "Connection: close\r\n".
                                "Content-type: application/x-www-form-urlencoded\r\n".
                                "Content-Length: ".strlen($content)."\r\n",
                    'content' => $content
                    )
                ));
                return $context;
            }
            return false;
        }

        public function send()
        {
            $context = $this->setContext();
            if($context)
            {
                $contents = file_get_contents($this->server, null, $context);
                $response = json_decode($contents);
                return $response;
            }
            return false;
        }
	}
?>