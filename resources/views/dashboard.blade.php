<!DOCTYPE html>
<html>

<head>
    <title>Kinde Org Users</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Add Users to Organization</h1>

        @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
        @endif

        @if (session('kinde_request'))
        <div class="mb-4">
            <h2 class="font-semibold">Request</h2>
            <pre class="bg-gray-100 p-3 rounded">{{ json_encode(session('kinde_request'), JSON_PRETTY_PRINT) }}
            </pre>
        </div>
        <div class="mb-4">
            <h2 class="font-semibold">Response</h2>
            <pre class="bg-gray-100 p-3 rounded">{{ json_encode(session('kinde_response'), JSON_PRETTY_PRINT) }}
            </pre>
        </div>
        @endif

        <form method="POST" action="{{ route('kinde.users.add') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block font-semibold">Org Code</label>
                <input type="text" name="org_code" class="w-full border rounded p-2" placeholder="org_abc123"
                    required>
            </div>
            <div>
                <label class="block font-semibold">User IDs (comma-separated)</label>
                <input type="text" name="user_ids" class="w-full border rounded p-2" placeholder="kp_xxx,kp_yyy"
                    required>
            </div>
            <div>
                <label class="block font-semibold">Roles (comma-separated)</label>
                <input type="text" name="roles" class="w-full border rounded p-2" value="owner">
            </div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Add Users</button>
        </form>

        <div class="mt-8 border-t pt-6">
            <h2 class="text-lg font-bold mb-2">Scenario Tester</h2>
            <form method="POST" action="{{ route('kinde.test.run') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-semibold">Org Code</label>
                    <input type="text" name="org_code" class="w-full border rounded p-2" placeholder="org_abc123"
                        required>
                </div>
                <div>
                    <label class="block font-semibold">User ID</label>
                    <input type="text" name="user_id" class="w-full border rounded p-2" placeholder="kp_xxx">
                </div>
                <div>
                    <label class="block font-semibold">Roles (comma-separated)</label>
                    <input type="text" name="roles" class="w-full border rounded p-2" value="owner">
                </div>
                <div>
                    <label class="block font-semibold">Scenario</label>
                    <select name="scenario" class="w-full border rounded p-2" required>
                        <option value="valid">valid (expect 200 + users_added)</option>
                        <option value="none">none (expect 204)</option>
                        <option value="already_member">already_member (expect 204)</option>
                        <option value="malformed">malformed (expect 400)</option>
                    </select>
                </div>
                <button class="bg-gray-800 text-white px-4 py-2 rounded">Run Scenario</button>
            </form>
        </div>
    </div>

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