<?php

namespace App\Console\Commands;

use App\Models\CartItem;
use Illuminate\Console\Command;

class ClearCartItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:cart-items';

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
        $cartItems = CartItem::where('created_at', '<', now()->subHours(1))->get();

        foreach ($cartItems as $cartItem) {
            if ($cartItem->productAttribute) {
                $productAttribute = $cartItem->productAttribute;
                $productAttribute->quantity += $cartItem->quantity;
                $productAttribute->saveOrFail();
            } else {
                $product = $cartItem->product;
                $product->quantity += $cartItem->quantity;
                $product->saveOrFail();
            }
            $cartItem->delete();
        }
        return Command::SUCCESS;
    }
}
