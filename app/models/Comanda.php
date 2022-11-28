<?php

class Comanda
{
    public $id;
    public $fechaAlta;
    public $idPedido;
    public $idProducto;
    public $cantidad;

   

    public function crearComanda()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO comandas (fechaAlta,idPedido,idProducto,cantidad) VALUES (:fechaAlta,:idPedido,:idProducto,:cantidad)");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, fechaAlta,idPedido,idProducto,cantidad,idCliente FROM comandas");
        $consulta->execute();
        
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Comanda');
    }

    public static function obtenerComanda($id)
    {
        
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, fechaAlta,idPedido,idProducto,cantidad,idCliente FROM comandas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Comanda');
    }

    public static function modificarComanda($id,$idPedido,$idProducto,$cantidad,$idCliente)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE comandas SET idPedido = :idPedido, idProducto = :idProducto, cantidad = :cantidad, idCliente = :idCliente,  WHERE id = :id");
       
        $consulta->bindValue(':idPedido', $idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':idCliente', $idCliente, PDO::PARAM_INT);

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }

    public static function borrarComanda($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE Comandas SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}