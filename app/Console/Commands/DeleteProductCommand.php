<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Products have been successfully deleted!';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::all();

        foreach ($products as $product) {
            if (Carbon::now()->day == Carbon::create($product->created_at)->day && Carbon::create($product->created_at)->month < Carbon::now()->month) {
                if (Storage::exists($product->file_path)) Storage::delete($product->file_path);
                $product->delete();
            }
        }

        echo Carbon::now() . ': ' . $this->description;
    }
}
