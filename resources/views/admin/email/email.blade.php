@extends('layouts/contentNavbarLayout')

@section('title', 'Configuração de Email')

@section('page-script')
  @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card">
    <h5 class="card-header">Configuração de Email</h5>
    <form action="{{ route('storeEmailConfig') }}" method="post">
      @csrf
      <div class="card-body">
        <div class="mb-4">
          <label for="mail_mailer" class="form-label">Mailer</label>
          <input type="text" class="form-control" id="mail_mailer" name="MAIL_MAILER"
                 value="{{ $mailConfig['MAIL_MAILER'] }}" required />
        </div>
        <div class="mb-4">
          <label for="mail_host" class="form-label">Host</label>
          <input type="text" class="form-control" id="mail_host" name="MAIL_HOST"
                 value="{{ $mailConfig['MAIL_HOST'] }}" required />
        </div>
        <div class="mb-4">
          <label for="mail_port" class="form-label">Porta</label>
          <input type="number" class="form-control" id="mail_port" name="MAIL_PORT"
                 value="{{ $mailConfig['MAIL_PORT'] }}" required />
        </div>
        <div class="mb-4">
          <label for="mail_username" class="form-label">Usuário</label>
          <input type="text" class="form-control" id="mail_username" name="MAIL_USERNAME"
                 value="{{ $mailConfig['MAIL_USERNAME'] }}" required />
        </div>
        <div class="mb-4">
          <label for="mail_password" class="form-label">Senha</label>
          <input type="password" class="form-control" id="mail_password" name="MAIL_PASSWORD"
                 value="{{ $mailConfig['MAIL_PASSWORD'] }}" required />
        </div>
        <div class="mb-4">
          <label for="mail_from_address" class="form-label">Email</label>
          <input type="email" class="form-control" id="mail_from_address" name="MAIL_FROM_ADDRESS"
                 value="{{ $mailConfig['MAIL_FROM_ADDRESS'] }}" required />
        </div>
        <div class="mb-4">
          <label for="mail_from_name" class="form-label">Nome</label>
          <input type="text" class="form-control" id="mail_from_name" name="MAIL_FROM_NAME"
                 value="{{ $mailConfig['MAIL_FROM_NAME'] }}" required />
        </div>
        <div class="mb-4">
          <label for="mail_encryption" class="form-label">Encriptação</label>
          <select class="form-select" id="mail_encryption" name="MAIL_ENCRYPTION" required>
            <option value="ssl" {{ $mailConfig['MAIL_ENCRYPTION'] == 'ssl' ? 'selected' : '' }}>SSL</option>
            <option value="tls" {{ $mailConfig['MAIL_ENCRYPTION'] == 'tls' ? 'selected' : '' }}>TLS</option>
            <option value="" {{ $mailConfig['MAIL_ENCRYPTION'] == '' ? 'selected' : '' }}>Nenhuma</option>
          </select>
        </div>
        <div class="mt-4">
          <button type="submit" class="btn btn-primary me-3">Salvar</button>
          <a href="{{ route('showMailConfig') }}" class="btn btn-warning">Cancelar</a>
        </div>
      </div>
    </form>
  </div>
@endsection
