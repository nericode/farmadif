 <table width="100%" class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre comercial</th>
            <th>Nombre compuesto</th>
            <th>No. etiqueta</th>
            <th>No. folio</th>
            <th>Fecha caducidad</th>
            <th>Cantidad</th>
            <th>Solucion/Tableta</th>
            <th>Contenido</th>
            <th>Agregar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($medicamentos as $medicamento)
        <tr>
            <th>{{ $medicamento->id_medicamento }}</th>
            <th>{{ $medicamento->nombre_comercial }}</th>
            <th>{{ $medicamento->nombre_compuesto }}</th>
            <th>{{ $medicamento->num_etiqueta }}</th>
            <th>{{ $medicamento->num_folio }}</th>
            <th>{{ $medicamento->fecha_caducidad }}</th>
            <th class="tb-cantidad">{{ $medicamento->cantidad }}</th>
            <th>{{ $medicamento->solucion_tableta }}</th>
            <th>{{ $medicamento->tipo_contenido }}</th>
            <th>
                <center>
                    <form action="{{ route('ruta_agregar_medicamento', ['id' => $medicamento->id_medicamento, 'idb' => $beneficiario->id_beneficiario]) }}" method="post" id="form-agregar">
                        {{ csrf_field() }}
                        <button class="btn btn-primary btn-small btn-agregar">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </button>
                    </form>
                </center>
            </th>
        </tr>
        @endforeach
    </tbody>
</table>