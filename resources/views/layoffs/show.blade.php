@extends('layouts.bootstrap')

@section('title', 'Layoff Details')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-danger mb-0">
                    <i class="bi bi-person-x-fill me-2"></i>Layoff Details
                </h2>
                <a href="{{ route('layoffs.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Employee Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Employee Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Full Name:</th>
                                    <td><strong>{{ $layoff->employee->employee_name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>NIK:</th>
                                    <td>{{ $layoff->employee->nik }}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number:</th>
                                    <td>{{ $layoff->employee->nomor_hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Birth Date:</th>
                                    <td>{{ $layoff->employee->birthdate->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Birth Place:</th>
                                    <td>{{ $layoff->employee->birthplace }}</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td>{{ $layoff->employee->address }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Layoff Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient text-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text me-2"></i>Layoff Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Layoff Date:</th>
                                    <td>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-calendar-x me-1"></i>
                                            {{ $layoff->layoff_date->format('d M Y') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Processed By:</th>
                                    <td>{{ $layoff->processedBy->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Processed Date:</th>
                                    <td>{{ $layoff->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Layoff Letter:</th>
                                    <td>
                                        @if($layoff->layoff_letter)
                                            <a href="{{ asset('storage/' . $layoff->layoff_letter) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-danger">
                                                <i class="bi bi-file-pdf me-1"></i>View Letter PDF
                                            </a>
                                        @else
                                            <span class="text-muted">No letter uploaded</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($layoff->reason)
                        <div class="row mt-3">
                            <div class="col-12">
                                <hr>
                                <h6 class="fw-bold text-danger">Reason for Layoff:</h6>
                                <p class="text-muted">{{ $layoff->reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if(auth()->user()->isAdmin())
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="mb-3">Administrative Actions</h6>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('layoffs.edit', $layoff) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>Edit Layoff Record
                            </a>
                            <form action="{{ route('layoffs.destroy', $layoff) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this layoff record? This will restore the employee to active status.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>Delete Record
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
}
</style>
@endsection
