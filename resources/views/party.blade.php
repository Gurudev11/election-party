
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Party Page</title>
   
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .butn {
            margin-bottom: 20px;
        }
        .img-placeholder {
            display: block;
            margin-bottom: 20px;
            max-width: 150px;
        }
    </style>
</head>
<body>
  
    <div class="container mt-5">
        <h1>{{ isset($partyToEdit->id) ? 'Update Party' : 'Party Registration' }}</h1> 
        
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

        @if (isset($partyToEdit->id))
            <form id="party-form" action="{{ route('party.update', $partyToEdit->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
        @else
            <form id="party-form" action="{{ route('parties.save') }}" method="POST" enctype="multipart/form-data">
        @endif

            @csrf
            <div class="form-group">
                <label for="name">Party Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', isset($partyToEdit->id) ? $partyToEdit->name : '') }}" required>
            </div>

            <div class="form-group">
                <label for="registration_date">Registration Date<span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="registration_date" name="registration_date" value="{{ old('registration_date', isset($partyToEdit->id) ? $partyToEdit->registration_date : '') }}" required>
            </div>

            <div class="form-group">
                <label for="address1">Address Line1<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="address1" name="address1" value="{{ old('address1', isset($partyToEdit->id) ? $partyToEdit->address->address1 : '') }}" required>
            </div>

            <div class="form-group">
                <label for="address2">Address Line2</label>
                <input type="text" class="form-control" id="address2" name="address2" value="{{ old('address2', isset($partyToEdit->id) ? ($partyToEdit->address ? $partyToEdit->address->address2 : '') : '') }}">
            </div>

            <div class="form-group">
                <label for="state">State<span class="text-danger">*</span></label>
                <select class="form-control" id="state" name="state" required>
                    <option value="">{{ old('state', isset($partyToEdit->id) ? $partyToEdit->address->state : 'Select State') }}</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->name }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="city">City<span class="text-danger">*</span></label>
                <select class="form-control" id="city" name="city" required>
                    <option value="">{{ old('city', isset($partyToEdit->id) ? $partyToEdit->address->city : 'Select City') }}</option>
                </select>
            </div> 
        
            <div class="form-group">
                <label for="number">Mobile Number<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="number" name="number" value="{{ old('number', isset($partyToEdit->id) ? $partyToEdit->number : '') }}" required minlength="10" maxlength="10" min="6000000000" max="9999999999">
            </div>

            <div class="form-group">
                <label for="party_logo">Upload Logo<span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="party_logo" name="party_logo" accept="image/*" onchange="validateImageFile(this)" {{ isset($partyToEdit->party_logo) ? '' : 'required' }}>
                @if (isset($partyToEdit->party_logo))
                    <img id="current-photo" src="{{ asset('storage/' . $partyToEdit->party_logo) }}" class="img-thumbnail img-placeholder" alt="Current Party Logo">
                @endif
            </div>
            
            <button type="submit" class="btn btn-primary butn">{{ isset($partyToEdit->id) ? 'Update' : 'Save' }}</button>
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
            if (isNaN(event.key) && event.key !== 'Backspace') {
                event.preventDefault();
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const existingPartyNames = @json($existingPartyNames);
            const nameInput = document.getElementById('name');

            nameInput.addEventListener('input', function() {
                const name = nameInput.value.trim().toLowerCase();
                if (existingPartyNames.map(n => n.toLowerCase()).includes(name)) {
                    alert("The party name already exists. Please use a different name");
                }
            });

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
