<?php

namespace App\Services;

use getID3;
use getid3_writetags;

class AudioMetadataService
{
    /**
     * Get basic metadata from an audio file.
     *
     * @param string $filePath Absolute path to the file.
     * @return array
     */
    public function getMetadata(string $filePath): array
    {
        // Require getID3 library files manually as they might not be autoloaded correctly
        if (!class_exists('getID3')) {
            $libPath = base_path('vendor/james-heinrich/getid3/getid3/getid3.php');
            if (file_exists($libPath)) {
                require_once $libPath;
            }
        }

        if (!class_exists('getID3')) {
             // Fallback or throw
             throw new \Exception('getID3 library not found.');
        }

        $getID3 = new \getID3;
        $fileInfo = $getID3->analyze($filePath);

        $tags = [];
        
        // Check for tags, prioritize ID3v2, then ID3v1
         if (isset($fileInfo['tags']['id3v2'])) {
             $tags = $fileInfo['tags']['id3v2'];
         } elseif (isset($fileInfo['tags']['id3v1'])) {
             $tags = $fileInfo['tags']['id3v1'];
         }

        return [
            'title' => $tags['title'][0] ?? $fileInfo['filename'] ?? 'Unknown Title',
            'artist' => $tags['artist'][0] ?? 'Unknown Artist',
            'album' => $tags['album'][0] ?? 'Unknown Album',
            'year' => $tags['year'][0] ?? null,
            'duration' => $fileInfo['playtime_string'] ?? '0:00',
            'duration_seconds' => $fileInfo['playtime_seconds'] ?? 0,
            'bitrate' => $fileInfo['audio']['bitrate'] ?? 0,
        ];
    }

    /**
     * Update/Write metadata and cover art to an MP3 file.
     *
     * @param string $filePath Absolute path to the file.
     * @param array $metadata Data to write (title, artist, album, year, comment).
     * @param string|null $coverPath Path to cover image to embed (optional).
     * @return bool True if successful.
     * @throws \Exception
     */
    public function updateMetadata(string $filePath, array $metadata, ?string $coverPath = null): bool
    {
        // Require getID3 library files manually as they might not be autoloaded correctly
        if (!class_exists('getID3')) {
            $libPath = base_path('vendor/james-heinrich/getid3/getid3/getid3.php');
            if (file_exists($libPath)) {
                require_once $libPath;
            }
        }
        
        if (!class_exists('getid3_writetags')) {
            $writePath = base_path('vendor/james-heinrich/getid3/getid3/write.php');
            if (file_exists($writePath)) {
                require_once $writePath;
            }
        }

        if (!class_exists('getID3') || !class_exists('getid3_writetags')) {
            throw new \Exception('getID3 library not found. Please run: composer require james-heinrich/getid3');
        }

        $encoding = 'UTF-8';

        // Initialize getID3 engine
        $getID3 = new \getID3;
        $getID3->setOption(array('encoding' => $encoding));

        // Initialize tag writer
        $tagwriter = new \getid3_writetags;
        $tagwriter->filename = $filePath;

        // Fetch Global Settings
        $settings = \App\Models\Setting::pluck('value', 'key');

        $tagwriter->tagformats = array('id3v2.3'); // Use ID3v2.3 for best compatibility
        
        // Remove ID3v1 if configured
        if (($settings['audio_remove_id3v1'] ?? '0') == '1') {
            $tagwriter->remove_other_tags = true; // Warning: This might remove other tags too, use with caution or specific setting
        } else {
            $tagwriter->remove_other_tags = false;
        }
        
        $tagwriter->overwrite_tags = true;
        $tagwriter->tag_encoding = $encoding;

        // Apply Defaults
        // STRICT: Only use Title, Artist, Year from input. Others from DB Settings.
        $album = $settings['audio_default_album'] ?? null;
        $genre = $settings['audio_default_genre'] ?? null;
        $comment = $settings['audio_default_comment'] ?? null;
        $copyright = $settings['audio_default_copyright'] ?? null;
        
        // Artist: Input primary, fallback to setting
        $artist = $metadata['artist'] ?? $settings['audio_default_artist'] ?? null;

        $tagData = [
            'title'     => isset($metadata['title']) ? [$metadata['title']] : [],
            'artist'    => $artist ? [$artist] : [],
            'album'     => $album ? [$album] : [],
            'year'      => isset($metadata['year']) ? [$metadata['year']] : [],
            'comment'   => $comment ? [$comment] : [],
            'genre'     => $genre ? [$genre] : [],
            'copyright' => $copyright ? [$copyright] : [],
        ];

        // Handle Cover Art
        // STRICT: ALWAYS use Default Audio Cover from DB Settings if available.
        // Ignore specific song cover input for ID3 tags if configured this way.
        
        $targetCoverPath = null;

        // 1. Try Default Audio Cover Setting
        if (!empty($settings['audio_default_cover'])) {
            $defPath = $settings['audio_default_cover'];
             if (\Illuminate\Support\Facades\Storage::disk('public')->exists($defPath)) {
                 $targetCoverPath = \Illuminate\Support\Facades\Storage::disk('public')->path($defPath);
             }
        }

        // 2. Fallback to Site Logo (if forced and default cover missing)
        if (!$targetCoverPath && ($settings['audio_force_site_cover'] ?? '0') == '1') {
            if (!empty($settings['site_logo'])) {
                 $logoPath = $settings['site_logo'];
                 if (\Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath)) {
                     $targetCoverPath = \Illuminate\Support\Facades\Storage::disk('public')->path($logoPath);
                 }
            }
        }
        
        // 3. Last resort: Input cover (OPTIONAL - remove strict if you want fallback)
        // For now, based on request "cover art also from db", we prioritize DB.
        // If DB has nothing, we can fallback to input or leave empty.
        if (!$targetCoverPath && $coverPath) {
             // Uncomment next line to fallback to song-specific cover if DB cover missing
             // $targetCoverPath = $coverPath; 
        }

        if ($targetCoverPath && file_exists($targetCoverPath)) {
            $imageData = file_get_contents($targetCoverPath);
            $mimeType = mime_content_type($targetCoverPath);

            // Normalize MIME types for better compatibility
            if ($mimeType == 'image/x-png') {
                $mimeType = 'image/png';
            } elseif ($mimeType == 'image/pjpeg') {
                $mimeType = 'image/jpeg';
            }
            
            // Should be strictly image/jpeg or image/png for ID3v2.3
            
            // Ensure we assign to index 0 to overwrite main cover
            $tagData['attached_picture'] = []; 
            $tagData['attached_picture'][0] = [
                'data'          => $imageData,
                'picturetypeid' => 0x03, // Cover (front)
                'description'   => 'cover',
                'mime'          => $mimeType,
            ];
        }

        $tagwriter->tag_data = $tagData;

        if ($tagwriter->WriteTags()) {
            if (!empty($tagwriter->warnings)) {
                 // Log warnings if needed: \Log::warning('ID3 Tags Warnings: ' . implode(', ', $tagwriter->warnings));
            }
            return true;
        } else {
             // Log errors: \Log::error('ID3 Tags Errors: ' . implode(', ', $tagwriter->errors));
             throw new \Exception('Failed to write tags: ' . implode(', ', $tagwriter->errors));
        }
    }
}
