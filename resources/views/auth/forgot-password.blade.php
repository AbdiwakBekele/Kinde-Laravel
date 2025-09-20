<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Your Password</title>
</head>

<body>

    <div class="max-w-md mx-auto p-6 bg-white rounded-xl shadow">
        <h1 class="text-xl font-semibold mb-4">Forgot your password?</h1>

        @if (session('status'))
        <div class="mb-4 p-3 rounded bg-green-50 text-green-700">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror" required autofocus>
            @error('email')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror

            <button type="submit" class="mt-4 w-full bg-black text-white py-2 rounded">
                Send reset link
            </button>
        </form>
    </div>
</body>

</html>