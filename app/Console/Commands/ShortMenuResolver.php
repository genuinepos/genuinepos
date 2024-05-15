<?php

namespace App\Console\Commands;

use App\Models\ShortMenu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class ShortMenuResolver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:menus';

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
        echo 'Fixing menus' . PHP_EOL;
        echo '--------------------' . PHP_EOL;

        $collection = Route::getRoutes();
        $routes = [];
        foreach ($collection as $route) {
            $routeName = $route->getName();
            if (isset($routeName)) {
                $routes[] = $routeName;
            }
        }

        $shortMenus = ShortMenu::get();
        $shortMenus->map(function ($menu) use ($routes) {
            if (!in_array($menu->url, $routes)) {
                echo "$menu->url is not in route definition ";
                ShortMenu::where('id', $menu->id)->delete();
                echo " DELETED\n";
            }
        });

        echo '--------------------' . PHP_EOL;
    }
}
