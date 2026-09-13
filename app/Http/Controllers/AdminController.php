<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Otomatis ubah status pesanan yang lewat tanggal
        Order::autoUpdateExpiredStatuses();

        $stats = [
            'users' => User::where('is_admin', false)->count(),
            'total_revenue' => Order::sum('paid_amount') - Order::sum('refund_amount'),
            'packages' => Package::count(),
            'orders' => Order::count(),
        ];

        // Chart Data: Last 6 months
        $months = collect([]);
        $revenueData = collect([]);
        $ordersData = collect([]);
        
        for ($i = 5; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));
            
            // Calculate net revenue for that month (paid_amount - refund_amount, termasuk dana ditahan dari pembatalan)
            $paid = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('paid_amount');
            $refund = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('refund_amount');
            $revenueData->push($paid - $refund);

            // Calculate orders for that month
            $orderCount = Order::where('status', '!=', 'cancelled')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $ordersData->push($orderCount);
        }

        $chartData = [
            'labels' => $months->toArray(),
            'revenue' => $revenueData->toArray(),
            'orders' => $ordersData->toArray()
        ];
        
        $recentInquiries = \App\Models\Inquiry::latest()->take(4)->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'recentInquiries'));
    }

    public function calendar()
    {
        // Otomatis ubah status pesanan yang lewat tanggal
        Order::autoUpdateExpiredStatuses();

        $orders = \App\Models\Order::where('status', '!=', 'cancelled')->with('user', 'package')->get();
        
        $events = [];
        foreach ($orders as $order) {
            $events[] = [
                'title' => $order->user->name . ' - ' . $order->package->name,
                'start' => \Carbon\Carbon::parse($order->wedding_date)->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($order->start_time)->format('H:i:s'),
                'end' => \Carbon\Carbon::parse($order->wedding_date)->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($order->end_time)->format('H:i:s'),
                'url' => route('admin.orders.show', $order),
                'color' => $order->status === 'paid' ? '#198754' : '#ffc107',
                'textColor' => $order->status === 'paid' ? '#fff' : '#000',
            ];
        }

        return view('admin.calendar', compact('events'));
    }
}
