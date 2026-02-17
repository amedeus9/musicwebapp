<?php

use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('dashboard page loads and shows latest releases', function () {
    // Create some songs
    Song::create([
        'title' => 'Song 1',
        'artist' => 'Artist 1',
        'file_path' => 'songs/song1.mp3',
        'downloads' => 10,
    ]);

    Song::create([
        'title' => 'Song 2',
        'artist' => 'Artist 2',
        'file_path' => 'songs/song2.mp3',
        'downloads' => 5,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertViewIs('dashboard');
    $response->assertSee('Latest Releases');
    $response->assertSee('All Songs');
    $response->assertSee('Song 1');
    $response->assertSee('Song 2');
});
