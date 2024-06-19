
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style>
        .butn {
            margin-bottom: 20px;
        }
        .img-placeholder {
            display: none;
            margin-bottom: 20px;
            max-width: 150px;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1>{{ isset($candidateToEdit->id) ? 'Update Candidate' : 'Candidate Registration' }}</h1>
        
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

        @if (isset($candidateToEdit->id))
            <form id="candidate-form" action="{{ route('candidate.update', $candidateToEdit->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
        @else
            <form id="candidate-form" action="{{ route('candidates.save') }}" method="POST" enctype="multipart/form-data"> 
        @endif

            @csrf
            <div class="form-group">
                <label for="name">Candidate Name</label><span class="text-danger">*</span>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', isset($candidateToEdit->id) ? $candidateToEdit->name : '') }}" required>
            </div>

            <div class="form-group">
                <label for="party">Party</label><span class="text-danger">*</span>
                <select class="form-control" id="party" name="party" required>
                   <option value="">{{ old('party', isset($candidateToEdit->id) ? $candidateToEdit->party : 'Select Party') }}</option>
                   @foreach ($parties as $party)
                       <option value="{{ $party->name }}">{{ $party->name }}</option>
                   @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="dob">DOB</label><span class="text-danger">*</span>
                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', isset($candidateToEdit->id) ? $candidateToEdit->dob : '') }}" required>
            </div>

            <div class="form-group">
                <label for="address1">Address Line1</label><span class="text-danger">*</span>
                <input type="text" class="form-control" id="address1" name="address1" value="{{ old('address1', isset($candidateToEdit->id) ? $candidateToEdit->address->address1 : '') }}" required>
            </div>

            <div class="form-group">
                <label for="address2">Address Line2</label>
                <input type="text" class="form-control" id="address2" name="address2" value="{{ old('address2', isset($candidateToEdit->id) ? ($candidateToEdit->address ? $candidateToEdit->address->address2 : '') : '') }}">
            </div>

            <div class="form-group">
                <label for="state">State</label><span class="text-danger">*</span>
                <select class="form-control" id="state" name="state" required>
                    <option value="">{{ old('state', isset($candidateToEdit->id) ? $candidateToEdit->address->state : 'Select State') }}</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->name }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="city">City</label><span class="text-danger">*</span>
                <select class="form-control" id="city" name="city" required>
                    <option value="">{{ old('city', isset($candidateToEdit->id) ? $candidateToEdit->address->city : 'Select City') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="number">Mobile Number</label><span class="text-danger">*</span>
                <input type="text" class="form-control" id="number" name="number" value="{{ old('number', isset($candidateToEdit->id) ? $candidateToEdit->number : '') }}" required minlength="10" maxlength="10" min="6000000000" max="9999999999">
            </div>

            <div class="form-group">
                <label for="candidate_photo">Upload Photo</label><span class="text-danger">*</span>
                <input type="file" class="form-control" id="candidate_photo" name="candidate_photo" accept="image/*" onchange="validateImageFile(this)" required>
                @if (isset($candidateToEdit->candidate_photo))
                    <img id="current-photo" src="{{ asset('storage/' . $candidateToEdit->candidate_photo) }}" class="img-thumbnail img-placeholder" alt="Current Candidate Photo">
                @endif
            </div>

            <button type="submit" class="btn btn-primary butn">{{ isset($candidateToEdit->id) ? 'Update' : 'Save' }}</button>
        </form>              
    </div>

    <script>
        const candidates = @json($candidates);
        const parties = @json($parties); 

        const states = @json($states);
        const districts = @json($districts);
        var inputField = document.querySelector('#number');

        inputField.onkeydown = function(event) {
            // Only allow if the e.key value is a number or if it's 'Backspace'
            if(isNaN(event.key) && event.key !== 'Backspace') {
                event.preventDefault();
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const existingCandidates = @json($existingCandidates);
            const partyInput = document.getElementById('party');
            const cityInput = document.getElementById('city');

            function checkDuplicateCandidate() {
                const party = partyInput.value.trim().toLowerCase();
                const city = cityInput.value.trim().toLowerCase();

                if (existingCandidates.some(candidate => candidate.party.toLowerCase() === party && candidate.city.toLowerCase() === city)) {
                    alert('A candidate from this city already exists for the selected party. Please use a different city or party.');
                }
            }

            partyInput.addEventListener('input', checkDuplicateCandidate);
            cityInput.addEventListener('input', checkDuplicateCandidate);

            // Display the current photo if it exists
            const currentPhoto = document.getElementById('current-photo');
            if (currentPhoto && currentPhoto.src) {
                currentPhoto.style.display = 'block';
            }
        });

        function validateImageFile(input) {
            const file = input.files[0];
            const fileType = file['type'];
            const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];

            if (!validImageTypes.includes(fileType)) {
                alert('Please upload a valid image file (JPEG, PNG, JPG, GIF, SVG).');
                input.value = '';
            }
        }

        document.getElementById('state').addEventListener('change', function() {
            const stateName = this.value;
            const state = states.find(state => state.name === stateName);
            const stateId = state.id;
            const districtDropdown = document.getElementById('city');

            districtDropdown.innerHTML = '<option value="">Select City</option>';

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
