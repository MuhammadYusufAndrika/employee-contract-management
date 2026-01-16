@extends('layouts.bootstrap')

@section('title', 'Add New Employee')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-1">
                        <i class="bi bi-person-plus-fill me-2"></i>Add New Employee
                    </h2>
                    <p class="text-muted mb-0">Enter employee personal information</p>
                </div>
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Success Alert (Hidden by default) -->
            <div id="successAlert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
                <h5 class="alert-heading">
                    <i class="bi bi-check-circle-fill me-2"></i>Employee Created Successfully!
                </h5>
                <p id="successMessage" class="mb-0"></p>
            </div>

            <!-- Employee Form Card -->
            <div class="card shadow-sm border-0" id="employeeFormCard">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-person me-2"></i>Personal Information Form
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div id="formErrors" class="alert alert-danger" style="display: none;">
                        <h6 class="alert-heading">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Please fix the following errors:
                        </h6>
                        <ul id="errorList" class="mb-0"></ul>
                    </div>

                    <form id="employeeForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information Section -->
                        <div class="row g-3">
                            <!-- Employee Name -->
                            <div class="col-md-6">
                                <label for="employee_name" class="form-label fw-semibold">
                                    <i class="bi bi-person-fill text-primary me-1"></i>Full Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="employee_name" 
                                       name="employee_name" 
                                       placeholder="Enter full name"
                                       required>
                            </div>

                            <!-- NIK -->
                            <div class="col-md-6">
                                <label for="nik" class="form-label fw-semibold">
                                    <i class="bi bi-credit-card-2-front text-primary me-1"></i>NIK (ID Number) <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nik" 
                                       name="nik" 
                                       placeholder="Enter NIK"
                                       maxlength="50"
                                       required>
                                <small class="text-muted">Unique employee identification number</small>
                            </div>

                            <!-- NID -->
                            <div class="col-md-6">
                                <label for="nid" class="form-label fw-semibold">
                                    <i class="bi bi-building text-primary me-1"></i>NID (Company ID)
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nid" 
                                       name="nid" 
                                       placeholder="Enter company ID"
                                       maxlength="16">
                                <small class="text-muted">Internal company identification number</small>
                            </div>

                            <!-- Birthplace -->
                            <div class="col-md-6">
                                <label for="birthplace" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i>Place of Birth
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="birthplace" 
                                       name="birthplace" 
                                       placeholder="Enter place of birth"
                                       required>
                            </div>

                            <!-- Birthdate -->
                            <div class="col-md-6">
                                <label for="birthdate" class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event text-primary me-1"></i>Date of Birth
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="birthdate" 
                                       name="birthdate" 
                                       max="{{ date('Y-m-d', strtotime('-17 years')) }}"
                                       required>
                            </div>

                            <!-- TMT Awal -->
                            <div class="col-md-6">
                                <label for="TMT_awal" class="form-label fw-semibold">
                                    <i class="bi bi-calendar-check text-primary me-1"></i>TMT Masuk Dahana (Join Date)
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="TMT_awal" 
                                       name="TMT_awal">
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-6">
                                <label for="nomor_hp" class="form-label fw-semibold">
                                    <i class="bi bi-telephone-fill text-primary me-1"></i>Phone Number
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nomor_hp" 
                                       name="nomor_hp" 
                                       placeholder="e.g., 08123456789"
                                       maxlength="20">
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <label for="address" class="form-label fw-semibold">
                                    <i class="bi bi-house-fill text-primary me-1"></i>Address
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="address" 
                                          name="address" 
                                          rows="3"
                                          placeholder="Enter complete address"
                                          required></textarea>
                            </div>

                            <!-- CV File -->
                            <div class="col-12">
                                <label for="file_cv" class="form-label fw-semibold">
                                    <i class="bi bi-file-earmark-pdf text-primary me-1"></i>CV / Resume (PDF)
                                </label>
                                <input type="file" 
                                       class="form-control" 
                                       id="file_cv" 
                                       name="file_cv" 
                                       accept=".pdf">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>Maximum file size: 5MB. Accepted format: PDF only.
                                </small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-end">
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4" id="btnSaveEmployee">
                                <i class="bi bi-check-circle me-1"></i>Save Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add Contract Option (Hidden by default) -->
            <div id="addContractOption" class="card shadow-sm border-0 mt-4" style="display: none;">
                <div class="card-body p-4 text-center">
                    <h5 class="mb-3">
                        <i class="bi bi-question-circle-fill text-primary me-2"></i>Would you like to add an employment contract?
                    </h5>
                    <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                        <button type="button" class="btn btn-lg btn-primary" id="btnShowContractForm">
                            <i class="bi bi-file-earmark-plus me-2"></i>Add Employment Contract
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-lg btn-outline-secondary">
                            <i class="bi bi-list-ul me-2"></i>Back to Employee List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contract Form (Hidden by default) -->
            <div id="contractFormCard" class="card shadow-sm border-0 mt-4" style="display: none;">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text me-2"></i>Add Employment Contract
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data" id="contractForm">
                        @csrf
                        
                        <!-- Hidden employee_id -->
                        <input type="hidden" name="employee_id" id="contract_employee_id">

                        <!-- Employment Information -->
                        <h6 class="mb-3 text-primary">
                            <i class="bi bi-briefcase me-2"></i>Employment Information
                        </h6>

                        <div class="row g-3">
                            {{-- <div class="col-md-6">
                                <label for="TMT_awal" class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event text-primary me-1"></i>TMT Awal (Effective Start Date)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="TMT_awal" 
                                       name="TMT_awal" 
                                       required>
                            </div> --}}

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
                                <i class="bi bi-x-circle me-1"></i>Skip Contract
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

