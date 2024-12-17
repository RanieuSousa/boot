@extends('layouts/contentNavbarLayout')

@section('title', 'Editar Cliente')

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
    <div class="card-body">
      <div class="mb-4 d-flex flex-wrap">
        <label for="codigo" class="form-label me-4"><b>Código: </b>{{$cliente->codigo}}</label>
        <label for="email" class="form-label me-4"><b>Email:</b> {{$cliente->nome}}</label>
        <label for="email" class="form-label me-4"><b>Email:</b> {{$cliente->email}}</label>
        <label for="telefone" class="form-label me-4"><b>Telefone:</b> {{$cliente->telefone}}</label>
        <label for="valor" class="form-label me-4"><b>Valor: R$</b> {{ number_format($cliente->valor, 2, ',', '.') }}</label>
        <label for="vendedor" class="form-label me-4"><b>Vendedor:</b> {{$cliente->vendedor}}</label>
        <label for="empresa" class="form-label me-4"><b>Empresa:</b> {{$cliente->empresa}}</label>
        <label for="data" class="form-label me-4"><b>Data Vencimento: </b>{{ \Carbon\Carbon::parse($cliente->data_vencimento)->format('d/m/Y') }}</label>
      </div>
      <hr style="color: green">
      @foreach($historico AS $historico)

        <div class="mb-4 d-flex align-items-center">
          <div class="avatar avatar-online me-2">
            <img src="{{ asset('storage/' . $historico->photo) }}" alt="Foto do perfil" class="w-px-40 h-auto rounded-circle">
          </div>
          <p class="mb-0">{{$historico->name}}</p>
        </div>
        <div class="mb-4 d-flex flex-wrap">
          <label for="data_atualizacao" class="form-label me-4"><b>Data Atualização: {{\Carbon\Carbon::parse($historico->created_at)->format('d/m/Y')}}</b></label>
        </div>

        <div class="mb-4 d-flex flex-wrap">
          <label for="previsao_pagamento" class="form-label me-4">P<b>revisão de Pagamento: {{\Carbon\Carbon::parse($historico->data_previsao)->format('d/m/Y')}}</b></label>

        </div>

        <div class="mb-4 d-flex flex-wrap">
          <!-- Label e observação na mesma linha -->
          <label for="observacao" class="form-label me-4"><b>Observação:</b></label><br>

        </div>

        <div class="mb-4 d-flex flex-wrap">
          <p class="mb-0">{{$historico->observação}}</p>
        </div>
        <hr style="color: green">
      @endforeach




      <form action="{{ route('updateclientes',$cliente->id) }}" method="post">
        @csrf
        <div class="mb-4">
          <label for="observacao" class="form-label">Observação</label>
          <textarea name="observacao" id="observacao" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-4">
          <label for="exampleFormControlReadOnlyInput1" class="form-label">Previsão de Pagamento</label>
          <input class="form-control" type="date" id="data" name="data" placeholder="Previsão de Pagamento"  required />
        </div>
        <button type="submit" class="btn btn-primary me-3">Salvar</button>
        <a href="{{ route('clientes') }}">
          <button type="button" class="btn btn-outline-secondary">Cancelar</button>
        </a>
      </form>

    </div>

@endsection
