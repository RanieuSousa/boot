@extends('layouts/contentNavbarLayout')

@section('title', 'Ciclus')

@section('page-script')
  @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

    <!-- Form controls -->

      <div class="card">
        <h5 class="card-header">Criar Ciclus</h5>
        <form action="{{route('storecicuos')}}"  method="post">
          @csrf
        <div class="card-body">
          <div class="mb-4">
            <label for="exampleFormControlInput1" class="form-label">Dias</label>
            <input type="number" class="form-control" id="dias" name="dias" placeholder="Dias" required />
          </div>
          <div class="mb-4">
            <label for="exampleFormControlSelect1" class="form-label">Mensagem</label>
            <select class="form-select" id="memsagem" name="memsagem" aria-label="Default select example" required>
              <option selected>Selecione a mensagem</option>
              @foreach($mensagem as $mensagem)
              <option value="{{$mensagem->id}}">{{$mensagem->titulo}}</option>
              @endforeach
            </select>
          </div>
          <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">Salvar</button>
            <a href="{{route('ciclus')}}"><button type="button" class="btn btn-warning">Cancelar</button></a>
          </div>
        </div>
        </form>
      </div>



@endsection

