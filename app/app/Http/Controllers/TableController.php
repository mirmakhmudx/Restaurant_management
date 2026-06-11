<?php

namespace App\Http\Controllers;

use App\Enums\TableLocation;
use App\Enums\TableStatus;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TableController extends Controller
{
    public function index(): View
    {
        $tables   = Table::orderBy('number')->get();
        $grouped  = $tables->groupBy(fn($t) => $t->location->value);
        $statuses = TableStatus::cases();

        return view('tables.index', compact('tables', 'grouped', 'statuses'));
    }

    public function create(): View
    {
        return view('tables.create', [
            'statuses'  => TableStatus::cases(),
            'locations' => TableLocation::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'number'   => ['required', 'integer', 'min:1', 'max:999', 'unique:tables,number'],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'location' => ['required', 'string'],
            'status'   => ['required', 'string'],
            'notes'    => ['nullable', 'string', 'max:200'],
        ]);

        Table::create($data);

        return redirect()->route('tables.index')
            ->with('success', "Table {$data['number']} added successfully.");
    }

    public function edit(Table $table): View
    {
        return view('tables.edit', [
            'table'     => $table,
            'statuses'  => TableStatus::cases(),
            'locations' => TableLocation::cases(),
        ]);
    }

    public function update(Request $request, Table $table): RedirectResponse
    {
        $data = $request->validate([
            'number'   => ['required', 'integer', 'min:1', 'max:999', "unique:tables,number,{$table->id}"],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'location' => ['required', 'string'],
            'status'   => ['required', 'string'],
            'notes'    => ['nullable', 'string', 'max:200'],
        ]);

        $table->update($data);

        return redirect()->route('tables.index')
            ->with('success', "Table {$table->number} updated.");
    }

    public function destroy(Table $table): RedirectResponse
    {
        $table->delete();
        return redirect()->route('tables.index')
            ->with('success', "Table {$table->number} removed.");
    }

    public function updateStatus(Request $request, Table $table): \Illuminate\Http\RedirectResponse
    {
        $request->validate(['status' => ['required', 'string']]);
        $table->update(['status' => $request->status]);

        return redirect()->route('tables.index')
            ->with('success', "Stol {$table->number} → {$table->fresh()->status->label()}");
    }
    public function qrCodes(): \Illuminate\View\View
    {
        $tables = \App\Models\Table::orderBy('number')->get()->map(function ($table) {
            $url = url("/menu?table={$table->id}");
            $table->qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate($url);
            return $table;
        });
        return view('menu.qr', compact('tables'));
    }
}
