<div class="game flex">
    <div class="flex-none">
        <a href="{{ route('games.show', $game['slug']) }}">
            <img src="{{ $game['cover'] }}" alt="game cover" class="w-16 hover:opacity-75 transition ease-in-out duration-150">
        </a>
    </div>
    <div class="ml-4">
        <a href="{{ route('games.show', $game['slug']) }}" class="hover:text-gray-300">{{ $game['name'] }}</a>
        <div class="text-gray-400 text-sm mt-1">{{ $game['first_release_date'] }}</div>
    </div>
</div>