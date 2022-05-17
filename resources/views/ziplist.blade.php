@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @guest
                    <script>window.location = "/login";</script>
                    @else
                    <div>
                        <table border="1" align="center">
                            <tr>
                                <th>Codigo</th>
                                <th width="320px">Asenta</th>
                                <th width="70px">Tipo</th>
                                <th width="70px">Municipio</th>
                                <th width="140px">Estado</th>
                            </tr>
                            @foreach($data as $ciudad)
                            <tr>
                                <td>{{ html_entity_decode($ciudad->d_codigo) }}</td>
                                <td>{{ html_entity_decode($ciudad->d_asenta) }}</td>
                                <td>{{ html_entity_decode($ciudad->d_tipo_asenta) }}</td>
                                <td>{{ html_entity_decode($ciudad->d_mnpio) }}</td>
                                <td>{{ html_entity_decode($ciudad->d_estado) }}</td>
                            </tr>
                            @endforeach
                        </table>
                        <p><br></p>
                        {!! $data->links() !!}
                    </div>
                    @endguest

                </div>
            </div>
        </div>
    </div>
</div>
@endsection