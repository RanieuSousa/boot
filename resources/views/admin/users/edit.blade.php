@extends('layouts/contentNavbarLayout')

@section('title', 'Usuário')

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
    <form action="{{route('updateusers',$user->id)}}" method="post">
      @csrf
      <div class="card-body">
        <div class="mb-4">
          <label for="exampleFormControlReadOnlyInput1" class="form-label">Nome</label>
          <input class="form-control" type="text" id="name" name="nome" placeholder="Nome"  required  value="{{$user->name}}"/>
        </div>
        <div class="mb-4">
          <label for="exampleFormControlInput1" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Email" required value="{{$user->email}}" />
        </div>
        <div class="mb-4">
          <label for="exampleFormControlReadOnlyInput1" class="form-label">Senha</label>
          <input class="form-control" type="password" id="senha" name="senha" placeholder="Senha" />
        </div>
        <div class="mb-4">
          <div class="form-check">
            <input type="hidden" name="type" value="0"> <!-- Valor 0 enviado se não marcado -->
            <input
              class="form-check-input"
              type="checkbox"
              value="1"
              name="type"
              id="type"
              {{ $user->type == 1 ? 'checked' : '' }}>
            <label class="form-check-label" for="vendedor">Vendedor</label>
          </div>


        </div>
        <div class="mb-4">
          <label for="exampleFormControlSelect1" class="form-label">Empresas</label>
          <div class="row">
            @foreach($empresas as $empresa)
              <div class="col-md-2 mb-3">
                <div class="form-check">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    value="{{ $empresa->id }}"
                    id="empresa{{ $empresa->id }}"
                    name="empresas[]"
                    @if($empresaUsers->contains('empresa_id', $empresa->id)) checked @endif
                  >
                  <label class="form-check-label" for="empresa{{ $empresa->id }}">
                    {{ $empresa->nome }}
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
                  <input
                    class="form-check-input"
                    type="checkbox"
                    value="{{ $grupo->id }}"
                    id="grupo{{ $grupo->id }}"
                    name="grupos[]"
                    @if($grupoUsers->contains('grupo_id', $grupo->id)) checked @endif
                  >
                  <label class="form-check-label" for="grupo{{ $grupo->id }}">
                    {{ $grupo->nome }}
                  </label>
                </div>
              </div>
            @endforeach

          </div>
        </div>
        <div class="mt-6">
          <button type="submit" class="btn btn-primary me-3">Salvar</button>
          <a href="{{route('users')}}"> <button type="button" class="btn btn-outline-secondary">Cancelar</button></a>
        </div>
      </div>
    </form>
  </div>

@endsection
