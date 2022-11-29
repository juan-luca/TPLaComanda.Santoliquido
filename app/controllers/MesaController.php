<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $idSalon = $parametros['idSalon'];

        // Creamos el Mesa
        $mesa = new Mesa();
        $mesa->descripcion = $descripcion;
        $mesa->salon = $idSalon;

        $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function servirMesa($request, $response, $args)
    {
      
        
        
        
        
        $id = $args['id'];
        
        
        
        
        if(Mesa::actualizarEstadoMesa(2,$id))
        {
          $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
        }else
        {
          $payload = json_encode(array("mensaje" => "Mesa NO SE PUDO MODIFICAR"));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function cerrarMesa($request, $response, $args)
    {
        $id = $args['id'];
        $payload = json_encode(array("mensaje" => "ERROR ALC ERRAR LA MESA"));
        if(Mesa::actualizarEstadoMesa(3,$id))
        {
          if(Pedido::cerrarPedido($id))
          {
            $payload = json_encode(array("mensaje" => "Se cerraron con exito el pedido y la mesa."));
          }
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function TraerUno($request, $response, $args)
    {
        // Buscamos Mesa por id
        $id = $args['id'];
        $mesa = Mesa::obtenerMesa($id);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        
        $payload = json_encode(array("listaMesa" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      
        $parametros = $request->getParsedBody();
        
        
        $id = $args['id'];
        $descripcion = $parametros['descripcion'];
        $idSalon = $parametros['idSalon'];
        $idEstado = $parametros['idEstado'];
        
        
        if(Mesa::modificarMesa($id,$descripcion,$idSalon,$idEstado))
        {
          $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
        }else
        {
          $payload = json_encode(array("mensaje" => "Mesa NO SE PUDO MODIFICAR"));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];
        Mesa::borrarMesa($id);

        $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
