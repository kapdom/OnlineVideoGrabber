<?php

declare(strict_types=1);

namespace KapDom\OnlineVideoGrabber\Providers;

use KapDom\OnlineVideoGrabber\Exceptions\VideoGrabberException;
use KapDom\OnlineVideoGrabber\Models\Video;

class VimeoProvider extends VideoProvider
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
        if (!$this->checkIfVideoExists($url)) {
            throw new VideoGrabberException('Video in Vimeo service not exists. Url: '.$url);
        }

        $linkArr  = explode('/',trim($url,'/'));
        $arrCount = count($linkArr)-1;

        if(strpos($linkArr[$arrCount],'#')){
            $idArr = explode('#',$linkArr[$arrCount]);
            $movieId = $idArr[0];
        } else {
            $movieId = $linkArr[$arrCount];
        }

        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$movieId.php"));
        $thumbnail = '';
        if (isset($hash[0]) && $hash[0]["thumbnail_large"]) {
            $thumbnail = $hash[0]["thumbnail_large"];
        }
        $embed = 'https://player.vimeo.com/video/'.$movieId;

        return new Video(self::VIMEO, $url, $embed, $thumbnail);
    }
}
