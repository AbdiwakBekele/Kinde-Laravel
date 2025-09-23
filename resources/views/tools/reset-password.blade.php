<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <div class="max-w-xl mx-auto mt-10">
        <h1 class="text-xl font-semibold mb-4">Kinde Password Reset Repro</h1>

        {{-- flash messages --}}
        @if(session('status'))
        <div class="p-3 mb-4 rounded bg-green-100 text-green-800">
            {{ session('status') }}
        </div>
        @endif
        @if(session('error'))
        <div class="p-3 mb-4 rounded bg-red-100 text-red-800">
            {{ session('error') }}
        </div>
        @endif

        {{-- TEMPORARY PASSWORD FORM --}}
        <form method="POST" action="{{ route('tools.reset.set') }}" class="mb-6 border rounded p-4">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium">Kinde User ID</label>
                <input name="user_id" type="text" required class="w-full border rounded px-3 py-2"
                    placeholder="kp_xxx..." />
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Password (optional)</label>
                <input name="password" type="text" class="w-full border rounded px-3 py-2"
                    placeholder="defaults to TempPassw0rd!" />
            </div>
            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">
                Set Temporary Password
            </button>
            <p class="text-xs text-gray-600 mt-2">
                Calls <code>PUT /api/v1/users/{id}/password</code> with <code>is_temporary_password=true</code>.
            </p>
        </form>

        {{-- PERMANENT PASSWORD FORM --}}
        <form method="POST" action="{{ route('tools.reset.set_permanent') }}" class="mb-6 border rounded p-4">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium">Kinde User ID</label>
                <input name="user_id" type="text" required class="w-full border rounded px-3 py-2"
                    placeholder="kp_xxx..." />
            </div>
            <div class="mb-3">
                <label class="block text-sm font-medium">Password (optional)</label>
                <input name="password" type="text" class="w-full border rounded px-3 py-2"
                    placeholder="defaults to PermPassw0rd!" />
            </div>
            <button type="submit" class="px-4 py-2 rounded bg-purple-600 text-white">
                Set Permanent Password
            </button>
            <p class="text-xs text-gray-600 mt-2">
                Calls <code>PUT /api/v1/users/{id}/password</code> with <code>is_temporary_password=false</code>.
            </p>
        </form>

        {{-- REQUEST RESET FORM --}}
        <form method="POST" action="{{ route('tools.reset.request') }}" class="border rounded p-4">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium">Kinde User ID</label>
                <input name="user_id" type="text" required class="w-full border rounded px-3 py-2"
                    placeholder="same ID as above" />
            </div>
            <button type="submit" class="px-4 py-2 rounded bg-amber-600 text-white">
                Request Reset
            </button>
            <p class="text-xs text-gray-600 mt-2">
                Calls <code>PATCH /api/v1/user</code> with <code>is_password_reset_requested=true</code>.
            </p>
        </form>
    </div>


</body>

</html>