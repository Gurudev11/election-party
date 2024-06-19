<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .center-content {
            min-height: 80vh;
        }
        .scroll {
            font-size: 30px;
            padding: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
        }
        .container {
            max-width: 800px;
            padding: 0;
        }
        .dropdown-row {
            display: flex;
            gap: 15px;
            margin-bottom: 0px;
        }
        .dropdown-row select {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 6px;
            background-color: #fff;
            color: #333;
            outline: none;
            transition: border-color 0.3s ease-in-out;
        }
        .dropdown-row select:focus {
            border-color: black;
        }
        .dropdown-row select option {
            padding: 10px;
        }
        .alert-box {
            max-width: 500px; /* width */
            margin: 20px auto; 
            padding: 10px; 
            font-size: 20px;
        }
    </style>
</head>
<body>

    <marquee class="scroll" behavior="scroll" direction="left">
        @foreach ($cityDetails as $detail)
            <span>Leading : {{ $detail['leadingCandidate']->name }} from {{ $detail['leadingCandidate']->party }} in {{ $detail['city'] }} with {{ $detail['leadingCandidate']->votes }} votes ({{ $detail['leadingCandidatePercentage'] }}%)</span> |
        @endforeach
    </marquee>


<form action="{{ route('results.get') }}" method="POST">
    @csrf
    <div class="container">
        <div class="dropdown-row">
            <select id="state" name="state" required>
                <option value="">Select State</option>
                @foreach ($states as $state)
                    <option value="{{ $state->name }}">{{ $state->name }}</option>
                @endforeach
            </select>
            <select id="district" name="district" required>
                <option value="">Select District</option>
            </select>
            <button type="submit" class="btn btn-secondary">Show Results</button>
        </div>
    </div>
</form>

 @if (session('leading'))
    <div class="alert alert-info alert-box" >
    <p>Total Votes in City: {{ session('totalCityVotes') }}</p>  
    <h4> <p> Leading : <span style="color: #bd2c1c;">{{ session('leading')->name }} from {{ session('leading')->party }} with {{ session('leading')->votes }} votes. </span></p>
       
            <p>% of Votes: <span style="color: #bd2c1c;"> {{ session('leadingCandidatePercentage') }}% </span></p>  
        
    </h4>
    </div> 
 @endif
 @if (session('success'))
 <div class="alert alert-info alert-box" >{{ session('success') }}</div>
 @endif

<div class="container center-content d-flex flex-column justify-content-center align-items-center">
    <!-- <h1>Hii</h1> -->
    <button type="button" class="btn btn-info btn-lg my-2" onclick="partyPage()">Party Form</button>
    <button type="button" class="btn btn-info btn-lg my-2" onclick="partyDetails()">Party Details</button>
    <button type="button" class="btn btn-info btn-lg my-2" onclick="candidatePage()">Candidate Form</button>
    <button type="button" class="btn btn-info btn-lg my-2" onclick="candidateDetails()">Candidate Details</button>
    <button type="button" class="btn btn-info btn-lg my-2" onclick="votesPage()">Votes</button>
</div>

<script>
    const states = @json($states);
    const districts = @json($districts);

    function partyPage() {
        window.location.href = '/parties';
    }
    function partyDetails() {
        window.location.href = '/parties/details';
    }
    function candidatePage() {
        window.location.href = '/candidates';
    }
    function candidateDetails() {
        window.location.href = '/candidates/details';
    }
    function votesPage() {
        window.location.href = '/votes';
    }

    document.getElementById('state').addEventListener('change', function() {
        const stateName = this.value;
        const state = states.find(state => state.name === stateName);
        const stateId = state.id;
        const districtDropdown = document.getElementById('district');

        districtDropdown.innerHTML = '<option value="">Select District</option>';

        if (stateId) {
            const filteredDistricts = districts.filter(district => district.state_id == stateId);
            filteredDistricts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.name;
                option.textContent = district.name;
                districtDropdown.appendChild(option);
            });
        }
    });
</script>
</body>
</html>
