@extends('auth.template')

@section('content')
    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <div class="text-center">
                                    <h4>Login</h4>
                                </div>

                                <form class="mt-5 mb-5 login-input" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" placeholder="Email">

                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" placeholder="Password">

                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button class="btn login-form__btn submit w-100">Sign In</button>
                                </form>
                                <p class="mt-3 login-form__footer text-center">Dont have account? <a
                                        href="{{ route('register') }}" class="text-primary">Sign Up</a> now</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
