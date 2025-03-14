<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Modules\Order\Entities\OrderItem;
use Modules\Order\Entities\Order;

class ChangePendingOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change Order Status if not paid in 15 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limitation = env('BACK_PRODUCT_DURATION'); // 15 minutes
        $orders = Order::whereIn('order_status_id',[1,5])->where('created_at','<',Carbon::now()->subMinutes($limitation))->orderBy('id','desc')->get();
        foreach ($orders as $order){
            $order->update(['order_status_id'=>3]);
            foreach ($order->orderItems as $item) {
                $item->product->increment('qty', $item->qty);
            }
        }
        return true;
    }
}
