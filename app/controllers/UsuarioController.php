<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $idPerfil = $parametros['idPerfil'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->idPerfil = $idPerfil;

        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function Login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['usuario'];
        $clave = $parametros['clave'];
        $usuario = Usuario::obtenerUsuario($nombre);
        $retorno="error";

        if ($parametros != null) {
          if (count($parametros) == 2) {

            
              
              if(password_verify($clave,$usuario->clave))
              {
                $parametros['idPerfil']=$usuario->idPerfil;
               
                $token=AutentificadorJWT::CrearToken($parametros);
                $retorno=$token;
              }else
              {
                $retorno="clave incorrecta";
              }

              
              

          } else {
              foreach ($parametros as $item  => $value) {
                  if ($item == "clave") {
                      $retorno = "Falta ingresar el usuario";
                  } else {
                      $retorno = "Falta ingresar la clave";
                  }
              }
          }
      } else {
          $retorno = "No hay parametros";
      }

        
        
        

        $payload = json_encode(array("mensaje" => $retorno));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
     
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
      
        $parametros = $request->getParsedBody();
        
        
        $id = $args['id'];
        
        var_dump($parametros);
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $idPerfil = $parametros['idPerfil'];
        
        
        if(Usuario::modificarUsuario($id,$usuario,$clave,$idPerfil))
        {
          $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        }else
        {
          $payload = json_encode(array("mensaje" => "Usuario NO SE PUDO MODIFICAR"));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        

        $id = $args['id'];
        Usuario::borrarUsuario($id);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function exportarUsuariosCSV($request, $response, $args)
  {
    $lista = Usuario::obtenerTodos();

    $payload = json_encode($lista);


    header('Content-Type: application/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=datos.csv');


    ob_end_clean();

    
      $data = json_decode($payload, true);
      $archivo = fopen("datos.csv", 'w');
      foreach ($data as $row) {
        fputcsv($archivo, $row);
      }
      fclose($archivo);
    
    readfile('./datos.csv');

    return $response->withHeader('Content-Type','application/csv');
  }

}
