<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

      
        $idMesa = $parametros['idMesa'];
        
        $idMozo = $parametros['idMozo'];
        $codigo = $parametros['codigo'];

        // Creamos el Pedido
        $ped = new Pedido();
        $ped->idMesa = $idMesa;
        $ped->idMozo = $idMozo;
        $ped->codigo = $codigo;
        $ped->fotoMesa = $ped->guardarFoto();

        $ped->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function tiempoEstimado($request, $response, $args)
    {
        
        $codigo = $args['codigo'];
        $tiempoEstimado= Pedido::tiempoPedido($codigo);
        $payload = json_encode(array("mensaje" => "El tiempo estimado de entrega es de: ".$tiempoEstimado));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Pedido por id
        $id = $args['id'];
        $pedido = Pedido::obtenerPedido($id);
        $payload = json_encode($pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
       
        $payload = json_encode(array("listaPedido" => $lista));
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodosPorEmpleado($request, $response, $args)
    {
      $idEmpleado=$args['id'];
        $lista = Pedido::traerPedidosPorEmpleado($idEmpleado);
       
        $payload = json_encode(array("listaPedido" => $lista));
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function TraerListos($request, $response, $args)
    {
      
        $lista = Pedido::traerPedidosListos();
       
       
        $payload = json_encode(array("listaPedido" => $lista));
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function actualizarEstado($request, $response, $args)
    {
      
        $parametros = $request->getParsedBody();
        
        
        
        $codigo = $parametros['codigo'];
        
        $idEstado = $parametros['idEstado'];
        
        
        if(Pedido::actualizarEstadoPedido($idEstado,$codigo))
        {
          $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
        }else
        {
          $payload = json_encode(array("mensaje" => "Pedido NO SE PUDO MODIFICAR"));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function cobrarPedido($request, $response, $args)
    {
      
        
        
        
        $total=0;
        $codigo = $args['codigo'];
        $pedido=Pedido::traerPedidoCompleto($codigo);
        foreach ($pedido as $item) {
          $total=+$item->precio;
        }
       echo $total;
        if($total>0)
        {
          $payload = json_encode(array("mensaje" => "El total a abonar del pedido es: $".$total));
        }else
        {
          $payload = json_encode(array("mensaje" => "Pedido NO SE PUDO MODIFICAR"));
        }
        

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
        
        
        if(Pedido::modificarPedido($id, $idMesa, $idMozo, $idEstado))
        {
          $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
        }else
        {
          $payload = json_encode(array("mensaje" => "Pedido NO SE PUDO MODIFICAR"));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        

        $id = $args['id'];
        Pedido::borrarPedido($id);

        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
