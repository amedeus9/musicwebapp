<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;

Route::get('/', [DashboardController::class, 'index'])->name('home');
// Album Routes
Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
Route::get('/albums/{album:slug}', [AlbumController::class, 'show'])->name('albums.show');

// Artist Routes
Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
// Artist Show Route Moved to Bottom for dynamic URL handling

// Song Routes
Route::get('/songs', [SongController::class, 'index'])->name('songs.index');
Route::get('/songs/create', [SongController::class, 'create'])->name('songs.create')->middleware('auth');
Route::post('/songs', [SongController::class, 'store'])->name('songs.store')->middleware('auth');
// Route::get('/songs/{song:slug}', [SongController::class, 'show'])->name('songs.show'); // Moved to dynamic route
Route::delete('/songs/{song:slug}', [SongController::class, 'destroy'])->name('songs.destroy')->middleware('auth');
Route::get('/songs/{song:slug}/download', [SongController::class, 'download'])->name('songs.download');

// New Routes
Route::get('/favorites', [SongController::class, 'favorites'])->name('favorites');
Route::get('/profile', [SongController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/profile/edit', [SongController::class, 'editProfile'])->name('profile.edit')->middleware('auth');
Route::put('/profile', [SongController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

// Interaction Routes
use App\Http\Controllers\InteractionController;
Route::post('/interactions/{type}/{id}/like', [InteractionController::class, 'toggleLike'])->name('interactions.like');
Route::post('/interactions/{type}/{id}/comment', [InteractionController::class, 'storeComment'])->name('interactions.comment');
Route::delete('/interactions/comment/{comment}', [InteractionController::class, 'deleteComment'])->name('interactions.deleteComment');

// Playlist Routes
use App\Http\Controllers\PlaylistController;
Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
Route::get('/playlists/create', [PlaylistController::class, 'create'])->name('playlists.create')->middleware('auth');
Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store')->middleware('auth');
Route::get('/playlists/{playlist:slug}', [PlaylistController::class, 'show'])->name('playlists.show');
Route::get('/playlists/{playlist:slug}/edit', [PlaylistController::class, 'edit'])->name('playlists.edit')->middleware('auth');
Route::put('/playlists/{playlist:slug}', [PlaylistController::class, 'update'])->name('playlists.update')->middleware('auth');
Route::delete('/playlists/{playlist:slug}', [PlaylistController::class, 'destroy'])->name('playlists.destroy')->middleware('auth');
Route::post('/playlists/{playlist:slug}/songs', [PlaylistController::class, 'addSong'])->name('playlists.addSong')->middleware('auth');
Route::delete('/playlists/{playlist:slug}/songs/{song:id}', [PlaylistController::class, 'removeSong'])->name('playlists.removeSong')->middleware('auth');
Route::post('/playlists/{playlist:slug}/invite', [PlaylistController::class, 'invite'])->name('playlists.invite')->middleware('auth');
Route::delete('/playlists/{playlist:slug}/collaborators/{user}', [PlaylistController::class, 'removeCollaborator'])->name('playlists.removeCollaborator')->middleware('auth');

// Authentication Routes
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect GET login/register to home since we use modals
Route::get('/login', function () { return redirect('/'); })->name('login');
Route::get('/register', function () { return redirect('/'); })->name('register');
Route::get('/password/reset', function() { return redirect('/'); })->name('password.request');

// Admin Routes
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SongController as AdminSongController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Songs Management
    Route::get('/songs', [AdminSongController::class, 'index'])->name('songs.index');
    Route::get('/songs/create', [AdminSongController::class, 'create'])->name('songs.create');
    Route::post('/songs', [AdminSongController::class, 'store'])->name('songs.store');
    Route::get('/songs/{song}/edit', [AdminSongController::class, 'edit'])->name('songs.edit');
    Route::put('/songs/{song}', [AdminSongController::class, 'update'])->name('songs.update');
    Route::delete('/songs/{song}', [AdminSongController::class, 'destroy'])->name('songs.destroy');

    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Artist Management
    Route::resource('artists', \App\Http\Controllers\Admin\ArtistController::class);

    // Placeholder routes - to be implemented
    Route::get('/albums', function () { return redirect()->route('admin.dashboard'); })->name('albums.index');
    Route::get('/playlists', function () { return redirect()->route('admin.dashboard'); })->name('playlists.index');
    Route::get('/comments', function () { return redirect()->route('admin.dashboard'); })->name('comments.index');
    // Settings Management
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

// Dynamic Artist Route (Must be last - 2 params)
Route::get('/{country:slug}/{artist:slug}', [ArtistController::class, 'show'])->name('artists.show');

// Dynamic Song Route (Must be last - 3 params)
Route::get('/{country:slug}/{artist:slug}/{song:slug}', [SongController::class, 'show'])->name('songs.show');
