@foreach($songs as $index => $song)
    <x-song-item :song="$song" :index="$index" :songs="$songs" />
@endforeach
