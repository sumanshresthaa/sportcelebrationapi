<!DOCTYPE html>
<html>
<head>
    <title>All Countries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>All Countries</h2>

    <a href="{{ route('countries.create') }}" class="btn btn-success mb-3">Add New Country</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Country Name</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($countries as $country)
                <tr>
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
