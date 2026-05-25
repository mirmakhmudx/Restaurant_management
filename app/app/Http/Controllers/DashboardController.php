<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user  = $request->user();

        $stats = match(true) {
            $user->isManager() => [
                ['label' => 'Total Orders Today', 'value' => '0',  'icon' => '📋'],
                ['label' => 'Active Tables',       'value' => '0',  'icon' => '🪑'],
                ['label' => "Today's Revenue",     'value' => '£0', 'icon' => '💰'],
                ['label' => 'Staff On Duty',       'value' => '4',  'icon' => '👥'],
            ],
            $user->isWaiter() => [
                ['label' => 'My Active Orders', 'value' => '0', 'icon' => '📋'],
                ['label' => 'Tables Assigned',  'value' => '0', 'icon' => '🪑'],
                ['label' => 'Orders Served',    'value' => '0', 'icon' => '✅'],
                ['label' => 'Pending Bills',    'value' => '0', 'icon' => '⏳'],
            ],
            $user->isChef() => [
                ['label' => 'Orders in Queue', 'value' => '0', 'icon' => '🔥'],
                ['label' => 'Preparing Now',   'value' => '0', 'icon' => '👨‍🍳'],
                ['label' => 'Ready to Serve',  'value' => '0', 'icon' => '✅'],
                ['label' => 'Completed Today', 'value' => '0', 'icon' => '📊'],
            ],
            $user->isCashier() => [
                ['label' => 'Bills Pending',    'value' => '0',  'icon' => '⏳'],
                ['label' => 'Paid Today',       'value' => '0',  'icon' => '💳'],
                ['label' => "Today's Revenue",  'value' => '£0', 'icon' => '💰'],
                ['label' => 'Avg Bill Value',   'value' => '£0', 'icon' => '📊'],
            ],
            default => [],
        };

        return view('dashboard.index', compact('stats'));
    }
}
