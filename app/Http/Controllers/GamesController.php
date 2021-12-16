<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $game = Http::withHeaders(config('services.igdb.headers'))->withBody("
            fields name, cover.url, first_release_date, platforms.abbreviation, rating, slug, involved_companies.company.name, genres.name, aggregated_rating, summary, websites.*, videos.*, screenshots.*, similar_games.cover.url, similar_games.name, similar_games.rating, similar_games.platforms.abbreviation, similar_games.slug;
            where slug=\"{$slug}\";", "text/plain")->post(config('services.igdb.endpoint'))->json();

        abort_if(!$game, 404);

        return view('show', [
            'game' => $this->formatGameForView($game[0]),
        ]);
    }

    private function formatGameForView($game)
    {
        return collect($game)->merge([
            'cover' => array_key_exists('cover', $game) ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) : 'https://images.igdb.com/igdb/image/upload/t_cover_big/nocover.png',
            'genres' => array_key_exists('genres', $game) ? collect($game['genres'])->pluck('name')->implode(', ') . ' ·' : null,
            'involved_companies' => array_key_exists('involved_companies', $game) ? collect($game['involved_companies'])->pluck('company')->pluck('name')->implode(', ') . ' ·' : null,
            'platforms' => array_key_exists('platforms', $game) ? collect($game['platforms'])->pluck('abbreviation')->implode(', ') : null,
            'rating' => isset($game['rating']) ? round($game['rating']).'%' : '0%',
            'aggregated_rating' => isset($game['aggregated_rating']) ? round($game['aggregated_rating']).'%' : '0%',
            'official' => collect($game['websites'])->where('category', 1)->pluck('url')->implode(null),
            'instagram' => collect($game['websites'])->where('category', 8)->pluck('url')->implode(null),
            'twitter' => collect($game['websites'])->where('category', 5)->pluck('url')->implode(null),
            'facebook' => collect($game['websites'])->where('category', 4)->pluck('url')->implode(null),
            'trailer' => 'https://youtube.com/watch/'.$game['videos'][0]['video_id'],
            'screenshots' => array_key_exists('screenshots', $game) ? collect($game['screenshots'])->map(function ($screenshot) {
                return [
                    'big' => Str::replaceFirst('thumb', 'screenshot_big', $screenshot['url']),
                    'huge' => Str::replaceFirst('thumb', 'screenshot_huge', $screenshot['url']),
                ];
            })->take(9) : null,
            'similar_games' => array_key_exists('similar_games', $game) ? collect($game['similar_games'])->map(function ($game) {
                return collect($game)->merge([
                    'cover' => array_key_exists('cover', $game) ? Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) : 'https://images.igdb.com/igdb/image/upload/t_cover_big/nocover.png',
                    'rating' => isset($game['rating']) ? round($game['rating']).'%' : '0%',
                    'platforms' => array_key_exists('platforms', $game) ? collect($game['platforms'])->pluck('abbreviation')->implode(', ') : null,
                ]);
            })->take(6) : null,
        ])->toArray();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
