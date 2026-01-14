@extends('layouts.bootstrap')

@section('title', 'Employee Created Successfully')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Success Message -->
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <h4 class="alert-heading">
                    <i class="bi bi-check-circle-fill me-2"></i>Employee Created Successfully!
                </h4>
                <p class="mb-0">
                    <strong>{{ $employee->employee_name }}</strong> (NIK: {{ $employee->nik }}) has been added to the system.
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- Employee Summary Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-check-fill me-2"></i>Employee Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Full Name:</th>
                                    <td><strong>{{ $employee->employee_name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>NIK:</th>
                                    <td>{{ $employee->nik }}</td>
                                </tr>
                                <tr>
                                    <th>Place of Birth:</th>
                                    <td>{{ $employee->birthplace }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">Date of Birth:</th>
                                    <td>{{ \Carbon\Carbon::parse($employee->birthdate)->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $employee->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4 text-center">
                    <h5 class="mb-3">
                        <i class="bi bi-question-circle-fill text-primary me-2"></i>What would you like to do next?
                    </h5>
                    <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                        <button type="button" class="btn btn-lg btn-primary" id="btnAddContract">
                            <i class="bi bi-file-earmark-plus me-2"></i>Add Employment Contract
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-lg btn-outline-secondary">
                            <i class="bi bi-list-ul me-2"></i>Back to Employee List
                        </a>
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-lg btn-outline-primary">
                            <i class="bi bi-eye me-2"></i>View Employee Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contract Form (Hidden by default) -->
            <div id="contractFormSection" style="display: none;">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>Add Employment Contract for {{ $employee->employee_name }}
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data" id="contractForm">
                            @csrf
                            
                            <!-- Hidden employee_id -->
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            <input type="hidden" name="from_employee_creation" value="1">

                            <!-- Employment Information -->
                            <h6 class="mb-3 text-primary">
                                <i class="bi bi-briefcase me-2"></i>Employment Information
                            </h6>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="TMT_awal" class="form-label fw-semibold">
                                        <i class="bi bi-calendar-event text-primary me-1"></i>TMT Awal (Effective Start Date)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="TMT_awal" 
                                           name="TMT_awal" 
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label for="contract_type" class="form-label fw-semibold">
                                        <i class="bi bi-file-text text-primary me-1"></i>Contract Type
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" 
                                            id="contract_type" 
                                            name="contract_type" 
                                            required>
                                        <option value="">Select Contract Type</option>
                                        <option value="Kontrak">Kontrak (Fixed Term)</option>
                                        <option value="KPP">KPP (Permanent)</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="nomor_kontrak" class="form-label fw-semibold">
                                        <i class="bi bi-hash text-primary me-1"></i>Contract Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nomor_kontrak" 
                                           name="nomor_kontrak" 
                                           placeholder="e.g., CTR-2026-001"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label for="job_position" class="form-label fw-semibold">
                                        <i class="bi bi-person-badge text-primary me-1"></i>Job Position
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="job_position" 
                                           name="job_position" 
                                           placeholder="e.g., Software Engineer"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label for="point_of_hire" class="form-label fw-semibold">
                                        <i class="bi bi-geo-alt text-primary me-1"></i>Point of Hire
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="point_of_hire" 
                                           name="point_of_hire" 
                                           placeholder="e.g., Head Office"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label for="department" class="form-label fw-semibold">
                                        <i class="bi bi-building text-primary me-1"></i>Department
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="department" 
                                           name="department" 
                                           list="departmentList"
                                           placeholder="e.g., IT, HR, Finance"
                                           required>
                                    <datalist id="departmentList">
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept }}">
                                        @endforeach
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label for="work_location" class="form-label fw-semibold">
                                        <i class="bi bi-pin-map text-primary me-1"></i>Work Location
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="work_location" 
                                           name="work_location" 
                                           list="locationList"
                                           placeholder="e.g., Jakarta HQ"
                                           required>
                                    <datalist id="locationList">
                                        @foreach($workLocations as $location)
                                            <option value="{{ $location }}">
                                        @endforeach
                                    </datalist>
                                </div>

                                <div class="col-md-6">
                                    <label for="start_date" class="form-label fw-semibold">
                                        <i class="bi bi-calendar-check text-primary me-1"></i>Start Date
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="start_date" 
                                           name="start_date" 
                                           required>
                                </div>

                                <div class="col-md-6" id="end_date_field">
                                    <label for="end_date" class="form-label fw-semibold">
                                        <i class="bi bi-calendar-x text-primary me-1"></i>End Date
                                        <span class="text-danger" id="end_date_required">*</span>
                                    </label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="end_date" 
                                           name="end_date">
                                    <small class="text-muted">Leave empty for permanent contracts</small>
                                </div>

                                <div class="col-md-12">
                                    <label for="file_contract" class="form-label fw-semibold">
                                        <i class="bi bi-file-pdf text-primary me-1"></i>Contract File (PDF)
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="file_contract" 
                                           name="file_contract" 
                                           accept=".pdf">
                                    <small class="text-muted">Upload contract PDF file (max 5MB)</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-secondary" id="btnCancelContract">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Save Contract
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: 3px solid #FF6B00;
}

.btn-primary {
    background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #002060 0%, #001040 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 61, 165, 0.3);
}

.btn-outline-primary {
    color: #003DA5;
    border-color: #003DA5;
}

.btn-outline-primary:hover {
    background-color: #003DA5;
    border-color: #003DA5;
    transform: translateY(-2px);
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 5px solid #28a745;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAddContract = document.getElementById('btnAddContract');
    const btnCancelContract = document.getElementById('btnCancelContract');
    const contractFormSection = document.getElementById('contractFormSection');
    const contractType = document.getElementById('contract_type');
    const endDateField = document.getElementById('end_date_field');
    const endDateInput = document.getElementById('end_date');
    const endDateRequired = document.getElementById('end_date_required');

    // Show contract form
    btnAddContract.addEventListener('click', function() {
        contractFormSection.style.display = 'block';
        contractFormSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        btnAddContract.style.display = 'none';
    });

    // Cancel contract form
    btnCancelContract.addEventListener('click', function() {
        if (confirm('Are you sure you want to cancel adding the contract?')) {
            contractFormSection.style.display = 'none';
            btnAddContract.style.display = 'inline-block';
            document.getElementById('contractForm').reset();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    // Toggle end date based on contract type
    function toggleEndDate() {
        if (contractType.value === 'KPP') {
            endDateField.style.display = 'none';
            endDateInput.removeAttribute('required');
            endDateInput.value = '';
            endDateRequired.style.display = 'none';
        } else {
            endDateField.style.display = 'block';
            if (contractType.value === 'Kontrak') {
                endDateInput.setAttribute('required', 'required');
                endDateRequired.style.display = 'inline';
            }
        }
    }

    contractType.addEventListener('change', toggleEndDate);
});
</script>
@endsection
