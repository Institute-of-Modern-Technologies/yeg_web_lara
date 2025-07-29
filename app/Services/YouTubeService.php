<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class YouTubeService
{
    protected $apiKey;
    protected $channelId = 'UCj_s79IpT7JY9BqUBT0hTuw'; // Young Experts Group channel ID
    protected $cacheTime = 3600; // Cache for 1 hour

    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key', env('YOUTUBE_API_KEY'));
    }

    /**
     * Get all videos from the channel (both regular videos and shorts)
     */
    public function getAllVideos($limit = 50)
    {
        return Cache::remember('youtube_all_videos', $this->cacheTime, function () use ($limit) {
            $videos = $this->getChannelVideos($limit);
            $this->addVideoDetails($videos);
            return $videos;
        });
    }

    /**
     * Get videos from the channel
     */
    protected function getChannelVideos($limit = 50)
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'key' => $this->apiKey,
            'channelId' => $this->channelId,
            'part' => 'snippet',
            'order' => 'date',
            'maxResults' => $limit,
            'type' => 'video'
        ]);

        if ($response->failed()) {
            return [];
        }

        $data = $response->json();
        $videos = [];

        foreach ($data['items'] as $item) {
            $videos[] = [
                'id' => $item['id']['videoId'],
                'title' => $item['snippet']['title'],
                'description' => $item['snippet']['description'],
                'thumbnail' => $item['snippet']['thumbnails']['high']['url'],
                'publishedAt' => $item['snippet']['publishedAt'],
                'isShort' => false, // Will be updated in addVideoDetails
            ];
        }

        return $videos;
    }

    /**
     * Add additional video details including duration to determine if it's a short
     */
    protected function addVideoDetails(&$videos)
    {
        if (empty($videos)) {
            return;
        }

        $videoIds = array_column($videos, 'id');
        $chunks = array_chunk($videoIds, 50); // YouTube API limit

        foreach ($chunks as $chunk) {
            $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
                'key' => $this->apiKey,
                'id' => implode(',', $chunk),
                'part' => 'contentDetails,statistics'
            ]);

            if ($response->failed()) {
                continue;
            }

            $data = $response->json();

            foreach ($data['items'] as $item) {
                $videoId = $item['id'];
                $duration = $this->parseDuration($item['contentDetails']['duration']);
                $views = $item['statistics']['viewCount'] ?? 0;
                $likes = $item['statistics']['likeCount'] ?? 0;

                // Find video in our array and update it
                foreach ($videos as &$video) {
                    if ($video['id'] === $videoId) {
                        $video['duration'] = $duration;
                        $video['views'] = $views;
                        $video['likes'] = $likes;
                        // YouTube Shorts are typically 60 seconds or less
                        $video['isShort'] = $duration <= 60;
                        break;
                    }
                }
            }
        }
    }

    /**
     * Parse ISO 8601 duration format to seconds
     */
    protected function parseDuration($duration)
    {
        $pattern = '/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/';
        preg_match($pattern, $duration, $matches);

        $hours = isset($matches[1]) ? intval($matches[1]) : 0;
        $minutes = isset($matches[2]) ? intval($matches[2]) : 0;
        $seconds = isset($matches[3]) ? intval($matches[3]) : 0;

        return $hours * 3600 + $minutes * 60 + $seconds;
    }

    /**
     * Format duration for display
     */
    public function formatDuration($seconds)
    {
        if ($seconds < 60) {
            return $seconds . 's';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            return $minutes . ':' . str_pad($remainingSeconds, 2, '0', STR_PAD_LEFT);
        } else {
            $hours = floor($seconds / 3600);
            $remainingSeconds = $seconds % 3600;
            $minutes = floor($remainingSeconds / 60);
            $seconds = $remainingSeconds % 60;
            return $hours . ':' . str_pad($minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
        }
    }
}