<style>
.bg-gradient {
    background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
}

.form-label {
    color: #002060;
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

.btn-outline-secondary:hover {
    transform: translateY(-2px);
}

.form-control:focus {
    border-color: #003DA5;
    box-shadow: 0 0 0 0.2rem rgba(0, 61, 165, 0.15);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeForm = document.getElementById('employeeForm');
    const employeeFormCard = document.getElementById('employeeFormCard');
    const addContractOption = document.getElementById('addContractOption');
    const contractFormCard = document.getElementById('contractFormCard');
    const btnShowContractForm = document.getElementById('btnShowContractForm');
    const btnCancelContract = document.getElementById('btnCancelContract');
    const successAlert = document.getElementById('successAlert');
    const formErrors = document.getElementById('formErrors');
    const contractType = document.getElementById('contract_type');
    const endDateField = document.getElementById('end_date_field');
    const endDateInput = document.getElementById('end_date');
    const endDateRequired = document.getElementById('end_date_required');

    let createdEmployeeId = null;

    // Handle employee form submission via AJAX
    employeeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(employeeForm);
        const btnSave = document.getElementById('btnSaveEmployee');
        const originalBtnText = btnSave.innerHTML;
        
        // Disable button and show loading
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
        
        // Hide previous errors
        formErrors.style.display = 'none';
        
        fetch('{{ route("employees.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createdEmployeeId = data.employee.id;
                
                // Show success message
                document.getElementById('successMessage').innerHTML = 
                    `<strong>${data.employee.employee_name}</strong> (NIK: ${data.employee.nik}) has been added successfully.`;
                successAlert.style.display = 'block';
                successAlert.classList.add('show');
                
                // Hide employee form
                employeeFormCard.style.display = 'none';
                
                // Show add contract option
                addContractOption.style.display = 'block';
                
                // Set employee ID in contract form
                document.getElementById('contract_employee_id').value = createdEmployeeId;
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                // Show errors
                const errorList = document.getElementById('errorList');
                errorList.innerHTML = '';
                
                if (data.errors) {
                    Object.values(data.errors).forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error[0];
                        errorList.appendChild(li);
                    });
                    formErrors.style.display = 'block';
                }
                
                btnSave.disabled = false;
                btnSave.innerHTML = originalBtnText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the employee. Please try again.');
            btnSave.disabled = false;
            btnSave.innerHTML = originalBtnText;
        });
    });

    // Show contract form
    btnShowContractForm.addEventListener('click', function() {
        addContractOption.style.display = 'none';
        contractFormCard.style.display = 'block';
        contractFormCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    // Skip contract (go to employee list)
    btnCancelContract.addEventListener('click', function() {
        if (confirm('Are you sure you want to skip adding a contract? You can always add it later.')) {
            window.location.href = '{{ route("employees.index") }}';
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
