<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votes Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div class="container mt-5">
    <h1>Votes</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('leading'))
        <div class="alert alert-info">
            <h4>Leading</h4>
            <p>{{ session('leading')->name }} from {{ session('leading')->party }} with {{ session('leading')->votes }} votes.</p>
            @if (session('leadingCandidatePercentage'))
                <p>Percentage of Votes: {{ session('leadingCandidatePercentage') }}%</p>
            @endif
        </div>
    @endif

    <form action="{{ route('results.check') }}" method="POST" id="votesForm">
        @csrf
        <div class="form-group">
            <label for="party">Party</label><span class="text-danger">*</span></label>
            <select class="form-control" id="party" name="party" required>
                <option value="">Select Party</option>
                @foreach ($parties as $party)
                    <option value="{{ $party->name }}">{{ $party->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="state">State</label><span class="text-danger">*</span></label>
            <select class="form-control" id="state" name="state" required>
                <option value="">Select State</option>
            </select>
        </div>
        <div class="form-group">
            <label for="city">City</label><span class="text-danger">*</span></label>
            <select class="form-control" id="city" name="city" required>
                <option value="">Select City</option>
            </select>
        </div>
        <div class="form-group">
            <label for="candidate">Candidate</label></label>
            <input type="text" class="form-control" id="candidate" name="candidate" readonly>
        </div>
        <div class="form-group">
            <label for="votes">Counted votes</label><span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="votes" name="votes" required min="1">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<script>
    const candidates = @json($candidates);
    const states = @json($states);
    const districts = @json($districts);

    document.getElementById('party').addEventListener('change', function() {
        const partyName = this.value;
        const partyCandidates = candidates.filter(candidate => candidate.party === partyName);
        if (partyCandidates.length === 0) {
            alert('No candidates enrolled from this party');
        }
        const stateDropdown = document.getElementById('state');
        stateDropdown.innerHTML = '<option value="">Select State</option>';

        const uniqueStates = [...new Set(partyCandidates.map(candidate => candidate.address.state))];
        uniqueStates.forEach(state => {
            const option = document.createElement('option');
            option.value = state;
            option.textContent = state;
            stateDropdown.appendChild(option);
        });
       
        document.getElementById('city').innerHTML = '<option value="">Select City</option>';
        document.getElementById('candidate').value = '';
    });

    document.getElementById('state').addEventListener('change', function() {
        const stateName = this.value;
        const partyName = document.getElementById('party').value;
        const partyCandidates = candidates.filter(candidate => candidate.party === partyName && candidate.address.state === stateName);

        const cityDropdown = document.getElementById('city');
        cityDropdown.innerHTML = '<option value="">Select City</option>';

        const uniqueCities = [...new Set(partyCandidates.map(candidate => candidate.address.city))];
        uniqueCities.forEach(city => {
            const option = document.createElement('option');
            option.value = city;
            option.textContent = city;
            cityDropdown.appendChild(option);
        });

        document.getElementById('candidate').value = '';
    });

    document.getElementById('city').addEventListener('change', function() {
        const cityName = this.value;
        const stateName = document.getElementById('state').value;
        const partyName = document.getElementById('party').value;
        const partyCandidate = candidates.find(candidate => candidate.party === partyName && candidate.address.state === stateName && candidate.address.city === cityName);

        // if (partyCandidate) {
            document.getElementById('candidate').value = partyCandidate.name;
        // } 
    });

    var inputField = document.querySelector('#votes');
    inputField.onkeydown = function(event) {
        // Only allow if the e.key value is a number or if it's 'Backspace'
        if(isNaN(event.key) && event.key !== 'Backspace') {
            event.preventDefault();
        }
    };
</script>
</body>
</html>
