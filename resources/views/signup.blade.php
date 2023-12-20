<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sign Up</title>

    <!-- Styles -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css'>
    <link rel="stylesheet" href='{{ mix('css/signup.css') }}'>
{{--    <script src="{{ mix('js/signup/index.js') }}" defer></script>--}}
    @vite(['resources/js/signup/index.js'])
</head>

<body>
<img src="{{ asset('images/passifi-logo.png') }}" alt="passifi-logo" url="" class="passifi-logo">
<cont>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="/signup" method="post">
                @csrf
                @method('post')
                <h1>Create Account</h1>
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div role="alert" class="alert alert-error">
                            <span>{{$error}}</span>
                        </div>
                    @endforeach
                @endif
                <input required name="name" type="text" placeholder="Name" value="{{ old('name') }}"/>
                <input required name="email" type="email" placeholder="Email" value="{{ old('email') }}"/>
                <input required name="password" type="password" placeholder="Password"/>
                <input required type="password" placeholder="Confirm Password"/>
                <button type="submit">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="/login" method="post">
                @csrf
                @method('post')
                <h1>Sign in</h1>
                @error('email')
                <div role="alert" class="alert alert-error">
                    <span>{{$message}}</span>
                </div>
                @enderror
                <input required name="email" type="email" placeholder="Email" value="{{old("email")}}"/>
                <input required name="password" type="password" placeholder="Password"/>
                <a href="#">Forgot your password?</a>
                <button type="submit">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Already have an Account?</h1>
                    <p>To keep connected with us, please log in.</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Don't have an Account?</h1>
                    <p>Register now then passifi!</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
</cont>
</body>

</html>
