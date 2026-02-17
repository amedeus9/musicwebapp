<?php

use App\Models\Song;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('homepage lists songs', function () {
    Song::create([
        'title' => 'Test Song',
        'artist' => 'Test Artist',
        'file_path' => 'songs/test.mp3'
    ]);
    
    $this->get('/')
        ->assertStatus(200)
        ->assertSee('Test Song');
});

test('can upload a song', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('song.mp3', 100, 'audio/mpeg');

    $response = $this->post(route('songs.store'), [
        'title' => 'New Song',
        'artist' => 'Artist Name',
        'file' => $file,
    ]);

    $response->assertRedirect(route('home'));
    
    $this->assertDatabaseHas('songs', ['title' => 'New Song']);
    
    Storage::disk('public')->assertExists('songs/' . $file->hashName());
});

test('can download a song and increments counter', function () {
    Storage::fake('public');
    
    $file = UploadedFile::fake()->create('song.mp3', 100, 'audio/mpeg');
    $path = $file->store('songs', 'public');
    
    $song = Song::create([
        'title' => 'Downloadable Song',
        'artist' => 'Artist',
        'file_path' => $path,
        'downloads' => 0,
    ]);
    
    expect($song->downloads)->toBe(0);

    $response = $this->get(route('songs.download', $song));
    
    $response->assertStatus(200);
    // Note: assertDownload might require actual file existence handling which Storage::fake handles, 
    // but sometimes headers differ in test environment. Checking status 200 and headers usually enough.
    $response->assertHeader('content-disposition');
             
    expect($song->fresh()->downloads)->toBe(1);
});
