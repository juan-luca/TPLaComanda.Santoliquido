<?php

use Firebase\JWT\JWT;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class AdminVerificador
{
   
    
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $header = $request->getHeaderLine('Authorization');
        $token = "";
        $payload="";
        
        try {
          if($header!="")
          {

            $token = trim(explode("Bearer", $header)[1]);

          }
          AutentificadorJWT::VerificarToken($token);
          $D=AutentificadorJWT::ObtenerData($token);
          $data=array();
          foreach ($D as $key => $value) {
            array_push($data,$value);
          }

          if($data[2]==1)
          {
            $response = $handler->handle($request);

          }else{
            
            $payload = json_encode("El usuario no es admin.");
          }
        } catch (Exception $e) {

          $payload = json_encode(array('error' => $e->getMessage()));
          
        }
        if($payload!="")
        {
        $response->getBody()->write($payload);

        }
            return $response->withHeader('Content-Type', 'application/json');
    }

}
