@extends('layouts/contentNavbarLayout')

@section('title', 'Cliente em Potencial')

@section('content')
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Card para a tabela -->
  <div class="card">
    <h5 class="card-header">Clientes Potenciais</h5>
    <div class="table-responsive text-nowrap">
      <table class="table" style="font-size: 12px;" id="clientesTable">
        <thead>
        <tr>
          <th><input type="text" class="form-control" placeholder="Código"></th>
          <th><input type="text" class="form-control" placeholder="Nome"></th>
          <th><input type="text" class="form-control" placeholder="Vendedor"></th>
          <th><input type="text" class="form-control" placeholder="Empresa"></th>
          <th><input type="text" class="form-control" placeholder="Última Compra"></th>
        </tr>
        <tr>
          <th>Código</th>
          <th>Nome</th>
          <th>Vendedor</th>
          <th>Empresa</th>
          <th>Última Compra</th>
          <th>Dias Sem Comprar</th>
          <th>Faturamento</th>
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
            <td>{{ $cliente->cliente_id }}</td>
            <td>{{ $cliente->nome }}</td>
            <td>{{ $cliente->vendedor }}</td>
            <td>{{ $cliente->empresa }}</td>
            <td>{{ \Carbon\Carbon::parse($cliente->ultima_compra)->format('d/m/Y') }}</td>
            <td>{{ $cliente->dias_sem_comprar }} dias</td>
            <td>R${{ number_format($cliente->valor, 2, ',', '.') }}</td>
            <td>
                        <span class="badge
                            @if ($cliente->status == 1) bg-label-warning
                            @elseif ($cliente->status == 2) bg-label-success
                            @elseif ($cliente->status == 3) bg-label-danger
                            @else bg-label-secondary
                            @endif">
                            @if ($cliente->status == 1)  PENDENTE
                          @elseif ($cliente->status == 2) EMAIL ENVIADO
                          @elseif ($cliente->status == 3) DESATIVADO
                          @else Desconhecido
                          @endif
                        </span>
            </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  @if ($cliente->status == 3)
                    <a class="dropdown-item" href="{{ route('ativarclientespotencial', $cliente->id) }}">
                      <i class="bx bx-edit-alt me-1"></i> Ativar
                    </a>
                  @else
                    <a class="dropdown-item" href="{{ route('desativarclientespotencial', $cliente->id) }}">
                      <i class="bx bx-edit-alt me-1"></i> Desativar
                    </a>
                  @endif
                </div>
              </div>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const inputs = document.querySelectorAll('#clientesTable thead tr:first-child input'); // Captura os inputs de pesquisa
      const tableRows = document.querySelectorAll('#clientesTable tbody tr'); // Captura todas as linhas da tabela

      inputs.forEach((input, columnIndex) => {
        input.addEventListener('input', function () {
          const searchTerm = input.value.toLowerCase(); // Texto digitado no input
          tableRows.forEach(row => {
            const cell = row.cells[columnIndex]; // Captura a célula correspondente na linha
            if (cell) {
              const cellText = cell.textContent.toLowerCase(); // Conteúdo da célula
              if (cellText.includes(searchTerm)) {
                row.style.display = ''; // Mostra a linha se o texto corresponder
              } else {
                row.style.display = 'none'; // Oculta a linha se não corresponder
              }
            }
          });
        });
      });
    });

  </script>
@endsection

