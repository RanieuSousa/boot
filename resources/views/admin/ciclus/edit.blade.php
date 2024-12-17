@extends('layouts/contentNavbarLayout')

@section('title', 'Ciclus - Forms')

@section('page-script')
  @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

  <!-- Form controls -->

  <div class="card">
    <h5 class="card-header">Edit Ciclus</h5>
    <form action="{{route('updateciculos',$ciclus->id)}}"  method="post">
      @csrf
      <div class="card-body">
        <div class="mb-4">
          <label for="exampleFormControlInput1" class="form-label">Dias</label>
          <input type="number" class="form-control" id="dias" name="dias" placeholder="Dias" required value="{{$ciclus->dias}}" />
        </div>
        <div class="mb-4">
          <label for="exampleFormControlSelect1" class="form-label">Mensagem</label>
          <select class="form-select" id="memsagem" name="memsagem" aria-label="Default select example" required>
            <option value="" disabled>Selecione a mensagem</option>
            @foreach($mensagems as $mensagem)
              <option value="{{ $mensagem->id }}" {{ $mensagem->id == $ciclus->memsagem_id ? 'selected' : '' }}>
                {{ $mensagem->titulo }}
              </option>
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

