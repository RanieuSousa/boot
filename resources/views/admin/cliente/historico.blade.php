@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('page-script')
  @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

  <!-- Form controls -->
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <div class="card">
    <form action="{{route('storeusers')}}" method="post">
      @csrf
      <div class="card-body">
        <div class="mb-4">
          <label for="exampleFormControlReadOnlyInput1" class="form-label">Nome</label>
          <input class="form-control" type="text" id="name" name="nome" placeholder="Nome"  required />
        </div>
        <div class="mb-4">
          <label for="exampleFormControlInput1" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
        </div>
        <div class="mb-4">
          <label for="exampleFormControlReadOnlyInput1" class="form-label">Senha</label>
          <input class="form-control" type="password" id="senha" name="senha" placeholder="Senha" required  />
        </div>
        <div class="mb-4">
          <label for="exampleFormControlSelect1" class="form-label">Empresas</label>
          <div class="row">

              <div class="col-md-2 mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="{{$empresas->id}}" id="grupo{{$empresas->id}}" name="empresas[]">
                  <label class="form-check-label" for="grupo{{$empresas->id}}">

                  </label>
                </div>
              </div>
            @endforeach
          </div>
          <hr>
        </div>
        <div class="mb-4">
          <label for="exampleFormControlSelect1" class="form-label">Grupo</label>
          <div class="row">
            @foreach($grupos as $grupo)
              <div class="col-md-2 mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="{{$grupo->id}}" id="grupo{{$grupo->id}}" name="grupos[]">
                  <label class="form-check-label" for="grupo{{$grupo->id}}">
                    {{$grupo->nome}}
                  </label>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        <div class="mt-6">
          <button type="submit" class="btn btn-primary me-3">Salvar</button>
          <a href="{{route('users')}}"> <button type="reset" class="btn btn-outline-secondary">Cancelar</button></a>
        </div>
      </div>
    </form>
  </div>

@endsection
