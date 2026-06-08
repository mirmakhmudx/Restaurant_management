<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $date   = $request->query('date', today()->toDateString());
        $status = $request->query('status', 'all');

        $query = Reservation::with('table')
            ->whereDate('reserved_at', $date)
            ->orderBy('reserved_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reservations = $query->get();
        $tables       = Table::orderBy('number')->get();

        $counts = [
            'today'    => Reservation::whereDate('reserved_at', today())->count(),
            'pending'  => Reservation::whereDate('reserved_at', today())->where('status','pending')->count(),
            'upcoming' => Reservation::whereDate('reserved_at', '>', today())->count(),
        ];

        // Joriy oy uchun bronli kunlar
        $month     = $request->query('month', now()->format('Y-m'));
        $monthDate = \Carbon\Carbon::parse($month.'-01');
        $busyDays  = Reservation::whereYear('reserved_at', $monthDate->year)
            ->whereMonth('reserved_at', $monthDate->month)
            ->whereNotIn('status', ['cancelled','no_show'])
            ->get()
            ->groupBy(fn($r) => $r->reserved_at->format('Y-m-d'))
            ->map(fn($g) => $g->count());

        return view('reservations.index', compact(
            'reservations','tables','date','status','counts','busyDays','monthDate'
        ));
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load('table');
        return view('reservations.show', compact('reservation'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'guest_name'  => ['required','string','max:100'],
            'guest_phone' => ['required','string','max:20'],
            'guest_email' => ['nullable','email'],
            'table_id'    => ['nullable','exists:tables,id'],
            'reserved_at' => ['required','date','after:now'],
            'guest_count' => ['required','integer','min:1','max:20'],
            'notes'       => ['nullable','string','max:300'],
        ]);

        $reservation = Reservation::create($data);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', "{$data['guest_name']} uchun bron muvaffaqiyatli qilindi.");
    }

    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse
    {
        $request->validate(['status' => ['required','in:pending,confirmed,seated,cancelled,no_show']]);
        $reservation->update(['status' => $request->status]);

        if ($request->status === 'seated' && $reservation->table_id) {
            Table::find($reservation->table_id)?->update(['status' => 'occupied']);
        }

        return back()->with('success', "Bron holati yangilandi.");
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $reservation->delete();
        return redirect()->route('reservations.index')
            ->with('success', "Bron o'chirildi.");
    }
}
