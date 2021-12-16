<div wire:init="loadPopularGames" class="popular-games text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-6 gap-12 border-b border-gray-800 pb-8">
    @forelse ($popularGames as $game)
        <x-game-card :game="$game" />
    @empty
        @foreach (range(1, 12) as $game)
            <div class="animate-pulse">
                <div class="game">
                    <div class="flex justify-center md:block">
                        <div class="relative inline-block">
                            <div class="bg-gray-800 w-44 h-56"></div>
                        </div>
                    </div>
                    <div class="block text-transparent text-lg bg-gray-700 rounded font-semibold leading-tight mt-4">Title goes here</div>
                    <div class="text-transparent bg-gray-700 inline-block rounded mt-3">PS4, PC, Switch</div>
                </div>
            </div>
        @endforeach
    @endforelse
</div> {{-- end popular-games --}}
