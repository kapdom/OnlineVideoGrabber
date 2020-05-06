<?php

declare(strict_types=1);

namespace KapDom\OnlineVideoGrabber;

use KapDom\OnlineVideoGrabber\Exceptions\VideoGrabberException;
use KapDom\OnlineVideoGrabber\Models\Video;
use KapDom\OnlineVideoGrabber\Providers\DailymotionProvider;
use KapDom\OnlineVideoGrabber\Providers\LiveleakProvider;
use KapDom\OnlineVideoGrabber\Providers\MetacafeProvider;
use KapDom\OnlineVideoGrabber\Providers\VideoProvider;
use KapDom\OnlineVideoGrabber\Providers\VimeoProvider;
use KapDom\OnlineVideoGrabber\Providers\YoutubeProvider;

class VideoGrabber
{
    /**
     * @var VideoProvider
     */
    private $provider;

    /**
     * @param string $link
     *
     * @return Video|null
     *
     * @throws VideoGrabberException
     */
    public function getVideo(string $link): ?Video
    {
        $domain =  parse_url($link,PHP_URL_HOST);
        $domain = mb_strtolower($domain);
        $domain = str_replace('www.','',$domain);

        switch ($domain) {
            case 'youtube.com':
            case 'youtu.be':
                $this->provider = new YoutubeProvider();
                break;

            case 'vimeo.com':
                $this->provider = new VimeoProvider();
                break;

            case 'dai.ly':
            case 'dailymotion.com':
                $this->provider = new DailymotionProvider();
                break;

            case 'liveleak.com':
                $this->provider = new LiveleakProvider();
                break;

            case 'metacafe.com':
                $this->provider = new MetacafeProvider();
                break;
        }

        if ($this->provider === null) {
           return null;
        }

        return $this->provider->getVideoData($link);
    }
}
