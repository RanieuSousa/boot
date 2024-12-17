@extends('layouts/contentNavbarLayout')

@section('title', 'Clientes')

@section('content')

  <!-- Criação das Abas -->
  <!-- Criação das Abas -->
  <ul class="nav nav-pills mb-3  gap-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="pills-clientes-tab" data-bs-toggle="pill" href="#pills-clientes" role="tab" aria-controls="pills-clientes" aria-selected="true">Clientes</a>
    </li>
    @if($usuario->grupos->pluck('id')->contains(1) || $usuario->grupos->pluck('id')->contains(2))
      <li class="nav-item" role="presentation">
        <a class="nav-link bg-danger text-white" id="pills-segunda-tabela-tab" data-bs-toggle="pill" href="#pills-segunda-tabela" role="tab" aria-controls="pills-segunda-tabela" aria-selected="false">Clientes Vencidos</a>
      </li>
    @endif
  </ul>


  <div class="tab-content" id="pills-tabContent">
    <!-- Primeira Tabela de Clientes -->
    <div class="tab-pane fade show active" id="pills-clientes" role="tabpanel" aria-labelledby="pills-clientes-tab">
      <div class="card">
        <h5 class="card-header">Clientes</h5>
        <div class="table-responsive text-nowrap">

          <div class="row">
            <div class="col-3">
              <input type="text" class="form-control searchInput" data-table="clientes" placeholder="Código">
            </div>
            <div class="col-3">
              <input type="text" class="form-control searchInput" data-table="clientes" placeholder="Nome">
            </div>
            <div class="col-3">
              <input type="text" class="form-control searchInput" data-table="clientes" placeholder="Vendedor">
            </div>
            <div class="col-3">
              <input type="text" class="form-control searchInput" data-table="clientes" placeholder="Empresa">
            </div>
          </div>

          <br>

          <table class="table" style="font-size: 12px;" id="clientesTable">
            <thead>


            <tr>
              <th>Código</th>
              <th>Venda</th>
              <th>Nome</th>
              <th>Vendedor</th>
              <th>Empresa</th>
              <th>Vencimento</th>
              <th>Valor</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
            </thead>
            <tbody class="table-border-bottom-0">
            @foreach($clientes as $cliente)
              <tr class="
            @if ($cliente->status == 1) table-warning
            @elseif ($cliente->status == 2) table-success
            @elseif ($cliente->status == 3) table-danger
            @else table-secondary
            @endif">
                <td>{{ $cliente->codigo }}</td>
                <td>{{ $cliente->venda_id }}</td>
                <td>{{ $cliente->nome }}</td>
                <td>{{ $cliente->vendedor }}</td>
                <td>{{ $cliente->empresa }}</td>
                <td>{{ \Carbon\Carbon::parse($cliente->data_vencimento)->format('d/m/Y') }}</td>
                <td>R${{ number_format($cliente->valor, 2, ',', '.') }}</td>
                <td>
                <span class="badge
                  @if ($cliente->status == 1) bg-label-warning
                  @elseif ($cliente->status == 2) bg-label-success
                  @elseif ($cliente->status == 3) bg-label-danger
                  @else bg-label-secondary
                  @endif">
                  @if ($cliente->status == 1) PENDENTE
                  @elseif ($cliente->status == 2) Enviado Mensagem
                  @elseif ($cliente->status == 3) DESATIVADO
                  @elseif ($cliente->status == 4) PAGAMENTO AGENDADO
                  @endif
                </span>
                </td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{route('editclientes',$cliente->id)}}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Segunda Tabela -->
    <div class="tab-pane fade" id="pills-segunda-tabela" role="tabpanel" aria-labelledby="pills-segunda-tabela-tab">
      <div class="card">
        <h5 class="card-header">Clientes Vencidos</h5>
        <div class="table-responsive text-nowrap">
          <div class="row">
            <div class="col-3">
              <input type="text" class="form-control searchInput" data-table="segunda" placeholder="Código">
            </div>
            <div class="col-3">
              <input type="text" class="form-control searchInput" data-table="segunda" placeholder="Nome">
            </div>
            <div class="col-3">
              <input type="text" class="form-control searchInput" data-table="segunda" placeholder="Vendedor">
            </div>
            <div class="col-3">
              <input type="text" class="form-control searchInput" data-table="segunda" placeholder="Empresa">
            </div>
          </div>

          <br>
          <table class="table" style="font-size: 12px;" id="segundaTabela">
            <thead>

            <tr>
              <th>Código</th>
              <th>vendas</th>
              <th>Nome</th>
              <th>Empresa</th>
              <th>Vencimento</th>
              <th>Valor</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clientes30 as $item)

              <tr class="
            @if ($item->status == 1) table-danger
            @elseif ($item->status == 2) table-danger
            @elseif ($item->status == 3) table-danger
            @else table-secondary
            @endif">
                <td>{{ $item->codigo }}</td>
                <td>{{ $item->venda_id }}</td>
                <td>{{ $item->nome }}</td>
                <td>{{ $item->empresa }}</td>
                <td>{{ \Carbon\Carbon::parse($item->data_vencimento)->format('d/m/Y') }}</td>
                <td>R${{ number_format($item->valor, 2, ',', '.') }}</td>
                <td><span class="badge
                @if ($item->status == 1) bg-label-danger
                @elseif ($item->status == 2) bg-label-success
                @elseif ($item->status == 3) bg-label-danger
                @else bg-label-secondary
                @endif">
                @if ($item->status == 1) PENDENTE
                    @elseif ($item->status == 2)  Mensagem Enviada
                    @elseif ($item->status == 3) Desativado
                    @else Desconhecido
                    @endif
              </span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{route('editclientes',$item->id)}}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                    </div>
                  </div>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const inputs = document.querySelectorAll('.searchInput');

      inputs.forEach((input) => {
        input.addEventListener('input', function () {
          const tableType = input.getAttribute('data-table');
          const searchText = input.value.toLowerCase();

          let rows;
          if (tableType === 'clientes') {
            rows = document.querySelectorAll('#clientesTable tbody tr');
          } else {
            rows = document.querySelectorAll('#segundaTabela tbody tr');
          }

          rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let match = false;

            cells.forEach(cell => {
              if (cell.textContent.toLowerCase().includes(searchText)) {
                match = true;
              }
            });

            if (match) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });
      });
    });
  </script>

@endsection
