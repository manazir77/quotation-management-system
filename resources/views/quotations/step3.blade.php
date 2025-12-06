@extends('layouts.app')

@section('title', 'Create Quotation - Step 3: Terms & Save')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Quotations</a></li>
        <li class="breadcrumb-item active">Terms & Save</li>
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
                        <div class="progress-bar bg-primary" style="width: 66%;"></div>
                    </div>
                    
                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <small class="d-block mt-2 text-success">Basic Info</small>
                    </div>
                    
                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <small class="d-block mt-2 text-success">Add Items</small>
                    </div>
                    
                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                            <i class="bi bi-3-circle-fill"></i>
                        </div>
                        <small class="d-block mt-2 fw-semibold text-primary">Terms & Save</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-file-text me-2"></i>Step 3: Terms & Conditions
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

                <form action="{{ route('quotations.store') }}" method="POST">
                    @csrf
                    
                    @if($terms->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Select Terms & Conditions to Include:</label>
                            <div class="row">
                                @foreach($terms as $term)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input term-checkbox" type="checkbox" 
                                                   id="term_{{ $term->id }}" 
                                                   data-content="{{ $term->content }}"
                                                   {{ $term->is_default ? 'checked' : '' }}>
                                            <label class="form-check-label" for="term_{{ $term->id }}">
                                                {{ $term->title }}
                                                @if($term->is_default)
                                                    <span class="badge bg-success ms-1">Default</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="terms_conditions" class="form-label fw-semibold">
                            Terms & Conditions Content
                            <small class="text-muted fw-normal">(You can modify this for this quotation only)</small>
                        </label>
                        <textarea class="form-control" id="terms_conditions" name="terms_conditions" 
                                  rows="10" placeholder="Enter terms and conditions...">{{ old('terms_conditions', $defaultTerms) }}</textarea>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Changes made here will only apply to this quotation and won't affect your saved terms.
                        </small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('quotations.step2') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Items
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-lg me-2"></i>Save Quotation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const termCheckboxes = document.querySelectorAll('.term-checkbox');
    const termsTextarea = document.getElementById('terms_conditions');

    function updateTermsContent() {
        let content = [];
        termCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                content.push(checkbox.dataset.content);
            }
        });
        termsTextarea.value = content.join('\n\n');
    }

    termCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTermsContent);
    });
});
</script>
@endpush

