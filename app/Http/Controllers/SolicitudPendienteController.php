<?php

namespace App\Http\Controllers;

use App\Medicamento;
use App\Beneficiario;
use App\SalidaMedicamento;
use Illuminate\Http\Request;
use App\SolicitudMedicamento;
use App\MedicamentoRequerido;
use App\Http\Requests\SolicitudRequest;
use App\Http\Database\MedicamentoDatabase;
use App\Http\Database\VerificacionSalidaDatabase;
use App\Http\Database\SalidaMedicamentoDatabase;
use App\Http\Database\MedicamentoRequeridoDatabase;

class SolicitudPendienteController extends Controller
{
    public function mostrarSolicitud($idBeneficiario)
    {
    	$beneficiario = Beneficiario::find($idBeneficiario);
        $solicitudMedicamento = SolicitudMedicamento::all()->last();
        $medicamentosRequeridos = Beneficiario::medicamentosRequeridosPorUnBeneficiario($idBeneficiario, $solicitudMedicamento->id_solicitud);
        $medicamentosAgregados = SalidaMedicamento::medicamentosAgregados($solicitudMedicamento->id_solicitud);

    	return view('solicitudPendiente.principal')->with('beneficiario', $beneficiario)
                                                    ->with('noSolicitud', $solicitudMedicamento->id_solicitud)
                                                    ->with('medicamentosRequeridos', $medicamentosRequeridos)
                                                    ->with('medicamentos', $medicamentosAgregados);
    }

    public function mostrarSolicitudes()
    {
        //$solicitudMedicamento = SolicitudMedicamento::orderBy('id_solicitud', 'desc')->where('tipo_solicitud', 'En proceso')->paginate(10);
        $medicamentoRequerido = SolicitudMedicamento::medicamentosRequeridos();


        //$solicitudMedicamento = SolicitudMedicamento::find($medicamentoRequerido->tb_solicitudes_id_solicitud);
        //dd($medicamentoRequerido);

        return view('solicitudPendiente.solicitudes')->with('solicitudes', $medicamentoRequerido);
    }

    public function agregarMedicamento($idBeneficiario, SolicitudRequest $request)
    {
        $beneficiario = Beneficiario::find($idBeneficiario);
        MedicamentoDatabase::guardarMedicamentoRequerido($request);
        $solicitudMedicamento = SolicitudMedicamento::all()->last();
        $ultimoMedicamento = Medicamento::all()->last();

        MedicamentoRequeridoDatabase::guardarMedicamentoRequerido($ultimoMedicamento->id_medicamento, 
                                                                $solicitudMedicamento->id_solicitud,
                                                                $idBeneficiario);

        $medicamentosRequeridos = Beneficiario::medicamentosRequeridosPorUnBeneficiario($idBeneficiario, $solicitudMedicamento->id_solicitud);
        $medicamentosAgregados = SalidaMedicamento::medicamentosAgregados($solicitudMedicamento->id_solicitud);

        return view('solicitudPendiente.principal')->with('beneficiario', $beneficiario)
                                                    ->with('noSolicitud', $solicitudMedicamento->id_solicitud)
                                                    ->with('medicamentosRequeridos', $medicamentosRequeridos)
                                                    ->with('medicamentos', $medicamentosAgregados);
    }

    public function salidaDeMedicamentoRequerido($idSolicitud, $idMedicamento, $idBeneficiario, $idMedicamentoRequerido)
    {
        $cantidadADonar = 1;
        $medicamento = Medicamento::find($idMedicamento);
        $solicitudMedicamento = SolicitudMedicamento::find($idSolicitud);
        $medicamentoRequerido = MedicamentoRequerido::find($idMedicamentoRequerido);

        SalidaMedicamentoDatabase::guardarSalidaMedicamento (
            $idMedicamento,
            $idBeneficiario,
            $idSolicitud,
            $cantidadADonar
        );

        $medicamento->estatus = 'donado';
        $medicamento->save();

        $medicamentoRequerido->estatus_solicitud = 'donado';
        $medicamentoRequerido->save();

        // Se genera el registro en el LOG
        VerificacionSalidaDatabase::actualizarTipoSolicitud($idSolicitud);
        //LogSalidaMedicamento::guardarLogSalidaMedicamento($idBeneficiario, 'Exitosa', $cantidadADonar);
        
        return redirect()->route('ruta_solicitudes');
    }

    public function cancelarSolicitud()
    {
        $solicitudMedicamento = SolicitudMedicamento::all()->last();
        $solicitudMedicamento->delete();

        return redirect()->route('ruta_salida_medicamentos');
    }

    public function eliminarMedicamento($idMedicamento, $idMedicamentoRequerido, $idBeneficiario)
    {
        $medicamentoRequerido = MedicamentoRequerido::find($idMedicamentoRequerido);
        //dd($idMedicamento);
        $medicamentoRequerido->delete();

        $medicamento = Medicamento::find($idMedicamento);
        $medicamento->delete();

        $beneficiario = Beneficiario::find($idBeneficiario);

        $solicitudMedicamento = SolicitudMedicamento::all()->last();

        $medicamentosRequeridos = Beneficiario::medicamentosRequeridosPorUnBeneficiario($idBeneficiario, $solicitudMedicamento->id_solicitud);
        $medicamentosAgregados = SalidaMedicamento::medicamentosAgregados($solicitudMedicamento->id_solicitud);

        return view('solicitudPendiente.principal')->with('beneficiario', $beneficiario)
                                                    ->with('noSolicitud', $solicitudMedicamento->id_solicitud)
                                                    ->with('medicamentosRequeridos', $medicamentosRequeridos)
                                                    ->with('medicamentos', $medicamentosAgregados);
    }
}
