<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Kinde User</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <h2>Delete Kinde User</h2>

    <form id="deleteForm">
        <label for="user_id">Kinde User ID:</label>
        <input type="text" id="user_id" name="user_id" required>
        <button type="submit">Delete User</button>
    </form>

    <div id="response"></div>

    <script>
    document.getElementById('deleteForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const userId = document.getElementById('user_id').value.trim();
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!userId) {
            document.getElementById('response').innerHTML =
                `<p style="color:red;">❌ Please enter a user ID</p>`;
            return;
        }

        try {
            const response = await fetch(`/kinde/users/${encodeURIComponent(userId)}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('response').innerHTML =
                    `<p style="color:green;">✅ ${data.message}</p>`;
            } else {
                document.getElementById('response').innerHTML =
                    `<p style="color:red;">❌ ${data.error || 'Unknown error'}</p>`;
            }
        } catch (error) {
            document.getElementById('response').innerHTML =
                `<p style="color:red;">❌ Network error: ${error.message}</p>`;
        }
    });
    </script>
</body>

</html>