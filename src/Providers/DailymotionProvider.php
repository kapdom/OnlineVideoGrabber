<?php

declare(strict_types=1);

namespace KapDom\OnlineVideoGrabber\Providers;

use KapDom\OnlineVideoGrabber\Exceptions\VideoGrabberException;
use KapDom\OnlineVideoGrabber\Models\Video;

class DailymotionProvider extends VideoProvider
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
        if (strpos($url, '?')) {
            $url = explode('?', $url)[0];
        }

        if (!$this->checkIfVideoExists($url)){
            throw new VideoGrabberException('Video in Dailymotion service not exists. Url: '.$url);
        }

        $linkArr = explode('/',trim($url,'/'));
        $arrCount = count($linkArr)-1;
        $movieId = $linkArr[$arrCount];

        $thumbnail = 'http://www.dailymotion.com/thumbnail/video/'.$movieId;
        $embed = 'http://www.dailymotion.com/embed/video/'.$movieId;

        return new Video(self::DAILYMOTION, $url, $embed, $thumbnail);
    }
}
