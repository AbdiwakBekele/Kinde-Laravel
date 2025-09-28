<!DOCTYPE html>
<html>

<head>
    <title>Kinde Org User Test Result</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white shadow p-6 rounded-lg">
        <h1 class="text-xl font-bold mb-4">Test Result</h1>

        <div class="mb-6">
            <h2 class="font-semibold">Request</h2>
            <pre class="bg-gray-100 p-3 rounded">{{ json_encode($requestData, JSON_PRETTY_PRINT) }}</pre>
        </div>

        <div>
            <h2 class="font-semibold">Response</h2>
            <pre class="bg-gray-100 p-3 rounded">{{ json_encode($responseData, JSON_PRETTY_PRINT) }}</pre>
        </div>

        <div class="mt-4">
            <a href="{{ url('kinde/test/form') }}" class="text-blue-600">Back to form</a>
        </div>
    </div>
</body>

</html>