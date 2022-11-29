<?php
require_once './models/Comanda.php';
require_once './interfaces/IApiUsable.php';

class ComandaController extends Comanda implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

      
        $idPedido = $parametros['idPedido'];
        $idProducto = $parametros['idProducto'];
        $cantidad = $parametros['cantidad'];
        $codigoPedido = $parametros['codigoPedido'];

        // Creamos el Comanda
        $ped = new Comanda();
        $ped->idPedido = $idPedido;
        $ped->idProducto = $idProducto;
        $ped->cantidad = $cantidad;
        $ped->codigoPedido = $codigoPedido;

        $ped->crearComanda();
        
        $tiempoEstimado= Pedido::tiempoPedido($codigoPedido);
        $payload = json_encode(array("mensaje" => "Comanda creado con exito. El tiempo estimado de entrega es de: ".$tiempoEstimado));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Comanda por id
        $id = $args['id'];
        $Comanda = Comanda::obtenerComanda($id);
        $payload = json_encode($Comanda);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Comanda::obtenerTodos();
        $payload = json_encode(array("listaComanda" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      
        $parametros = $request->getParsedBody();
        
        
        $id = $args['id'];
        $idMesa = $parametros['idMesa'];
        
        $idMozo = $parametros['idMozo'];
        $idEstado = $parametros['idEstado'];
        
        
        if(Comanda::modificarComanda($id, $idMesa, $idMozo, $idEstado))
        {
          $payload = json_encode(array("mensaje" => "Comanda modificado con exito"));
        }else
        {
          $payload = json_encode(array("mensaje" => "Comanda NO SE PUDO MODIFICAR"));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        

        $id = $args['id'];
        Comanda::borrarComanda($id);

        $payload = json_encode(array("mensaje" => "Comanda borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
