<?php

declare(strict_types=1);

namespace KapDom\OnlineVideoGrabber\Providers;

use DOMDocument;
use KapDom\OnlineVideoGrabber\Models\Video;

abstract class VideoProvider
{
    public const YOUTUBE = 'youtube';
    public const VIMEO = 'vimeo';
    public const DAILYMOTION = 'dailymotion';
    public const LIVELEAK = 'liveleak';
    public const METACAFE = 'metacafe';

    /**
     * @param string $url
     *
     * @return Video
     */
    abstract public function getVideoData(string $url): Video;

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function checkIfVideoExists(string $url): bool
    {
        $file_headers = get_headers($url);
        return $file_headers && $file_headers[0] && strpos($file_headers[0], '200');
    }

    /**
     * @param string $url
     *
     * @return DOMDocument
     */
    protected function getVideoHtmlDom(string $url): DOMDocument
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);
        curl_close($ch);
        $doc = new DOMDocument();
        @$doc->loadHTML($data);

        return $doc;
    }
}
