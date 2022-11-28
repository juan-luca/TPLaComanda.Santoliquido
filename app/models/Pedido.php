<?php

class Pedido
{
    public $id;
    public $fechaAlta;
    public $idMesa;
    public $fotoMesa;
    public $idMozo;



    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (fechaAlta,idMesa,fotoMesa,idMozo,estado) VALUES (:fechaAlta,:idMesa,:fotoMesa,:idMozo,1)");
        $fecha = new DateTime(date("d-m-Y h:i:s"));
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fotoMesa', $this->fotoMesa, PDO::PARAM_STR);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, fechaAlta,idMesa,fotoMesa,idMozo,estado FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($id)
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, fechaAlta,idMesa,fotoMesa,idMozo,fechaBaja,estado FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
    public static function tiempoPedido($id)
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT MAX(T.tiempoEstimado) AS TiempoEstimado FROM comandas C LEFT JOIN pedidos P ON C.idPedido=P.id LEFT JOIN tiempospromedio T ON C.idProducto=T.idProducto where C.idPedido=:id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject();
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
