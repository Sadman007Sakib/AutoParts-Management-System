<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // List (admin/coordinator allowed or adjust middleware as you want)
    public function index()
    {
        $sales = Sale::with('seller')->latest()->paginate(20);
        return view('sales.index', compact('sales'));
    }

    // Show create form
    public function create()
    {
        $parts = Part::orderBy('name')->get(['id','sku','name','sell_price','current_quantity']);
    $prefill = request()->query('prefill') ? intval(request()->query('prefill')) : null;
    return view('sales.create', compact('parts','prefill'));
    }

    // Store new sale (transactional)
    // Store new sale (transactional)
public function store(Request $request)
{
    // --- CLEAN incoming items to remove empty rows (client-side may send blank rows) ---
        $rawItems = $request->input('items', []);
    // keep only rows with a non-empty part_id and positive quantity
        $clean = array_values(array_filter($rawItems, function($it) {
            return isset($it['part_id']) && $it['part_id'] !== '' && isset($it['quantity']) && intval($it['quantity']) > 0;
            }));

    // replace the request's items with cleaned array so validation only sees real rows
        $request->merge(['items' => $clean]);

    // if no item remains, return with error
    if (count($clean) === 0) {
        return back()->withInput()->with('error', 'Please add at least one part before submitting.');
    }

    

    $data = $request->validate([
        'customer_name' => 'nullable|string|max:255',
        'items' => 'required|array|min:1',
        'items.*.part_id' => 'required|integer|exists:parts,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
        // discount fields
        'discount_type' => 'nullable|in:fixed,percent',
        'discount_value' => 'nullable|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0',
    ]);

    return DB::transaction(function() use ($data, $request) {
        // slip generation
        $date = now()->format('Ymd');
        $prefix = "SLIP-{$date}-";
        $last = Sale::where('slip_no', 'like', $prefix.'%')->orderBy('id', 'desc')->first();
        $next = $last ? str_pad(intval(substr($last->slip_no, strlen($prefix))) + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $slipNo = $prefix . $next;

        // create header (subtotal/totals will be saved later)
        $sale = Sale::create([
            'slip_no' => $slipNo,
            'customer_name' => $data['customer_name'] ?? null,
            'sold_by' => Auth::id(),
            'subtotal' => 0,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'discount_value' => $data['discount_value'] ?? null,
            'discount_type' => $data['discount_type'] ?? null,
            'total_amount' => 0,
            'notes' => $data['notes'] ?? null,
        ]);

        $subtotal = 0;

        foreach ($data['items'] as $item) {
            $part = Part::findOrFail($item['part_id']);

            if ($part->current_quantity < $item['quantity']) {
                throw new \Exception("Insufficient stock for {$part->name} (available: {$part->current_quantity})");
            }

            $lineTotal = round($item['quantity'] * $item['price'], 2);

            $sale->items()->create([
                'part_id' => $part->id,
                'sold_price' => $item['price'],
                'quantity' => $item['quantity'],
                'line_total' => $lineTotal,
            ]);

            // decrement
            $part->current_quantity -= $item['quantity'];
            $part->save();

            $subtotal += $lineTotal;
        }

        // compute discount
        $discountType = $data['discount_type'] ?? null;
        $discountValue = isset($data['discount_value']) ? (float) $data['discount_value'] : 0.0;
        $discountAmount = 0.0;

        if ($discountType === 'percent' && $discountValue > 0) {
            $discountAmount = round($subtotal * ($discountValue / 100), 2);
        } elseif ($discountType === 'fixed' && $discountValue > 0) {
            $discountAmount = round($discountValue, 2);
        }

        // ensure discount not larger than subtotal
        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

        // tax rate: prefer request override, otherwise config
        $taxRate = $request->input('tax_rate') !== null
            ? (float) $request->input('tax_rate')
            : (float) config('shop.tax_rate', 0);

        // tax is usually calculated on amount after discount:
        $taxBase = max(0, $subtotal - $discountAmount);
        $taxAmount = round($taxBase * ($taxRate / 100), 2);
        $grandTotal = round($taxBase + $taxAmount, 2);

        // update sale header
        $sale->subtotal = round($subtotal, 2);
        $sale->discount_amount = round($discountAmount, 2);
        $sale->discount_value = $discountValue ?: null;
        $sale->discount_type = $discountType ?: null;
        $sale->tax_rate = $taxRate;
        $sale->tax_amount = $taxAmount;
        $sale->total_amount = $grandTotal;
        $sale->save();

        return redirect()->route('sales.show', $sale->id)->with('success', "Sale recorded ({$slipNo})");
    });
}


    // Show receipt
    public function show(Sale $sale)
    {
        $sale->load('items.part','seller');
        return view('sales.show', compact('sale'));
    }

    // Edit: show form prefilled
    public function edit(Sale $sale)
    {
        $sale->load('items.part');
        $parts = Part::orderBy('name')->get(['id','sku','name','sell_price','current_quantity']);
        return view('sales.edit', compact('sale','parts'));
    }

    // Update: replace sale items and adjust stock correctly
    public function update(Request $request, Sale $sale)
{
                // --- CLEAN incoming items to remove empty rows (client-side may send blank rows) ---
            $rawItems = $request->input('items', []);
            // keep only rows with a non-empty part_id and positive quantity
            $clean = array_values(array_filter($rawItems, function($it) {
                return isset($it['part_id']) && $it['part_id'] !== '' && isset($it['quantity']) && intval($it['quantity']) > 0;
            }));

            // replace the request's items with cleaned array so validation only sees real rows
            $request->merge(['items' => $clean]);

            // if no item remains, return with error
            if (count($clean) === 0) {
                return back()->withInput()->with('error', 'Please add at least one part before submitting.');
            }

    $data = $request->validate([
        'customer_name' => 'nullable|string|max:255',
        'items' => 'required|array|min:1',
        'items.*.part_id' => 'required|integer|exists:parts,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'notes' => 'nullable|string',
        'discount_type' => 'nullable|in:fixed,percent',
        'discount_value' => 'nullable|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0',
    ]);

    try {
        return DB::transaction(function() use ($sale, $data, $request) {

            // 1) Restore stock from existing sale items
            foreach ($sale->items as $oldItem) {
                $p = Part::find($oldItem->part_id);
                if ($p) {
                    $p->current_quantity += $oldItem->quantity;
                    $p->save();
                }
            }

            // 2) Remove old sale items
            $sale->items()->delete();

            // 3) Validate & create new items, adjust stock
            $subtotal = 0;
            foreach ($data['items'] as $item) {
                $part = Part::findOrFail($item['part_id']);

                if ($part->current_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$part->name} (available: {$part->current_quantity})");
                }

                $lineTotal = round($item['quantity'] * $item['price'], 2);

                $sale->items()->create([
                    'part_id' => $part->id,
                    'sold_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal,
                ]);

                // decrement stock
                $part->current_quantity -= $item['quantity'];
                $part->save();

                $subtotal += $lineTotal;
            }

            // compute discount
            $discountType = $data['discount_type'] ?? null;
            $discountValue = isset($data['discount_value']) ? (float) $data['discount_value'] : 0.0;
            $discountAmount = 0.0;

            if ($discountType === 'percent' && $discountValue > 0) {
                $discountAmount = round($subtotal * ($discountValue / 100), 2);
            } elseif ($discountType === 'fixed' && $discountValue > 0) {
                $discountAmount = round($discountValue, 2);
            }

            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }

            // tax rate: prefer request override, otherwise config
            $taxRate = $request->input('tax_rate') !== null
                ? (float) $request->input('tax_rate')
                : (float) config('shop.tax_rate', 0);

            $taxBase = max(0, $subtotal - $discountAmount);
            $taxAmount = round($taxBase * ($taxRate / 100), 2);
            $grandTotal = round($taxBase + $taxAmount, 2);

            // 4) Update sale header
            $sale->customer_name = $data['customer_name'] ?? null;
            $sale->notes = $data['notes'] ?? null;
            $sale->subtotal = round($subtotal, 2);
            $sale->discount_amount = round($discountAmount, 2);
            $sale->discount_value = $discountValue ?: null;
            $sale->discount_type = $discountType ?: null;
            $sale->tax_rate = $taxRate;
            $sale->tax_amount = $taxAmount;
            $sale->total_amount = $grandTotal;
            $sale->save();

            return redirect()->route('sales.show', $sale->id)->with('success','Sale updated.');
        });
    } catch (\Exception $e) {
        \Log::error('Sale update error: '.$e->getMessage(), ['sale_id' => $sale->id, 'trace' => $e->getTraceAsString()]);
        return back()->withInput()->with('error','Could not update sale: '.$e->getMessage());
    }
}


    // Destroy: remove sale and restore stock
    public function destroy(Sale $sale)
    {
        return DB::transaction(function() use ($sale) {
            // restore stock for each item
            foreach ($sale->items as $item) {
                $part = Part::lockForUpdate()->findOrFail($item->part_id);
                $part->increment('current_quantity', $item->quantity);
            }

            $sale->delete(); // cascade will remove sale_items if migration uses cascadeOnDelete
            return redirect()->route('sales.index')->with('success','Sale deleted and stock restored.');
        });
    }
}
