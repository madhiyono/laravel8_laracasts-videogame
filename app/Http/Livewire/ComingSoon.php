<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ComingSoon extends Component
{
    public $comingSoon = [];

    public function loadComingSoon()
    {
        $current = Carbon::now()->timestamp;

        $comingSoonUnformatted = Http::withHeaders(config('services.igdb.headers'))->withBody("
            fields name, cover.url, first_release_date, slug;
            where platforms = (48,49,130,6) & (first_release_date >= {$current});
            sort first_release_date asc;
            limit 4;", "text/plain")->post(config('services.igdb.endpoint'))->json();

        // dd($this->formatForView($mostAnticipatedUnformatted));

        $this->comingSoon = $this->formatForView($comingSoonUnformatted);
    }

    public function render()
    {
        return view('livewire.coming-soon');
    }

    public function formatForView($games)
    {
        return collect($games)->map(function ($game) {
            return collect($game)->merge([
                'cover' => array_key_exists('cover', $game) ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) : 'https://images.igdb.com/igdb/image/upload/t_cover_big/nocover.png',
                'first_release_date' => Carbon::parse($game['first_release_date'])->format('M d, Y'),
            ]);
        })->toArray();
    }
}
