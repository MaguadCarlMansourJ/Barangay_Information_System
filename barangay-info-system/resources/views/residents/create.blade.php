@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Resident Profiling</h2>
            <p class="text-muted mb-0">Create a complete resident profile record</p>
        </div>
        <a href="{{ route('residents.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the form.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('residents.store') }}" method="POST">
        @csrf
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Household Assignment</h5>
            </div>
            <div class="card-body">
                <label class="form-label">Household</label>
                <select name="household_id" class="form-select" required>
                    <option value="">Select household</option>
                    @foreach($households as $household)
                        <option value="{{ $household->id }}" {{ old('household_id', request('household_id')) == $household->id ? 'selected' : '' }}>
                            {{ $household->house_number }} - {{ $household->address }}{{ $household->purok ? ' (' . $household->purok->name . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Suffix</label>
                        <input type="text" name="suffix" class="form-control" value="{{ old('suffix') }}" placeholder="Jr., Sr., III">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Place of Birth</label>
                        <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth') }}" placeholder="City/Municipality, Province">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select gender</option>
                            @foreach(['Male', 'Female', 'Other'] as $gender)
                                <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Citizenship</label>
                        <input type="text" name="citizenship" class="form-control" value="{{ old('citizenship', 'Filipino') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Civil Status</label>
                        <select name="civil_status" class="form-select" required>
                            <option value="">Select status</option>
                            @foreach(['Single', 'Married', 'Widowed', 'Separated', 'Divorced'] as $status)
                                <option value="{{ $status }}" {{ old('civil_status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Religion</label>
                        <input type="text" name="religion" class="form-control" value="{{ old('religion') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Educational Attainment</label>
                        <select name="educational_attainment" class="form-select">
                            <option value="">Select level</option>
                            @foreach(['No Formal Education', 'Elementary Level', 'Elementary Graduate', 'High School Level', 'High School Graduate', 'Senior High School Graduate', 'Vocational', 'College Level', 'College Graduate', 'Post Graduate'] as $level)
                                <option value="{{ $level }}" {{ old('educational_attainment') == $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Relationship to Household Head</label>
                        <select name="relationship_to_household_head" class="form-select">
                            <option value="">Select relationship</option>
                            @foreach(['Head', 'Spouse', 'Child', 'Parent', 'Sibling', 'Grandchild', 'Relative', 'Boarder', 'Helper', 'Other'] as $relationship)
                                <option value="{{ $relationship }}" {{ old('relationship_to_household_head') == $relationship ? 'selected' : '' }}>{{ $relationship }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date of Residency</label>
                        <input type="date" name="date_of_residency" class="form-control" value="{{ old('date_of_residency') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Voter and Sector Classification</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="is_registered_voter" value="0">
                            <input class="form-check-input" type="checkbox" name="is_registered_voter" value="1" id="isRegisteredVoter" {{ old('is_registered_voter') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isRegisteredVoter">Registered voter</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Voter Precinct Number</label>
                        <input type="text" name="voter_precinct_number" class="form-control" value="{{ old('voter_precinct_number') }}" placeholder="e.g. 0123A">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_senior_citizen" value="0">
                            <input class="form-check-input" type="checkbox" name="is_senior_citizen" value="1" id="isSeniorCitizen" {{ old('is_senior_citizen') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isSeniorCitizen">Senior citizen</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_pwd" value="0">
                            <input class="form-check-input" type="checkbox" name="is_pwd" value="1" id="isPwd" {{ old('is_pwd') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPwd">Person with disability</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">PWD ID Number</label>
                        <input type="text" name="pwd_id_number" class="form-control" value="{{ old('pwd_id_number') }}">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_solo_parent" value="0">
                            <input class="form-check-input" type="checkbox" name="is_solo_parent" value="1" id="isSoloParent" {{ old('is_solo_parent') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isSoloParent">Solo parent</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Solo Parent ID Number</label>
                        <input type="text" name="solo_parent_id_number" class="form-control" value="{{ old('solo_parent_id_number') }}">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_4ps_beneficiary" value="0">
                            <input class="form-check-input" type="checkbox" name="is_4ps_beneficiary" value="1" id="is4psBeneficiary" {{ old('is_4ps_beneficiary') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is4psBeneficiary">4Ps beneficiary</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-switch mt-4">
                            <input type="hidden" name="is_indigenous_person" value="0">
                            <input class="form-check-input" type="checkbox" name="is_indigenous_person" value="1" id="isIndigenousPerson" {{ old('is_indigenous_person') ? 'checked' : '' }}>
                            <label class="form-check-label" for="isIndigenousPerson">Indigenous person</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Contact and Identification</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">PhilHealth ID</label>
                        <input type="text" name="philhealth_id" class="form-control" value="{{ old('philhealth_id') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('residents.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>Save Profile
            </button>
        </div>
    </form>
</div>
@endsection
