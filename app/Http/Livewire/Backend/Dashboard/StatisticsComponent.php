<?php

namespace App\Http\Livewire\Backend\Dashboard;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StatisticsComponent extends Component
{

    public $monthLabels = [];
    public $monthValues = [];

    public $currentMonthEarning = 0;
    public $currentAnnualEarning = 0;
    public $currentMonthOrderNew = 0;
    public $currentMonthOrderFinished = 0;


    public function mount()
    {
        $this->currentMonthEarning = Order::whereOrderStatus(Order::FINISHED)->whereMonth('created_at', date('m'))->sum('total');
        $this->currentAnnualEarning = Order::whereOrderStatus(Order::FINISHED)->whereYear('created_at', date('Y'))->sum('total');
        $this->currentMonthOrderNew = Order::whereOrderStatus(Order::NEW_ORDER)->whereMonth('created_at', date('m'))->count();
        $this->currentMonthOrderFinished = Order::whereOrderStatus(Order::FINISHED)->whereMonth('created_at', date('m'))->count();


        $orders = Order::select(DB::raw('SUM(total) as revenue'), DB::raw('Month(created_at) as month'))
            ->whereOrderStatus(Order::FINISHED)
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('Month(created_at)'))
            ->pluck('revenue', 'month');


        foreach ($orders->keys() as $month_number) {
            $this->monthLabels[] = date('F', mktime(0, 0, 0, $month_number, 1));
        }
        $this->monthValues = $orders->values()->toArray();
    }



    public function render()
    {
        return view('livewire.backend.dashboard.statistics-component');
    }
}
