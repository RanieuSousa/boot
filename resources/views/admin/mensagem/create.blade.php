@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('page-script')
  @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')


    <!-- Form controls -->
    @if(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

      <div class="card">
        <h5 class="card-header">Criar Mensagem</h5>
        <form action="{{route('storemensagem')}}" method="post">
          @csrf
        <div class="card-body">
          <div class="mb-4">
            <label for="exampleFormControlInput1" class="form-label">Chaves</label><br>
            <label for="exampleFormControlInput1" class="form-label">[nome],</label>
            <label for="exampleFormControlInput1" class="form-label">[email],</label>
            <label for="exampleFormControlInput1" class="form-label">[telefone],</label>
            <label for="exampleFormControlInput1" class="form-label">[vendedor],</label>
            <label for="exampleFormControlInput1" class="form-label">[valor_debito],</label>
            <label for="exampleFormControlInput1" class="form-label">[dias_atraso],</label>
            <label for="exampleFormControlInput1" class="form-label">[ultima_compra],</label>
            <label for="exampleFormControlInput1" class="form-label">[valor],</label>
            <label for="exampleFormControlInput1" class="form-label">[dias_sem_compara]</label>


            <div class="mb-4">
              <label for="titulo" class="form-label">Titulo</label>
              <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Titulo" required />
            </div>

            <div class="mb-4">
              <label for="tipo" class="form-label">Tipo</label>
              <select class="form-select" id="tipo" name="tipo" required>
                <option selected disabled>Selecione o tipo</option>
                <option value="1">Vendas</option>
                <option value="2">Aviso</option>
              </select>
            </div>

            <div>
              <label for="mensagem" class="form-label">Mensagem</label>
              <textarea class="form-control" id="mensagem" name="mensagem" rows="3" required></textarea>
            </div>
          <br>
          <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">Salvar</button>
            <a href="{{route('mensagem')}}"> <button type="button" class="btn btn-outline-secondary">Cancelar</button></a>
          </div>
        </div>
        </form>
      </div>


@endsection
