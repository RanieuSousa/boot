@extends('layouts/contentNavbarLayout')

@section('title', 'Mensagem')

@section('content')

  @if(session('error'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <a href="{{route('createmensagem')}}">
    <button type="submit" class="btn btn-primary me-3">Nova mensagem</button>
  </a>
  <br>
  <br>
  <div class="card">
    <h5 class="card-header">Mensagem</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
        <tr>
          <th>titulo</th>
          <th>tipo</th>
          <th>Ação</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0">
        @foreach($mensagens as $mensagens)
          <tr>
            <td>{{$mensagens->titulo}}</td>

            <td>

                @if($mensagens->tipo == 1)
                <span class="badge bg-label-success me-1">
                  Vendas
                   </span>
                @elseif($mensagens->tipo == 2)
                <span class="badge bg-label-warning me-1">
                  Cobrança

                @else
                <span class="badge bg-label-danger me-1">
                  Desconhecido
                   </span>
                @endif

            </td>

            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i
                    class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="{{route('editmensagem',$mensagens->id)}}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                  <a class="dropdown-item" href="javascript:void(0);" onclick="confirmDelete('{{ route('deleteMensagem', $mensagens->id) }}')">
                    <i class="bx bx-trash me-1"></i> Deletar
                  </a>

                </div>
              </div>
            </td>
          </tr>
        @endforeach


        </tbody>
      </table>
    </div>
  </div>
  <!-- Modal de Confirmação de Exclusão -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Você tem certeza que deseja excluir esta mensagem? Esta ação não pode ser desfeita.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <form id="deleteForm" method="POST" action="" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Deletar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    function confirmDelete(url) {
      // Define a ação do formulário de exclusão com a URL correta
      document.getElementById('deleteForm').action = url;

      // Exibe o modal de confirmação
      var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
    }
  </script>


@endsection
