<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PopularGames extends Component
{
    public $popularGames = [];

    public function loadPopularGames(){
        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;

        $popularGamesUnformatted = Cache::remember('popular-games', 7, function () use ($before, $after) {
            // sleep(3);
            return Http::withHeaders(config('services.igdb.headers'))->withBody("
            fields name, cover.url, platforms.abbreviation, rating, slug;
            where platforms = (48,49,130,6) & (first_release_date >= {$before} & first_release_date < {$after} & rating != null);
            sort rating desc;
            limit 12;", "text/plain")->post(config('services.igdb.endpoint'))->json();
        });

        // dd($this->formatForView($popularGamesUnformatted));
        $this->popularGames = $this->formatForView($popularGamesUnformatted);

        collect($this->popularGames)->filter(function ($game) {
            return $game['rating'];
        })->each(function ($game) {
            $this->emit('gameWithRatingAdded', [
                'slug' => $game['slug'],
                'rating' => $game['rating'] / 100
            ]);
        });
    }

    public function render()
    {
        return view('livewire.popular-games');
    }

    public function formatForView($games)
    {
        return collect($games)->map(function ($game) {
            return collect($game)->merge([
                'cover' => array_key_exists('cover', $game) ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) : 'https://images.igdb.com/igdb/image/upload/t_cover_big/nocover.png',
                'rating' => isset($game['rating']) ? round($game['rating']) : null,
                'platforms' => array_key_exists('platforms', $game) ? collect($game['platforms'])->pluck('abbreviation')->implode(', ') : null,
            ]);
        })->toArray();
    }
}
