<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Store;
use App\Models\AllowedNetwork;

class CreateStoreAndNetwork extends Command
{
    protected $signature = 'app:create-store-and-network';
    protected $description = 'Create default store and network settings';

    public function handle()
    {
        // Create store if not exists
        $store = Store::firstOrCreate(
            ['name' => 'Main Store'],
            [
                'lat' => 14.652848,
                'lng' => 121.045295,
                'radius_meters' => 50,
                'active' => true,
            ]
        );
        
        $this->info("Store created: {$store->name}");

        // Create network if not exists
        $network = AllowedNetwork::firstOrCreate(
            ['name' => 'Office Network'],
            [
                'ip_ranges' => ['136.158.37.82'],
                'active' => true,
            ]
        );
        
        $this->info("Network created: {$network->name}");
        $this->info("Setup complete!");
    }
}
