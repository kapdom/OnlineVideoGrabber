<?php

declare(strict_types=1);

namespace KapDom\OnlineVideoGrabber\Providers;

use KapDom\OnlineVideoGrabber\Exceptions\VideoGrabberException;
use KapDom\OnlineVideoGrabber\Models\Video;

class MetacafeProvider extends VideoProvider
{
    /**
     * @param string $url
     * @return Video
     *
     * @throws VideoGrabberException
     */
    public function getVideoData(string $url): Video
    {
        if (!$this->checkIfVideoExists($url)) {
            throw new VideoGrabberException('Video in MetaCafe service not exists. Url: '.$url);
        }

        $urlArr = explode('/',$url);

        if (!isset($urlArr[4]) || !is_numeric($urlArr[4])) {
            throw new VideoGrabberException('Unable to find movie id on this url or id is not correct: '.$url);
        }

        $movieId = $urlArr[4];

        $thumbnail = $this->getThumb($url);
        $embed = 'http://www.metacafe.com/embed/'.$movieId.'/';

        return new Video(self::METACAFE, $url, $embed, $thumbnail);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getThumb(string $url): string
    {
        $htmlDom = $this->getVideoHtmlDom($url);

        $metas = $htmlDom->getElementsByTagName('meta');

        for ($i = 0; $i < $metas->length; $i++)
        {
            $meta = $metas->item($i);
            if ($meta !== null && $meta->getAttribute('property') === 'og:image') {
                return $meta->getAttribute('content');
            }
        }

        return '';
    }
}
