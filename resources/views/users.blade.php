<!DOCTYPE html>
<html>
<head>
    <title>Random Users</title>
</head>
<body>
    <h1>Random Users</h1>

    <form method="GET" action="/users">
        <label for="gender">Filter by gender:</label>
        <select name="gender" onchange="this.form.submit()">
            <option value="">All</option>
            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
        </select>
    </form>

    <a href="{{ route('users.export', request()->query()) }}">Export to CSV</a>

    @if(isset($error))
        <p style="color:red">{{ $error }}</p>
    @else
        <table border="1" width="80%">
            <thead>
                <tr>
                    <th>Name</th><th>Email</th><th>Gender</th><th>Nationality</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user['name']['first'] }} {{ $user['name']['last'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ ucfirst($user['gender']) }}</td>
                        <td>{{ $user['nat'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->withQueryString()->links() }}
    @endif
</body>
</html>
