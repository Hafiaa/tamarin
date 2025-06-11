<?php

namespace App\Console\Commands;

use Filament\Facades\Filament;
use Illuminate\Console\Command;

class ListFilamentResources extends Command
{
    protected $signature = 'filament:list-resources';
    protected $description = 'List all registered Filament resources';

    public function handle()
    {
        $panel = Filament::getPanel('admin');
        $resources = $panel->getResources();
        
        $this->info('Registered Resources:');
        foreach ($resources as $resource) {
            $this->line("- {$resource}");
        }
        
        return Command::SUCCESS;
    }
}
