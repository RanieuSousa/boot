@extends('layouts/contentNavbarLayout')

@section('title', 'Usuários')

@section('content')
  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  <div class="mt-6">
    <a href="{{route('createusers')}}"><button type="submit" class="btn btn-primary me-3">Novo Usuário</button></a>
  </div>
  <br>
    <div class="card">
    <h5 class="card-header">Usuários</h5>
    <div class="table-responsive text-nowrap">
      <table class="table" >
        <thead>
        <tr>
          <th>Foto</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Grupos</th>
          <th>Empreas</th>
          <th>Tipo</th>
          <th>Ação</th>
        </tr>
        </thead>
        <tbody class="table-border-bottom-0">
        @foreach($users as $users)
        <tr>
          <td>
            <img src="{{ asset('storage/' . $users->photo) }}" alt="Foto do perfil" class="w-px-40 h-auto rounded-circle">

          </td>
          <td>{{$users->user_name}}</td>
          <td>{{$users-> user_email}}</td>
          <td>
            @php $count = 0; @endphp
            @foreach(explode(',', $users->grupos) as $grupo)
              {{ $grupo }}
              @php $count++; @endphp
              @if($count % 3 == 0)
                <br>
              @endif
            @endforeach
          </td>
          <td>
            @php $count = 0; @endphp
            @foreach(explode(',', $users->empresas) as $empresa)
              {{ $empresa }}
              @php $count++; @endphp
              @if($count % 3 == 0)
                <br>
              @endif
            @endforeach
          </td>
          <td>
            @switch($users->type)
              @case(1)
               <samp class="bg-label-success">Vendedor</samp>
                @break
              @default
                <samp class="bg-label-primary">Normal</samp>
            @endswitch
          </td>


          <td>
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{route('editusers',$users->user_id)}}"><i class="bx bx-edit-alt me-2"></i> Editar</a>
                <a class="dropdown-item btn-delete" href="javascript:void(0);" data-user-id="{{ $users->user_id }}">
                  <i class="bx bx-trash me-2"></i> Deletar
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

  <!-- Modal de Confirmação -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Deletar Usuário</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          Você tem certeza de que deseja deletar este usuário?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <form id="deleteForm" action="" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Deletar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Script para abrir o modal e configurar o formulário de exclusão
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
      button.addEventListener('click', function(event) {
        const userId = event.target.getAttribute('data-user-id');
        const deleteUrl = `/users/${userId}`;

        // Atualiza a ação do formulário com a URL para deletar o usuário
        document.getElementById('deleteForm').action = deleteUrl;

        // Abre o modal
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
      });
    });
  </script>


@endsection
