<?php

class Pedido
{
    public $id;
    public $fechaAlta;
    public $idMesa;
    public $fotoMesa;
    public $idMozo;
    public $codigo;



    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (fechaAlta,idMesa,fotoMesa,idMozo,estado,codigo) VALUES (:fechaAlta,:idMesa,:fotoMesa,:idMozo,1,:codigo)");
        $fecha = new DateTime(date("d-m-Y h:i:s"));
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fotoMesa', $this->fotoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT ppp.id, ppp.fechaAlta,ppp.idMesa,ppp.fotoMesa,ppp.idMozo,ppp.estado,ppp.codigo, (SELECT MAX(T.tiempoEstimado) AS TiempoEstimado FROM comandas C LEFT JOIN pedidos P ON C.idPedido=P.id LEFT JOIN tiempospromedio T ON C.idProducto=T.idProducto where P.codigo=ppp.codigo) AS tiempoEstimado FROM pedidos ppp");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }
    public static function traerPedidosPorEmpleado($idEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT ppp.id, ppp.fechaAlta,ppp.idMesa,ppp.fotoMesa,ppp.idMozo,ppp.estado,ppp.codigo, (SELECT MAX(T.tiempoEstimado) AS TiempoEstimado FROM comandas C LEFT JOIN pedidos P ON C.idPedido=P.id LEFT JOIN tiempospromedio T ON C.idProducto=T.idProducto where P.codigo=ppp.codigo) AS tiempoEstimado FROM pedidos ppp where idMozo = :idEmpleado");
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }
    public static function traerPedidosListos()
    {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * from pedidos where estado=2");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }
    public static function traerPedidoCompleto($codigo)
    {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT  prod.nombre, prod.precio FROM comandas C LEFT JOIN pedidos P ON C.codigoPedido=P.codigo LEFT JOIN tiempospromedio T ON C.idProducto=T.idProducto LEFT JOIN productos prod ON C.idProducto=prod.id where C.codigoPedido=:codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS,'Pedido');
    }

    public static function obtenerPedido($codigo)
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, fechaAlta,idMesa,fotoMesa,idMozo,fechaBaja,estado FROM pedidos WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
    public static function tiempoPedido($codigo)
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT MAX(T.tiempoEstimado) AS TiempoEstimado FROM comandas C LEFT JOIN pedidos P ON C.idPedido=P.id LEFT JOIN tiempospromedio T ON C.idProducto=T.idProducto where P.codigo=:codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchColumn();
    }
    public static function actualizarEstadoPedido($estado,$codigo)
    {
        
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado  WHERE codigo = :codigo");

        
        $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);

        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);

        return $consulta->execute();
    }
    public static function cerrarPedido($mesa)
    {
        
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = 3  WHERE idMesa = :mesa");

        
        $consulta->bindValue(':mesa', $mesa, PDO::PARAM_INT);

        return $consulta->execute();
    }

    public static function modificarPedido($id, $idMesa, $idMozo, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET idMesa = :idMesa, fotoMesa = :fotoMesa, idMozo = :idMozo, estado = :estado,  WHERE id = :id");

        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
       // $consulta->bindValue(':fotoMesa', $fotoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':idMozo', $idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja, estado=3 WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y h:i:s"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'd-m-Y h:i:s'));
        $consulta->execute();
    }

    public function guardarFoto()
    {
        $nombreFoto = explode(".", $_FILES["fotoMesa"]["name"]);

        $extension = $nombreFoto[1];

        $nombreFoto = $this->id . "." . $extension;
        $destino = "./FotosPedidos/" . $nombreFoto;

        if (move_uploaded_file($_FILES["fotoMesa"]["tmp_name"], $destino)) {
            return $destino;
        } else {
            return false;
        }
    }
}
