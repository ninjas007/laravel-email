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
                                    <h4>Register</h4>
                                </div>

                                <form class="mt-5 mb-5 login-input" method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" class="form-control border px-2" name="name" placeholder="Name" required>

                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control border px-2" name="email" placeholder="Email" required>

                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control border px-2" name="password" placeholder="Password"
                                            required>

                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn login-form__btn submit w-100">Sign up</button>
                                </form>
                                <p class="mt-3 login-form__footer text-center">Have account <a href="{{ route('login') }}"
                                        class="text-primary">Sign in </a> now</p>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
