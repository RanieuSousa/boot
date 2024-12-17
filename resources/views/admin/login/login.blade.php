@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <img src="{{ asset('assets/img/malpha.png') }}" alt="Logo" width="50%" style="margin-left: 25%;">
            </a>
          </div>


          <!-- /Logo -->
          <h4 class="mb-1 text-center">Bem-vindo  👋</h4>

          <form id="formAuthentication" class="mb-6" action="{{ route('admin.auth.login') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="email" class="form-label">Email </label>
              <input type="text" class="form-control" id="email" name="email-username" placeholder="Email" autofocus>
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Senha</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-8">
              <div class="d-flex justify-content-between mt-8">
                <a href="{{url('auth/forgot-password-basic')}}">
                  <span>Esqueceu a Senha?</span>
                </a>
              </div>
            </div>
            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Entrar</button>
            </div>
          </form>



        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
@endsection
