<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ClearUnPaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Order::whereNull('paid_at')->where('created_at', '<', now()->subHours())->get();
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->productAttribute) {
                    $productAttribute = $item->productAttribute;
                    $productAttribute->quantity += $item->quantity;
                    $productAttribute->save();
                } else {
                    $product = $item->product;
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }
        }

        return Command::SUCCESS;
    }
}
