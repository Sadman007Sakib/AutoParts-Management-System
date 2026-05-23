@extends('layouts.erp')

@section('title', 'Sales - Edit')

@section('content')

    <div class="container-fluid py-4">

        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">

                <div class="card border-0 shadow-sm rounded-4">

                    {{-- HEADER --}}
                    <div class="card-header bg-white border-0 py-4 px-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h3 class="fw-bold mb-1">
                                    Edit Sale — {{ $sale->slip_no ?? '#' }}
                                </h3>
                                <p class="text-muted mb-0 small">
                                    Recorded at {{ $sale->created_at->format('d M, Y H:i') }}
                                </p>
                            </div>

                            <a href="{{ route('sales.index') }}" class="btn btn-light border rounded-3 px-4">
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        @if (session('error'))
                            <div class="alert alert-danger rounded-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('sales.update', $sale->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- CUSTOMER --}}
                            <div class="card border-0 bg-light rounded-4 mb-4">
                                <div class="card-body p-4">

                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Customer Name</label>
                                            <input name="customer_name" class="form-control rounded-3"
                                                value="{{ old('customer_name', $sale->customer_name) }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Notes</label>
                                            <input name="notes" class="form-control rounded-3"
                                                value="{{ old('notes', $sale->notes) }}">
                                        </div>

                                    </div>

                                </div>
                            </div>

                            {{-- ITEMS --}}
                            <div class="card border rounded-4 mb-4">

                                <div class="card-header bg-white border-0 py-3 px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-semibold">Sale Items</h5>

                                        <button type="button" id="add-row" class="btn btn-primary rounded-3 px-3">
                                            + Add Item
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body p-0">

                                    <div class="table-responsive">

                                        <table class="table align-middle mb-0" id="sale-items">

                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4">Part</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th class="text-end">Line Total</th>
                                                    <th class="text-end pe-4">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @foreach ($sale->items as $i => $it)
                                                    <tr>

                                                        <td class="ps-4">
                                                            <select class="form-select rounded-3">
                                                                <option value="">-- select part --</option>
                                                                @foreach ($parts as $p)
                                                                    <option value="{{ $p->id }}"
                                                                        data-price="{{ $p->sell_price }}"
                                                                        data-stock="{{ $p->current_quantity }}"
                                                                        @if (old("items.$i.part_id", $it->part_id) == $p->id) selected @endif>
                                                                        {{ $p->sku }} — {{ $p->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <input type="number" step="0.01"
                                                                class="form-control rounded-3 price-input"
                                                                value="{{ old("items.$i.price", $it->sold_price) }}">
                                                        </td>

                                                        <td>
                                                            <input type="number" min="1"
                                                                class="form-control rounded-3 qty-input"
                                                                value="{{ old("items.$i.quantity", $it->quantity) }}">
                                                        </td>

                                                        <td class="line text-end fw-bold">
                                                            {{ number_format($it->line_total, 2) }}
                                                        </td>

                                                        <td class="text-end pe-4">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger rounded-3 remove-row">
                                                                Remove
                                                            </button>
                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>

                                        </table>

                                    </div>

                                </div>

                            </div>

                            {{-- TOTALS --}}
                            <div class="row g-4">

                                <div class="col-lg-7">
                                    <div class="card border rounded-4 h-100">
                                        <div class="card-body p-4">

                                            <h5 class="fw-semibold mb-4">Pricing Details</h5>

                                            <div class="row g-3">

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Discount Type</label>
                                                    <select id="discount_type" name="discount_type"
                                                        class="form-select rounded-3">
                                                        <option value="">None</option>
                                                        <option value="fixed"
                                                            {{ old('discount_type', $sale->discount_type) === 'fixed' ? 'selected' : '' }}>
                                                            Fixed</option>
                                                        <option value="percent"
                                                            {{ old('discount_type', $sale->discount_type) === 'percent' ? 'selected' : '' }}>
                                                            Percent</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Discount Value</label>
                                                    <input type="number" id="discount_value" name="discount_value"
                                                        class="form-control rounded-3"
                                                        value="{{ old('discount_value', $sale->discount_value ?? 0) }}">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Tax Rate (%)</label>
                                                    <input type="number" id="tax_rate" name="tax_rate"
                                                        class="form-control rounded-3"
                                                        value="{{ old('tax_rate', $sale->tax_rate ?? 0) }}">
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <div class="card border-0 bg-dark text-white rounded-4 h-100">
                                        <div class="card-body p-4">

                                            <h5 class="fw-semibold mb-4">Invoice Summary</h5>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal</span>
                                                <strong id="display_subtotal">0.00</strong>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Discount</span>
                                                <strong id="display_discount">0.00</strong>
                                            </div>

                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Tax</span>
                                                <strong id="display_tax">0.00</strong>
                                            </div>

                                            <hr>

                                            <div class="d-flex justify-content-between">
                                                <span class="fs-5">Grand</span>
                                                <strong id="display_grand" class="fs-4">0.00</strong>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- ACTIONS --}}
                            <div class="d-flex justify-content-end gap-2 mt-4">

                                <a href="{{ route('sales.index') }}" class="btn btn-light border rounded-3 px-4">
                                    Cancel
                                </a>

                                <button class="btn btn-primary rounded-3 px-4">
                                    Update Sale
                                </button>

                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        (function() {
            // --- Config / DOM references (defensive) ---
            const parts = (typeof window.partsData !== 'undefined') ? window.partsData : @json($parts);
            const tbody = document.querySelector('#sale-items tbody');
            const addRowBtn = document.getElementById('add-row');
            // Prefer the form that contains the table; fallback to first form on page
            const form = document.querySelector('form') || null;

            // discount & tax elements (may be present in both create/edit)
            const discountTypeEl = document.getElementById('discount_type');
            const discountValueEl = document.getElementById('discount_value');
            const taxRateEl = document.getElementById('tax_rate');

            // display elements (defensive)
            const displaySubtotal = document.getElementById('display_subtotal');
            const displayDiscount = document.getElementById('display_discount');
            const displayTax = document.getElementById('display_tax');
            const displayGrand = document.getElementById('display_grand');

            if (!tbody) {
                console.warn('Sale script: table body #sale-items tbody not found — aborting script.');
                return;
            }

            // --- Utility helpers ---
            function rowOf(el) {
                return el ? el.closest('tr') : null;
            }

            function toNum(v) {
                const n = parseFloat(v);
                return Number.isFinite(n) ? n : 0;
            }

            function partsOptionsHtml() {
                return ['<option value="">-- select part --</option>']
                    .concat(parts.map(p =>
                        `<option value="${p.id}" data-price="${p.sell_price}" data-stock="${p.current_quantity}">${p.sku} — ${p.name} (${p.current_quantity})</option>`
                        ))
                    .join('');
            }

            // Reindex names to items[0]..items[n]
            function indexRows() {
                tbody.querySelectorAll('tr').forEach((tr, idx) => {
                    const sel = tr.querySelector('select');
                    if (sel) sel.name = `items[${idx}][part_id]`;

                    const price = tr.querySelector('input.price-input');
                    if (price) price.name = `items[${idx}][price]`;

                    const qty = tr.querySelector('input.qty-input');
                    if (qty) qty.name = `items[${idx}][quantity]`;
                });
            }

            // Compute single row line and update totals
            function computeLine(tr) {
                if (!tr) return;
                const priceEl = tr.querySelector('input.price-input');
                const qtyEl = tr.querySelector('input.qty-input');
                const price = toNum(priceEl?.value);
                const qty = parseInt(qtyEl?.value) || 0;
                const lineCell = tr.querySelector('.line');
                const value = +(price * qty);
                if (lineCell) lineCell.innerText = value.toFixed(2);
                computeTotals();
            }

            // Compute subtotal, discount, tax, grand total and update UI
            function computeTotals() {
                let subtotal = 0;
                tbody.querySelectorAll('tr').forEach(tr => {
                    const lineCell = tr.querySelector('.line');
                    subtotal += toNum(lineCell?.innerText || lineCell?.textContent || 0);
                });

                // read discount inputs (defensive)
                const discountType = discountTypeEl ? (discountTypeEl.value || '') : '';
                const discountVal = discountValueEl ? toNum(discountValueEl.value) : 0;

                // friendly UX: assume fixed if value entered but type not chosen
                const effectiveType = discountType || (discountVal > 0 ? 'fixed' : '');

                let discountAmount = 0;
                if (effectiveType === 'percent') {
                    discountAmount = +(subtotal * (discountVal / 100));
                } else if (effectiveType === 'fixed') {
                    discountAmount = +discountVal;
                }

                if (discountAmount > subtotal) discountAmount = subtotal;

                const taxRate = taxRateEl ? toNum(taxRateEl.value) : 0;
                const taxable = Math.max(0, subtotal - discountAmount);
                const taxAmount = +(taxable * (taxRate / 100));
                const grand = +(taxable + taxAmount);

                if (displaySubtotal) displaySubtotal.innerText = subtotal.toFixed(2);
                if (displayDiscount) displayDiscount.innerText = discountAmount.toFixed(2);
                if (displayTax) displayTax.innerText = taxAmount.toFixed(2);
                if (displayGrand) displayGrand.innerText = grand.toFixed(2);

                return {
                    subtotal,
                    discountAmount,
                    taxAmount,
                    grand
                };
            }

            // Add a new empty row
            function addRow(prefill = null) {
                const tr = document.createElement('tr');

                if (prefill && prefill.part_id) {
                    // allow passing prefill object { part_id, price, quantity, line_total }
                    tr.innerHTML = `<td><select class="form-select">${partsOptionsHtml()}</select></td>
        <td><input type="number" step="0.01" class="form-control price-input" value="${prefill.price ?? ''}"></td>
        <td><input type="number" min="1" class="form-control qty-input" value="${prefill.quantity ?? 1}"></td>
        <td class="line text-end fw-semibold">${(prefill.line_total ?? '0.00')}</td>
        <td class="text-end"><button type="button" class="btn btn-sm btn-danger remove-row">×</button></td>`;
                    // after adding we must set the selected option explicitly
                    tbody.appendChild(tr);
                    const sel = tr.querySelector('select');
                    if (sel) sel.value = String(prefill.part_id);
                } else {
                    tr.innerHTML = `<td><select class="form-select">${partsOptionsHtml()}</select></td>
        <td><input type="number" step="0.01" class="form-control price-input"></td>
        <td><input type="number" min="1" class="form-control qty-input" value="1"></td>
        <td class="line text-end fw-semibold">0.00</td>
        <td class="text-end"><button type="button" class="btn btn-sm btn-danger remove-row">×</button></td>`;
                    tbody.appendChild(tr);
                }

                indexRows();
                // compute line for the new row (price may be blank until user selects part)
                computeLine(tr);
                return tr;
            }

            // Remove a row
            function removeRow(tr) {
                if (!tr) return;
                tr.remove();
                indexRows();
                computeTotals();
            }

            // Delegated handlers (works for existing & future rows)
            tbody.addEventListener('change', function(e) {
                const t = e.target;
                if (t.matches('select')) {
                    const tr = rowOf(t);
                    const selected = t.selectedOptions[0];
                    const priceEl = tr.querySelector('input.price-input');
                    // populate price from option data attribute (if provided)
                    if (selected && selected.dataset.price) {
                        priceEl.value = selected.dataset.price;
                    } else {
                        priceEl.value = '';
                    }
                    computeLine(tr);
                    indexRows();
                }
            });

            tbody.addEventListener('input', function(e) {
                const t = e.target;
                if (t.matches('input.price-input') || t.matches('input.qty-input')) {
                    computeLine(rowOf(t));
                }
            });

            tbody.addEventListener('click', function(e) {
                const t = e.target;
                if (t.matches('.remove-row')) {
                    removeRow(rowOf(t));
                }
            });

            // add row button
            if (addRowBtn) addRowBtn.addEventListener('click', () => addRow());

            // Attach listeners for discount/tax so computeTotals runs live
            if (discountValueEl) discountValueEl.addEventListener('input', computeTotals);
            if (discountTypeEl) discountTypeEl.addEventListener('change', computeTotals);
            if (taxRateEl) taxRateEl.addEventListener('input', computeTotals);

            // Before submit: prune empty rows, reindex names, compute final totals, disable submit to avoid double-post
            if (form) {
                form.addEventListener('submit', function(e) {
                    // prune rows missing part_id or with qty <=0
                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    rows.forEach(tr => {
                        const sel = tr.querySelector('select');
                        const qty = tr.querySelector('.qty-input');
                        if (!sel || !sel.value || sel.value === '' || !qty || parseInt(qty.value) <=
                            0) {
                            tr.remove();
                        }
                    });

                    if (tbody.querySelectorAll('tr').length === 0) {
                        e.preventDefault();
                        alert('Please add at least one valid part before submitting.');
                        return false;
                    }

                    // if user typed discount value without selecting type, assume fixed
                    if (discountValueEl && discountValueEl.value && discountValueEl.value.trim() !== '' &&
                        discountTypeEl && (!discountTypeEl.value || discountTypeEl.value === '')) {
                        discountTypeEl.value = 'fixed';
                    }

                    // final indexing & totals
                    indexRows();
                    computeTotals();

                    // disable submit button to avoid double posts
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('disabled');
                        submitBtn.dataset.origText = submitBtn.innerText;
                        submitBtn.innerText = 'Processing...';
                    }

                    // allow submit
                });
            } // end form check

            // Init: if no rows exist, add one. For edit form you might already have rows prefilled; ensure prices filled
            (function init() {
                if (tbody.querySelectorAll('tr').length === 0) {
                    addRow();
                } else {
                    // for prefilled rows (edit) ensure each row's price input is populated from the selected option if empty
                    tbody.querySelectorAll('tr').forEach(tr => {
                        const sel = tr.querySelector('select');
                        const priceEl = tr.querySelector('input.price-input');
                        if (sel && sel.selectedOptions && sel.selectedOptions[0] && sel.selectedOptions[0]
                            .dataset.price && (!priceEl.value || priceEl.value === '')) {
                            priceEl.value = sel.selectedOptions[0].dataset.price;
                        }
                        // ensure line cell matches price*qty
                        computeLine(tr);
                    });
                }

                // small delay if DOM is still being manipulated by server template code
                setTimeout(() => {
                    indexRows();
                    computeTotals();
                }, 40);
            })();

        })();
    </script>

@endsection
