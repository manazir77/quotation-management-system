@extends('layouts.app')

@section('title', 'Create Quotation - Step 1')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Quotations</a></li>
        <li class="breadcrumb-item active">Create Quotation</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Progress Steps -->
        <div class="card mb-4">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between position-relative">
                    <div class="progress position-absolute w-100" style="height: 4px; top: 20px; z-index: 0;">
                        <div class="progress-bar bg-primary" style="width: 0%;"></div>
                    </div>
                    
                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                            <i class="bi bi-1-circle-fill"></i>
                        </div>
                        <small class="d-block mt-2 fw-semibold text-primary">Basic Info</small>
                    </div>
                    
                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                            <i class="bi bi-2-circle"></i>
                        </div>
                        <small class="d-block mt-2 text-muted">Add Items</small>
                    </div>
                    
                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="rounded-circle bg-light text-muted d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                            <i class="bi bi-3-circle"></i>
                        </div>
                        <small class="d-block mt-2 text-muted">Terms & Save</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-file-plus me-2"></i>Step 1: Basic Quotation Information
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($customers->count() == 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No customers found. Please <a href="{{ route('customers.create') }}">add a customer</a> first.
                    </div>
                @else
                    <form action="{{ route('quotations.storeStep1') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="customer_id" class="form-label">Select Customer <span class="text-danger">*</span></label>
                            <select class="form-select form-select-lg @error('customer_id') is-invalid @enderror" 
                                    id="customer_id" name="customer_id" required>
                                <option value="">-- Select a Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} {{ $customer->company ? '(' . $customer->company . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="quotation_date" class="form-label">Quotation Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('quotation_date') is-invalid @enderror" 
                                       id="quotation_date" name="quotation_date" 
                                       value="{{ old('quotation_date', date('Y-m-d')) }}" required>
                                @error('quotation_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="quotation_time" class="form-label">Quotation Time <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('quotation_time') is-invalid @enderror" 
                                       id="quotation_time" name="quotation_time" 
                                       value="{{ old('quotation_time', date('H:i')) }}" required>
                                @error('quotation_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="validity_date" class="form-label">Valid Until <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('validity_date') is-invalid @enderror" 
                                       id="validity_date" name="validity_date" 
                                       value="{{ old('validity_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                @error('validity_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Add any additional notes for this quotation...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('quotations.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Next: Add Items <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

