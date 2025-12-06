@extends('layouts.app')

@section('title', 'Create Quotation - Step 2: Add Items')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Quotations</a></li>
        <li class="breadcrumb-item active">Add Items</li>
    </ol>
</nav>
@endsection

@push('styles')
<style>
    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 8px 8px;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1050;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }
    .search-result-item {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
    }
    .search-result-item:hover {
        background: #f8fafc;
    }
    .search-result-item:last-child {
        border-bottom: none;
    }
    .item-row input {
        max-width: 100px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- Progress Steps -->
        <div class="card mb-4">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between position-relative">
                    <div class="progress position-absolute w-100" style="height: 4px; top: 20px; z-index: 0;">
                        <div class="progress-bar bg-primary" style="width: 33%;"></div>
                    </div>
                    
                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <small class="d-block mt-2 text-success">Basic Info</small>
                    </div>
                    
                    <div class="text-center position-relative" style="z-index: 1;">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 40px; height: 40px;">
                            <i class="bi bi-2-circle-fill"></i>
                        </div>
                        <small class="d-block mt-2 fw-semibold text-primary">Add Items</small>
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

        <div class="row">
            <!-- Customer Details -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-person me-2"></i>Customer Details
                    </div>
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">{{ $customer->name }}</h5>
                        @if($customer->company)
                            <p class="mb-2"><i class="bi bi-building me-2 text-muted"></i>{{ $customer->company }}</p>
                        @endif
                        @if($customer->email)
                            <p class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i>{{ $customer->email }}</p>
                        @endif
                        @if($customer->phone)
                            <p class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i>{{ $customer->phone }}</p>
                        @endif
                        @if($customer->gst_number)
                            <p class="mb-2"><i class="bi bi-hash me-2 text-muted"></i>GST: {{ $customer->gst_number }}</p>
                        @endif
                        @if($customer->address)
                            <p class="mb-0"><i class="bi bi-geo-alt me-2 text-muted"></i>{{ $customer->address }}</p>
                        @endif

                        <hr>
                        <p class="mb-1"><strong>Date:</strong> {{ date('d M Y', strtotime($step1['quotation_date'])) }}</p>
                        <p class="mb-1"><strong>Time:</strong> {{ date('h:i A', strtotime($step1['quotation_time'])) }}</p>
                        <p class="mb-0"><strong>Valid Until:</strong> {{ date('d M Y', strtotime($step1['validity_date'])) }}</p>
                    </div>
                </div>
            </div>

            <!-- Item Search & Add -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-search me-2"></i>Search & Add Items
                    </div>
                    <div class="card-body">
                        <div class="position-relative mb-4">
                            <input type="text" id="item-search" class="form-control form-control-lg" 
                                   placeholder="Search by item name or barcode..." autocomplete="off">
                            <div id="search-results" class="search-results d-none"></div>
                        </div>

                        @if($items->count() == 0)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No items found. Please <a href="{{ route('items.create') }}">add items</a> first.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quotation Items Table -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-list-ul me-2"></i>Quotation Items
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="items-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th width="100">Qty</th>
                                <th width="150">Unit Price (₹)</th>
                                <th>GST %</th>
                                <th>GST Amount</th>
                                <th>Total</th>
                                <th width="60">Action</th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            <tr id="no-items-row">
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No items added yet. Search and add items above.
                                </td>
                            </tr>
                        </tbody>
                        <tfoot id="totals-footer" class="d-none">
                            <tr class="table-light">
                                <td colspan="5"></td>
                                <td class="fw-semibold">Subtotal:</td>
                                <td class="fw-semibold" id="subtotal">₹0.00</td>
                                <td></td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="5"></td>
                                <td class="fw-semibold">Total GST:</td>
                                <td class="fw-semibold" id="total-gst">₹0.00</td>
                                <td></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="5"></td>
                                <td class="fw-bold">Grand Total:</td>
                                <td class="fw-bold fs-5" id="grand-total">₹0.00</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('quotations.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Step 1
            </a>
            <a href="{{ route('quotations.step3') }}" class="btn btn-primary" id="next-btn">
                Next: Terms & Conditions <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('item-search');
    const searchResults = document.getElementById('search-results');
    const itemsBody = document.getElementById('items-body');
    const noItemsRow = document.getElementById('no-items-row');
    const totalsFooter = document.getElementById('totals-footer');
    
    let items = @json($items);
    let quotationItems = @json($quotationItems);
    let searchTimeout;

    // Initialize existing items
    if (quotationItems.length > 0) {
        renderItems();
    }

    // Search functionality
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.toLowerCase().trim();
        
        if (query.length < 1) {
            searchResults.classList.add('d-none');
            return;
        }

        searchTimeout = setTimeout(() => {
            const filtered = items.filter(item => 
                item.name.toLowerCase().includes(query) || 
                (item.barcode && item.barcode.toLowerCase().includes(query))
            );

            if (filtered.length > 0) {
                searchResults.innerHTML = filtered.map(item => `
                    <div class="search-result-item" data-item='${JSON.stringify(item)}'>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${item.name}</strong>
                                ${item.category ? `<span class="badge bg-secondary ms-2">${item.category.name}</span>` : ''}
                                ${item.barcode ? `<small class="text-muted d-block">Barcode: ${item.barcode}</small>` : ''}
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success">₹${parseFloat(item.sale_price).toFixed(2)}</span>
                                <small class="text-muted d-block">GST: ${item.gst_percentage}%</small>
                            </div>
                        </div>
                    </div>
                `).join('');
                searchResults.classList.remove('d-none');
            } else {
                searchResults.innerHTML = '<div class="search-result-item text-muted">No items found</div>';
                searchResults.classList.remove('d-none');
            }
        }, 200);
    });

    // Click on search result
    searchResults.addEventListener('click', function(e) {
        const resultItem = e.target.closest('.search-result-item');
        if (resultItem && resultItem.dataset.item) {
            const item = JSON.parse(resultItem.dataset.item);
            addItem(item);
            searchInput.value = '';
            searchResults.classList.add('d-none');
        }
    });

    // Hide search results on click outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('d-none');
        }
    });

    function addItem(item) {
        fetch('{{ route("quotations.addItem") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                item_id: item.id,
                quantity: 1,
                unit_price: item.sale_price
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                quotationItems = data.items;
                renderItems();
                updateTotals(data.totals);
            }
        });
    }

    function updateItem(index, quantity, unitPrice) {
        fetch('{{ route("quotations.updateItem") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                index: index,
                quantity: quantity,
                unit_price: unitPrice
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                quotationItems = data.items;
                renderItems();
                updateTotals(data.totals);
            }
        });
    }

    function removeItem(index) {
        fetch('{{ route("quotations.removeItem") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ index: index })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                quotationItems = data.items;
                renderItems();
                updateTotals(data.totals);
            }
        });
    }

    function renderItems() {
        if (quotationItems.length === 0) {
            noItemsRow.style.display = '';
            totalsFooter.classList.add('d-none');
            return;
        }

        noItemsRow.style.display = 'none';
        totalsFooter.classList.remove('d-none');

        // Remove existing item rows (except no-items-row)
        const existingRows = itemsBody.querySelectorAll('.item-row');
        existingRows.forEach(row => row.remove());

        quotationItems.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'item-row';
            row.innerHTML = `
                <td>${index + 1}</td>
                <td><strong>${item.item_name}</strong></td>
                <td>
                    <input type="number" class="form-control form-control-sm qty-input" 
                           value="${item.quantity}" min="1" data-index="${index}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm price-input" 
                           value="${parseFloat(item.unit_price).toFixed(2)}" step="0.01" min="0" data-index="${index}">
                </td>
                <td>${item.gst_percentage}%</td>
                <td>₹${parseFloat(item.gst_amount).toFixed(2)}</td>
                <td class="fw-semibold">₹${parseFloat(item.total_price).toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-btn" data-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            itemsBody.insertBefore(row, noItemsRow);
        });

        // Add event listeners
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function() {
                const index = parseInt(this.dataset.index);
                const priceInput = document.querySelector(`.price-input[data-index="${index}"]`);
                updateItem(index, parseInt(this.value), parseFloat(priceInput.value));
            });
        });

        document.querySelectorAll('.price-input').forEach(input => {
            input.addEventListener('change', function() {
                const index = parseInt(this.dataset.index);
                const qtyInput = document.querySelector(`.qty-input[data-index="${index}"]`);
                updateItem(index, parseInt(qtyInput.value), parseFloat(this.value));
            });
        });

        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Remove this item from quotation?')) {
                    removeItem(parseInt(this.dataset.index));
                }
            });
        });
    }

    function updateTotals(totals) {
        document.getElementById('subtotal').textContent = '₹' + parseFloat(totals.subtotal).toFixed(2);
        document.getElementById('total-gst').textContent = '₹' + parseFloat(totals.total_gst).toFixed(2);
        document.getElementById('grand-total').textContent = '₹' + parseFloat(totals.total_amount).toFixed(2);
    }
});
</script>
@endpush

