@extends('layouts/contentNavbarLayout')

@section('title', 'Ciclus')

@section('content')
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  <a href="{{route('createciclus')}}">
  <button type="submit" class="btn btn-primary me-3">Novo</button>
  </a>
  <br>
  <br>
  <div class="card">
    <h5 class="card-header">Table Basic</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
        <tr>
          <th>mensagem</th>
          <th>Dias</th>
          <th>Ação</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0">
        @foreach($ciclus as $ciclus)
        <tr>
          <td><span class="badge bg-label-primary me-1">{{$ciclus->titulo}}</span></td>
          <td><span class="badge bg-label-primary me-1">{{$ciclus->dias}}</span></td>
          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{route('editciculos',$ciclus->id)}}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                <a class="dropdown-item" href="javascript:void(0);"
                   data-bs-toggle="modal"
                   data-bs-target="#deleteModal"
                   onclick="setDeleteAction('{{ route('deleteciclus', $ciclus->id) }}')">
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

  <!-- Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirmar Deleção</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Tem certeza de que deseja deletar este item?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Deletar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    function setDeleteAction(actionUrl) {
      const deleteForm = document.getElementById('deleteForm');
      deleteForm.action = actionUrl;
    }
  </script>
  <!--/ Basic Bootstrap Table -->

@endsection
