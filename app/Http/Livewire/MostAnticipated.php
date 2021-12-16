<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class MostAnticipated extends Component
{
    public $mostAnticipated = [];

    public function loadMostAnticipated()
    {
        $current = Carbon::now()->timestamp;
        $afterFourMonths = Carbon::now()->addMonths(4)->timestamp;

        $mostAnticipatedUnformatted = Http::withHeaders(config('services.igdb.headers'))->withBody("
            fields name, cover.url, first_release_date, total_rating_count, slug;
            where platforms = (48,49,130,6) & (first_release_date >= {$current} & first_release_date < {$afterFourMonths});
            sort total_rating_count desc;
            limit 4;", "text/plain")->post(config('services.igdb.endpoint'))->json();

        // dd($this->formatForView($mostAnticipatedUnformatted));

        $this->mostAnticipated = $this->formatForView($mostAnticipatedUnformatted);
    }

    public function render()
    {
        return view('livewire.most-anticipated');
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
