<?php

namespace App\Http\Controllers;

use App\Models\StaffShift;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffShiftController extends Controller
{
    public function index(Request $request): View
    {
        $staffId = $request->query('staff', '');
        $week    = $request->query('week', now()->startOfWeek()->toDateString());
        $weekStart = \Carbon\Carbon::parse($week)->startOfWeek();
        $weekEnd   = $weekStart->copy()->endOfWeek();

        $query = StaffShift::with(['user','creator'])
            ->whereBetween('shift_start', [$weekStart, $weekEnd])
            ->latest('shift_start');

        if ($staffId) {
            $query->where('user_id', $staffId);
        }

        $shifts = $query->get();

        // Har bir xodim uchun statistika
        $staffStats = $shifts->groupBy('user_id')->map(function ($group) {
            $totalMins = $group->sum(fn($s) => $s->getDurationMinutes());
            return [
                'user'       => $group->first()->user,
                'shifts'     => $group->count(),
                'total_mins' => $totalMins,
                'hours'      => round($totalMins / 60, 1),
                'active'     => $group->filter(fn($s) => $s->isActive())->count(),
            ];
        })->sortByDesc('total_mins');

        $allStaff    = User::where('is_active', true)->orderBy('name')->get();
        $activeCount = StaffShift::whereNull('shift_end')->count();

        // Haftalik jadval uchun kunlar
        $weekDays = collect();
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->copy()->addDays($i);
            $weekDays->push([
                'date'   => $day,
                'shifts' => $shifts->filter(fn($s) => $s->shift_start->isSameDay($day)),
            ]);
        }

        return view('staff.shifts', compact(
            'shifts','staffStats','allStaff','staffId',
            'week','weekStart','weekEnd','activeCount','weekDays'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id'     => ['required', 'exists:users,id'],
            'shift_start' => ['required', 'date'],
            'shift_end'   => ['nullable', 'date', 'after:shift_start'],
            'type'        => ['required', 'in:regular,morning,evening,night,overtime'],
            'notes'       => ['nullable', 'string', 'max:200'],
        ]);

        $data['created_by'] = auth()->id();

        StaffShift::create($data);

        return back()->with('success', "Navbat qo'shildi.");
    }

    public function clockIn(Request $request): RedirectResponse
    {
        $request->validate(['user_id' => ['required', 'exists:users,id']]);

        $existing = StaffShift::where('user_id', $request->user_id)
            ->whereNull('shift_end')->first();

        if ($existing) {
            return back()->with('error', 'Bu xodim hozir navbatda.');
        }

        StaffShift::create([
            'user_id'     => $request->user_id,
            'created_by'  => auth()->id(),
            'shift_start' => now(),
            'type'        => $request->type ?? 'regular',
            'notes'       => $request->notes,
        ]);

        $staff = User::find($request->user_id);
        return back()->with('success', "{$staff->name} navbat boshladi — " . now()->format('H:i'));
    }

    public function clockOut(StaffShift $shift): RedirectResponse
    {
        if ($shift->shift_end) {
            return back()->with('error', 'Bu navbat allaqachon yakunlangan.');
        }

        $shift->update(['shift_end' => now()]);

        return back()->with('success',
            "{$shift->user->name} navbat yakunladi — {$shift->getFormattedDuration()}"
        );
    }

    public function destroy(StaffShift $shift): RedirectResponse
    {
        $shift->delete();
        return back()->with('success', "Navbat o'chirildi.");
    }
}
