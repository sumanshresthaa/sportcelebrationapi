<!DOCTYPE html>
<html>
<head>
    <title>Add Country Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Add Country Info</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('countries.store') }}">
        @csrf
    
        <div class="mb-3">
            <label for="name" class="form-label">Country Name</label>
            <input type="text" class="form-control" name="name" placeholder="e.g. Brazil" required>
        </div>
    
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Details..."></textarea>
        </div>
        <div class="mb-3">
            <label for="flag_url" class="form-label">Flag URL</label>
            <input type="text" class="form-control" id="flag_url" name="flag_url" placeholder="Enter flag image URL" required>
        </div>
    
        <hr>
        <h5>Matches</h5>
        <div id="matches-wrapper">
            <div class="match-group mb-3 border rounded p-3">
                <input type="text" name="matches[0][title]" class="form-control mb-2" placeholder="e.g. Germany vs France" required>
                <input type="date" name="matches[0][date]" class="form-control mb-2" required>
    
                <div class="score-group">
                    <input type="number" name="matches[0][score_germany]" class="form-control mb-1" placeholder="Germany score (optional)">
                    <input type="number" name="matches[0][score_france]" class="form-control mb-1" placeholder="France score (optional)">
                </div>
            </div>
        </div>
    
        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addMatch()">+ Add Match</button>
    
        <button type="submit" class="btn btn-primary">Save Country</button>
    </form>
    
    <script>
        let matchIndex = 1;
    
        function addMatch() {
            const wrapper = document.getElementById('matches-wrapper');
    
            const html = `
            <div class="match-group mb-3 border rounded p-3">
                <input type="text" name="matches[${matchIndex}][title]" class="form-control mb-2" placeholder="e.g. Germany vs France" required>
                <input type="date" name="matches[${matchIndex}][date]" class="form-control mb-2" required>
    
                <div class="score-group">
                    <input type="number" name="matches[${matchIndex}][score_germany]" class="form-control mb-1" placeholder="Germany score (optional)">
                    <input type="number" name="matches[${matchIndex}][score_france]" class="form-control mb-1" placeholder="France score (optional)">
                </div>
            </div>`;
            wrapper.insertAdjacentHTML('beforeend', html);
            matchIndex++;
        }
    </script>
    </body>
</html>
