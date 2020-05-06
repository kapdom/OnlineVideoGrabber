<?php

declare(strict_types=1);

namespace KapDom\OnlineVideoGrabber\Providers;

use DOMDocument;
use KapDom\OnlineVideoGrabber\Exceptions\VideoGrabberException;
use KapDom\OnlineVideoGrabber\Models\Video;

class LiveleakProvider extends VideoProvider
{
    /**
     * @var DOMDocument
     */
    private $liveLeakHtml;

    /**
     * @param string $url
     *
     * @return Video
     *
     * @throws VideoGrabberException
     */
    public function getVideoData(string $url): Video
    {
        $this->liveLeakHtml = $this->getVideoHtmlDom($url);

        If (!$this->checkIfVideoExists($url)) {
            throw new VideoGrabberException('Video in LiveLeak service not exists. Url: '.$url);
        }

        $linkArr = explode('view?t=',trim($url,'/'));
        if (!isset($linkArr[1])) {
            throw new VideoGrabberException('Unable to find movie id on this url: '.$url);
        }

        $movieId = $linkArr[1];

        $thumbnail = $this->getThumb();
        $embed = 'http://www.liveleak.com/ll_embed?f='.$movieId;

        return new Video(self::LIVELEAK, $url, $embed, $thumbnail);
    }

    /**
     * @return string
     */
    private function getThumb(): string
    {
        $metas = $this->liveLeakHtml->getElementsByTagName('meta');

        for ($i = 0; $i < $metas->length; $i++)
        {
            $meta = $metas->item($i);
            if ($meta !== null && $meta->getAttribute('property') === 'og:image') {
                return $meta->getAttribute('content');
            }
        }

        return '';
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function checkIfVideoExists(string $url): bool
    {
        $metas = $this->liveLeakHtml->getElementsByTagName('meta');

        for ($i = 0; $i < $metas->length; $i++)
        {
            $meta = $metas->item($i);
            if ($meta !== null
                && $meta->getAttribute('property') === 'og:url'
                && strpos($meta->getAttribute('content'),'view?')
            ) {

                return true;
            }
        }

        return false;
    }
}
