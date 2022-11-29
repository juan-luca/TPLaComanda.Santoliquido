<?php
interface IApiUsablePedido
{
	public function TraerUno($request, $response, $args);
	public function TraerTodos($request, $response, $args);
	public function traerPedidosPorEmpleado($request, $response, $args);
	public function CargarUno($request, $response, $args);
	public function BorrarUno($request, $response, $args);
	public function ModificarUno($request, $response, $args);
}
