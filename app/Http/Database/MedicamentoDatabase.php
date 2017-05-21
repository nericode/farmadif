<?php

namespace App\Http\Database;

use App\Medicamento;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SolicitudRequest;
use App\Http\Requests\RegistrarMedicamentoRequest;

class MedicamentoDatabase
{
    /**
    * Permite guardar en la base de datos el donador
    * @param RegistrarDonadorRequest  $request
    * @return void
    */
	public static function guardarMedicamento(RegistrarMedicamentoRequest $request)
	{
        $Medicamento = new Medicamento();
        $Medicamento->nombre_compuesto = $request->get('nombre_compuesto');
    	$Medicamento->nombre_comercial = $request->get('nombre_comercial');
        $Medicamento->num_etiqueta = $request->get('nro_etiqueta');
        $Medicamento->num_folio = $request->get('nro_folio');
        $Medicamento->mes_caducidad = $request->get('mes_caducidad');
        $Medicamento->anio_caducidad = $request->get('anio_caducidad');
        $Medicamento->dosis = $request->get('cantidad_re');
        $Medicamento->solucion_tableta = $request->get('precentacion');
        $Medicamento->tipo_contenido = $request->get('medida');
        $Medicamento->fecha_registro = date("Y-m-d");
        $Medicamento->estatus = 'existencia';
        $Medicamento->save();
	}

    public static function actualizarMedicamento(RegistrarMedicamentoRequest $request, $idMedicamento)
    {
        $medicamento = Medicamento::find($idMedicamento);
        $medicamento->nombre_compuesto = $request->get('nombre_compuesto');
        $medicamento->nombre_comercial = $request->get('nombre_comercial');
        $medicamento->dosis = $request->get('cantidad_re');
        $medicamento->solucion_tableta = $request->get('precentacion');
        $medicamento->tipo_contenido = $request->get('medida');
        $medicamento->num_etiqueta = $request->get('nro_etiqueta');
        $medicamento->num_folio = $request->get('nro_folio');
        $medicamento->mes_caducidad = $request->get('mes_caducidad');
        $medicamento->anio_caducidad = $request->get('anio_caducidad');
        $medicamento->estatus = 'existencia';
        $medicamento->fecha_registro = date("Y-m-d");
        $medicamento->save();
    }

    public static function guardarMedicamentoRequerido(SolicitudRequest $request)
    {
        $medicamento = new Medicamento();
        $medicamento->nombre_compuesto = $request->get('nombre_compuesto');
        $medicamento->nombre_comercial = $request->get('nombre_comercial');
        $medicamento->dosis = $request->get('dosis');
        $medicamento->solucion_tableta = $request->get('precentacion');
        $medicamento->tipo_contenido = $request->get('medida');
        $medicamento->estatus = 'requerido';
        $medicamento->fecha_registro = date("Y-m-d");
        $medicamento->save();
    }
}