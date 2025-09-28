<!DOCTYPE html>
<html>

<head>
    <title>Kinde Org User Test</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white shadow p-6 rounded-lg">
        <h1 class="text-xl font-bold mb-4">Test Add User to Org</h1>

        @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('kinde.test.run') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block font-semibold">Org Code:</label>
                <input type="text" name="org_code" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-semibold">User ID (for valid/ already_member):</label>
                <input type="text" name="user_id" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-semibold">Scenario:</label>
                <select name="scenario" class="w-full border rounded p-2" required>
                    <option value="valid">Valid (add user)</option>
                    <option value="none">None (empty array)</option>
                    <option value="already_member">Already Member</option>
                    <option value="malformed">Malformed Body</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Run Test
            </button>
        </form>
    </div>
</body>

</html>