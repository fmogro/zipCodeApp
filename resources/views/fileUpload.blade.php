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
                    <div class="container">

                        <div class="panel panel-primary">
                            @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                            @endif
                            <div class="panel-body">

                                @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                                {{ Session::get('file') }}
                                <form action="{{ route('file.upload.database') }}" method="POST" name="database">
                                    @csrf
                                    <input type="hidden" name="nombreArchivo" id="" value="{{ Session::get('file') }}">
                                    </input>
                                    <button type="submit" class="btn btn-danger">Cargar Información a base de
                                        datos</button>
                                </form>

                                <p><br>
                                <p></p>
                                </p>
                                @endif

                                @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <div id="ocultar">
                                    <h3>Cargue aquí los códigos postales</h3>
                                    <button type="button" class="btn btn-danger">
                                        Advertencia
                                    </button>
                                    <span class="badge badge-light">Se sobreescribirán todos los registros
                                        almacenados</span>
                                    <p></p>
                                    <form action="{{ route('file.upload.post') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <div class="col-md-6">
                                                <input type="file" name="file" class="form-control">
                                            </div>

                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-success">Upload</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                    @endguest

                </div>
            </div>
        </div>
    </div>
</div>
@endsection