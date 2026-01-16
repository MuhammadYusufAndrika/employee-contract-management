@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text"></i> Contract Details
                    </h5>
                    <div>
                        @if(auth()->user()->canModify())
                            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Employee Information -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-person"></i> Employee Information
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Employee Name:</th>
                                    <td>
                                        <a href="{{ route('employees.show', $contract->employee) }}">
                                            {{ $contract->employee->employee_name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>NIK:</th>
                                    <td>{{ $contract->employee->nik }}</td>
                                </tr>
                                <tr>
                                    <th>NID:</th>
                                    <td>{{ $contract->employee->nid ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Contract Information -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-file-earmark-text"></i> Contract Information
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Contract Number:</th>
                                    <td>{{ $contract->nomor_kontrak }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @php
                                            $statusClass = match($contract->status) {
                                                'Active' => 'success',
                                                'Permanent' => 'primary',
                                                'Expired' => 'danger',
                                                'Layoff' => 'dark',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $contract->status }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Renewal Number:</th>
                                    <td>{{ $contract->renewal_number ?? 0 }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Work Details -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-briefcase"></i> Work Details
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Department:</th>
                                    <td>{{ $contract->department }}</td>
                                </tr>
                                <tr>
                                    <th>Work Location:</th>
                                    <td>{{ $contract->work_location }}</td>
                                </tr>
                                <tr>
                                    <th>Work Position:</th>
                                    <td>{{ $contract->work_position ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Contract Period -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="bi bi-calendar-range"></i> Contract Period
                            </h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Start Date:</th>
                                    <td>{{ $contract->start_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <th>End Date:</th>
                                    <td>
                                        {{ $contract->end_date->format('d M Y') }}
                                        @if($contract->status === 'Active')
                                            @php
                                                $daysRemaining = now()->diffInDays($contract->end_date, false);
                                            @endphp
                                            @if($daysRemaining > 0)
                                                <span class="badge bg-info text-dark ms-2">
                                                    {{ $daysRemaining }} days remaining
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Duration:</th>
                                    <td>{{ $contract->start_date->diffInDays($contract->end_date) }} days</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($contract->notes)
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="bi bi-sticky"></i> Notes
                                </h6>
                                <div class="alert alert-info">
                                    {{ $contract->notes }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    @if(auth()->user()->canModify())
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    @if($contract->status === 'Active' || $contract->status === 'Expired')
                                        <a href="{{ route('contracts.renew', $contract) }}" class="btn btn-success">
                                            <i class="bi bi-arrow-clockwise"></i> Renew Contract
                                        </a>
                                    @endif
                                    <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Edit Contract
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
