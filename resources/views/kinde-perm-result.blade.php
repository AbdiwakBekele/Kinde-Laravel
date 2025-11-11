<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Kinde Permission Repro — Result</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        details>summary {
            cursor: pointer;
        }

        pre {
            white-space: pre-wrap;
            word-break: break-word;
        }
    </style>
</head>

<body class="bg-gray-50 p-6">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="bg-white shadow rounded-lg p-5">
            <h1 class="text-2xl font-semibold">Kinde Permission Repro — Result</h1>
            <p class="text-gray-600 mt-1">
                Below is the full trace of each request and response made by the harness.
            </p>
            <div class="mt-4">
                <a href="{{ route('kinde.perm.form') }}" class="text-blue-600 hover:underline">← Back to form</a>
            </div>
        </div>

        @foreach ($log as $step => $payload)
        <div class="bg-white shadow rounded-lg p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">{{ $step }}</h2>

                @php
                // Try to surface status if present
                $status = null;
                if (is_array($payload) && array_key_exists('response', $payload) && is_array($payload['response']))
                {
                $status = $payload['response']['status'] ?? null;
                } elseif (is_array($payload) && isset($payload[0]['response']['status'])) {
                $status = $payload[0]['response']['status'];
                }
                @endphp

                @if ($status)
                <span class="text-sm px-2 py-1 rounded
              {{ $status >= 200 && $status < 300 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    HTTP {{ $status }}
                </span>
                @endif
            </div>

            <div class="mt-4 space-y-3">
                <details open>
                    <summary class="font-medium">Request</summary>
                    <pre
                        class="bg-gray-100 p-3 rounded text-sm">{{ json_encode($payload['request'] ?? $payload['requests'] ?? '—', JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                    </pre>
                </details>

                <details open>
                    <summary class="font-medium">Response</summary>
                    <pre
                        class="bg-gray-100 p-3 rounded text-sm">{{ json_encode($payload['response'] ?? $payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                    </pre>
                </details>

                @if (is_array($payload) && isset($payload[0]) && isset($payload[0]['response']))
                {{-- Handle multi-read blocks like C_read_back_user / E_read_back_after_single --}}
                <details>
                    <summary class="font-medium">Additional Reads</summary>
                    <pre
                        class="bg-gray-100 p-3 rounded text-sm">{{ json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                    </pre>
                </details>
                @endif
            </div>
        </div>
        @endforeach

        <div class="text-sm text-gray-500">
            Tip: If the bulk add by <code>permission_key</code> returns 200 but the reads show no permission,
            try the single-user endpoint with <code>permission_id</code> and compare the reads.
        </div>
    </div>
</body>

</html>