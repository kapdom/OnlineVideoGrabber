<?php

declare(strict_types=1);

namespace KapDom\OnlineVideoGrabber\Providers;

use KapDom\OnlineVideoGrabber\Exceptions\VideoGrabberException;
use KapDom\OnlineVideoGrabber\Models\Video;

class YoutubeProvider extends VideoProvider
{
    /**
     * @param string $url
     *
     * @return Video
     *
     * @throws VideoGrabberException
     */
    public function getVideoData(string $url): Video
    {
        $movieId = null;

        if(!strpos($url,'youtu.be')) {

            $linkArr = explode('watch?v=', $url);
            if (isset($linkArr[1]) && strpos($linkArr[1], '&' )) {
                $videoIdArr = explode('&', $linkArr[1]);
                $movieId = $videoIdArr[0] ?? null;

            } else {
                $movieId = $linkArr[1] ?? null;
            }

        } else {
            $videoIdArr = explode('be/',$url);
            if( isset($videoIdArr[1]) && strpos($videoIdArr[1],'?' )){
                $linkWithTime = explode('?',$videoIdArr[1]);
                $movieId = $linkWithTime[0] ?? null;
            } else {
                $movieId = $videoIdArr[1] ?? null;
            }
        }

        if ($movieId === null) {
            throw new VideoGrabberException('Unable to find movie id on this url: '.$url);
        }

        if (!$this->checkIfVideoExists($movieId)) {
            throw new VideoGrabberException('Youtube video not find. Url: '.$url);
        }

        $thumbnail = $this->findBestQualityYoutubeThumbnail($movieId);
        $embed  = 'https://www.youtube.com/embed/'.$movieId;

        return new Video(self::YOUTUBE, $url, $embed, $thumbnail);
    }

    /**
     * @param string $videoId
     *
     * @return string
     */
    private function findBestQualityYoutubeThumbnail(string $videoId): string
    {
        $imgQuality = ['maxresdefault.jpg', 'hqdefault.jpg', 'mqdefault.jpg', 'sddefault.jpg', 'default.jpg'];

        foreach ($imgQuality as $item) {
            $file = 'http://img.youtube.com/vi/'.$videoId.'/'.$item;
            $file_headers = get_headers($file);
            if( $file_headers && $file_headers[0] && strpos($file_headers[0], '200')) {

                return $file;
            }
        }

        return '';
    }

    /**
     * @param string $videoId
     *
     * @return bool
     */
    protected function checkIfVideoExists(string $videoId): bool
    {
        $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $videoId);

        if(is_array($headers) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$headers[0]) : false){
            return true;
        }

        return false;
    }
}
