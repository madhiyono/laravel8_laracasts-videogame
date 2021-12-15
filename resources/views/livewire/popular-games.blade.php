<div wire:init="loadPopularGames" class="popular-games text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-6 gap-12 border-b border-gray-800 pb-8">
    @forelse ($popularGames as $game)
        <div class="game">
            <div class="flex justify-center md:block">
                <div class="relative inline-block">
                    <a href="{{ route('games.show', $game['slug']) }}">
                        @if (array_key_exists('cover', $game))
                            <img src="{{ Str::replaceFirst('thumb', 'cover_big', $game['cover']['url']) }}" alt="game cover" class="hover:opacity-75 transition ease-in-out duration-150">
                        @else
                            <img src="https://images.igdb.com/igdb/image/upload/t_cover_big/nocover.png" alt="game cover" class="hover:opacity-75 transition ease-in-out duration-150">
                        @endif
                    </a>
                    @isset($game['rating'])
                        <div class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full" style="right: -20px; bottom: -20px">
                            <div class="font-semibold text-xs flex justify-center items-center h-full">
                                {{ round($game['rating']). '%' }}
                            </div>
                        </div>
                    @endisset
                </div>
            </div>
            <a href="{{ route('games.show', $game['slug']) }}" class="block text-base text-center md:text-left font-semibold leading-tight hover:text-gray-400 mt-8">
                {{ $game['name'] }}
            </a>
            <div class="text-gray-400 mt-1 text-center md:text-left">
                @foreach ($game['platforms'] as $platform)
                    @if (array_key_exists('abbreviation', $platform))
                        {{ $platform['abbreviation'] }}
                        @if (!$loop->last)
                            ,
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    @empty
        @foreach (range(1, 12) as $game)
            <div class="animate-pulse">
                <div class="game">
                    <div class="relative inline-block">
                        <div class="bg-gray-800 w-44 h-56"></div>
                    </div>
                    <div class="block text-transparent text-lg bg-gray-700 rounded font-semibold leading-tight mt-4">Title goes here</div>
                    <div class="text-transparent bg-gray-700 inline-block rounded mt-3">PS4, PC, Switch</div>
                </div>
            </div>
        @endforeach
    @endforelse
</div> {{-- end popular-games --}}
