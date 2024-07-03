<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!---<link rel="icon" type="image/png" href="{{asset('admin/img/logos/logo.png')}}">-->
    <title>seagull international Jobs || login</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>

<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');

*{
    box-sizing: border-box;
}
:root{
    --first-color: #e7eaf6;
    --second-color: #a2a8d3;
    --third-color: #38598b;
    --fourth-color: #113f67;
    --input-bgcolor: #f2f2f2;
}


body{
    font-family: 'Poppins', sans-serif;
    background-image: url(https://shankarodathkovilakam.com/admin/admin/img/bgtilepattern.png)!important;
    background-repeat: repeat!important;
    background-position: bottom!important;
}
.login-form a {
    font-size: 14px;
    color: #000;
    text-decoration: none;
}
.login-form {
    border: 1px solid #1111;
    padding: 10px;
}
.login-page {
    width: 500px;
    padding: 9% 0 0;
    margin: 0px auto;
}
.form {
    position: relative;
    z-index: 1;
    background: #FFFFFF;
    max-width: 450px;
    margin: 0 auto 100px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.1), 0 5px 5px 0 rgba(0, 0, 0, 0.2);
}
.form input {
    outline: 0;
    background: var(--input-bgcolor);
    width: 100%;
    border: 0;
    margin: 0 0 15px;
    padding: 0.8rem;
    font-size: 0.8rem;
    border-radius: 5px;
}
.form button {
    text-transform: uppercase;
    outline: 0;
    background: #c03501 !important;
    width: 100%;
    border: 0;
    padding: 8px;
    color: #FFFFFF;
    font-size: 0.9rem;
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
    cursor: pointer;
    border-radius: 5px;
}
.form button:hover,.form button:active,.form button:focus {
    background: var(--third-color);
}
.form .message {
    margin: 15px 0 0;
    color: var(--second-color);
    font-size: 0.8rem;
}
.form .message a {
    color: var(--third-color);
    text-decoration: none;
}
.form .register-form {
    display: none;
}
.form-check .form-check-label {
    font-size: 13px;
    padding-top: 7px;
    padding-left: 5px;
}
.form-check input {
    outline: 0;
    background: var(--input-bgcolor);
    width: fit-content;
    border: 0;
    margin: 9px 0 0px;
    padding: 1rem;
    font-size: 0.9rem;
}
.invalid-feedback strong{
    font-size: 0.9rem;
}
.login-form img{

}


@media (max-width: 575.98px) {
    .login-form img {
        height: 125px;
        margin-bottom: 1rem;
    }

}
</style>