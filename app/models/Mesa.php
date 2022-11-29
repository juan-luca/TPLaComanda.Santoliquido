<?php

class Mesa
{
    public $id;
    public $descripcion;
    public $salon;
    public $idEstado;

   

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (descripcion, idSalon, idEstado) VALUES (:descripcion, :salon, 1)");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':salon', $this->salon);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT M.id, M.descripcion, M.idSalon, E.descripcion FROM mesas M,estadosmesa E where M.idEstado=E.id");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, descripcion, salon, idEstado FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function modificarMesa($id,$descripcion,$salon,$idEstado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET descripcion = :descripcion, salon = :salon, idEstado = :idEstado WHERE id = :id");
        $consulta->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':salon', $salon, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':idEstado', $idEstado, PDO::PARAM_INT);

        return $consulta->execute();
    }
    public static function actualizarEstadoMesa($estado,$id)
    {
        
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET idEstado = :estado  WHERE id = :id");

        
        $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);

        $consulta->bindValue(':id', $id, PDO::PARAM_INT);

        return $consulta->execute();
    }

    public static function borrarMesa($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET idEstado = :idEstado WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':idEstado', -1);
        $consulta->execute();
    }
}