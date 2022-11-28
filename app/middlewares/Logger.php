<?php

use Psr7Middlewares\Middleware\Expires;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class LoggerMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = new Response();
        $parametros = $request->getParsedBody();


        $retorno = "";
        $flags = [0, 0];
        $count = 0;
        $ok = false;

        if ($parametros != null) {
            foreach ($parametros as $item  => $value) {

                switch ($item) {
                    case "usuario":
                        $flags[0] = 1;
                        break;
                    case "clave":
                        $flags[1] = 1;
                        break;
                }
            }


            if ($flags[0] == 0) {
                $retorno .= "<br>Falta ingresar el usuario";
            } else {
                $count++;
            }
            if ($flags[1] == 0) {
                $retorno .= "<br>Falta ingresar la clave";
            } else {
                $count++;
            }

            $payload = json_encode($retorno);

            $response->getBody()->write($payload);

            if ($count == 2) {
                $response = $handler->handle($request);

                return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
            } else {
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }
    }
}
