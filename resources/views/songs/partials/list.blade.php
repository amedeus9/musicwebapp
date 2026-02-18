@foreach($songs as $song)
    <x-song-card :song="$song" />
@endforeach
