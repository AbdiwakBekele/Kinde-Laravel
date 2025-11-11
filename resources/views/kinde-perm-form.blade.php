<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Kinde Permission Repro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-xl mx-auto bg-white shadow p-6 rounded-lg space-y-4">
        <h1 class="text-xl font-bold">Reproduce: Assign Permission to User with Zero Permissions</h1>

        @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('kinde.perm.run') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium">Org Code</label>
                <input class="border rounded w-full p-2" type="text" name="org_code" required
                    placeholder="org_abc123">
            </div>

            <div>
                <label class="block text-sm font-medium">Kinde User ID</label>
                <input class="border rounded w-full p-2" type="text" name="user_id" required placeholder="kp_75...">
            </div>

            <div>
                <label class="block text-sm font-medium">Permission Key (for bulk)</label>
                <input class="border rounded w-full p-2" type="text" name="permission_key" required
                    placeholder="read:stuff">
            </div>

            <div>
                <label class="block text-sm font-medium">Permission ID (for single endpoint, optional)</label>
                <input class="border rounded w-full p-2" type="text" name="permission_id" placeholder="perm_123...">
                <p class="text-xs text-gray-500">If set, the tool will also add the permission using the single
                    endpoint by ID.</p>
            </div>

            <div>
                <label class="block text-sm font-medium">HTTP Method for Bulk Endpoint</label>
                <select class="border rounded w-full p-2" name="method" required>
                    <option value="PATCH">PATCH (update)</option>
                    <option value="POST">POST (add)</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Run Repro</button>
        </form>
    </div>
</body>

</html>