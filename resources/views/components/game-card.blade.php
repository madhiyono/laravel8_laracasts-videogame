<div class="game">
    <div class="flex justify-center md:block">
        <div class="relative inline-block">
            <a href="{{ route('games.show', $game['slug']) }}">
                <img src="{{ $game['cover'] }}" alt="game cover" class="hover:opacity-75 transition ease-in-out duration-150">
            </a>
            @if ($game['rating'])
                <div id="{{ $game['slug'] }}" class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full text-xs" style="right: -20px; bottom: -20px"></div>
            @endif
        </div>
    </div>
    <a href="{{ route('games.show', $game['slug']) }}" class="block text-base text-center md:text-left font-semibold leading-tight hover:text-gray-400 mt-8">
        {{ $game['name'] }}
    </a>
    <div class="text-gray-400 mt-1 text-center md:text-left">
        {{ $game['platforms'] }}
    </div>
</div>