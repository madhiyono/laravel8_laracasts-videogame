<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class PopularGames extends Component
{
    public $popularGames = [];

    public function loadPopularGames(){
        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;

        $this->popularGames = Cache::remember('popular-games', 7, function () use ($before, $after) {
            // sleep(3);
            return Http::withHeaders(config('services.igdb.headers'))->withBody("
            fields name, cover.url, first_release_date, platforms.abbreviation, rating, slug;
            where platforms = (48,49,130,6) & (first_release_date >= {$before} & first_release_date < {$after} & rating != null);
            sort rating desc;
            limit 12;", "text/plain")->post(config('services.igdb.endpoint'))->json();
        });
    }

    public function render()
    {
        return view('livewire.popular-games');
    }
}
