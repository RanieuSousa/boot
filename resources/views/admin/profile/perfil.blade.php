
@extends('layouts/contentNavbarLayout')

@section('title', 'Perfil')

@section('page-script')
  @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')

  <!-- Form controls -->
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif
    <div class="card">
        <form action="{{route('updateprofile')}}", method="post"  enctype="multipart/form-data">
          @csrf
        <h5 class="card-header">Editar Perfil</h5>
        <div class="card-body">
          <div class="mb-4">
            <label for="exampleFormControlInput1" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{{auth()->user()->name}}"  disabled/>
          </div>
          <div class="mb-4">
            <label for="exampleFormControlReadOnlyInput1" class="form-label">Email</label>
            <input class="form-control" type="email" id="email" name="email" value="{{auth()->user()->email}}"  disabled />
          </div>
          <div class="mb-4">
            <label for="exampleFormControlReadOnlyInputPlain1" class="form-label">Photo</label>
            <input type="file" readonly class="form-control-plaintext" id="foto"  name="photo"/>
          </div>

          <button type="submit" class="btn btn-primary me-3">Salvar</button>
        </div>
        </form>
      </div>
    <br>
    <div class="card">
      <form action="{{ route('updatepassword') }}" method="post" onsubmit="return validatePassword()">
        @csrf
        <h5 class="card-header">Editar Senha</h5>
        <div class="card-body">
          <div class="mb-4">
            <label for="senha" class="form-label">Nova Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" placeholder="senha" required />
          </div>
          <div class="mb-4">
            <label for="confirmacao" class="form-label">Confirmação de senha</label>
            <input class="form-control" type="password" id="confirmacao" placeholder="Confirmação de senha" required/>
            <!-- Mensagem de erro que será exibida abaixo do campo -->
            <small id="error-message" class="text-danger" style="display: none;">As senhas não coincidem. Tente novamente.</small>
          </div>
          <button type="submit" class="btn btn-primary me-3">Salvar</button>
        </div>
      </form>
    </div>

  <script>
    function validatePassword() {
      // Obter os valores das senhas
      var senha = document.getElementById('senha').value;
      var confirmacao = document.getElementById('confirmacao').value;
      var errorMessage = document.getElementById('error-message');

      // Verificar se as senhas coincidem
      if (senha !== confirmacao) {
        // Exibir a mensagem de erro
        errorMessage.style.display = 'block'; // Torna a mensagem visível
        return false; // Impede o envio do formulário
      }
      // Se as senhas coincidirem, esconder a mensagem de erro
      errorMessage.style.display = 'none';
      return true; // Permite o envio do formulário
    }
  </script>
@endsection
