@extends('layouts.app')

@section('title', 'Terms & Conditions - Quotation Management System')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Terms & Conditions</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">Terms & Conditions</h1>
    <a href="{{ route('terms.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Add Terms
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($terms->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Default</th>
                            <th>Order</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($terms as $term)
                            <tr>
                                <td>{{ $loop->iteration + ($terms->currentPage() - 1) * $terms->perPage() }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $term->title }}</span>
                                </td>
                                <td>{{ Str::limit($term->content, 60) }}</td>
                                <td>
                                    @if($term->is_default)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-lg"></i> Default
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $term->sort_order }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('terms.edit', $term) }}" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('terms.destroy', $term) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this term?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($terms->hasPages())
                <div class="card-footer bg-white">
                    {{ $terms->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-file-text fs-1 text-muted"></i>
                <p class="text-muted mt-3">No terms & conditions found. Add your first one!</p>
                <a href="{{ route('terms.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Add Terms
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

