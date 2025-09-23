<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    This is Dashboard

    <br>
    <a href="{{ url('/delete-kinde-user') }}">Delete Users</a><br>
    <a href="{{ url('/forgot-password') }}">Forget Password</a>
    <br>

    <a href="{{ route('tools.reset.form') }}" class="inline-block px-3 py-2 rounded bg-gray-800 text-white">
        Password Reset Repro
    </a>
    <br>
    <a href="{{ url('/logout') }}">Logout</a>
</body>

</html>