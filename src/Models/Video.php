<?php

declare(strict_types=1);

namespace KapDom\OnlineVideoGrabber\Models;

class Video
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $videoUrl;

    /**
     * @var string
     */
    private $embedUrl;

    /**
     * @var string
     */
    private $thumbUr;

    /**
     * @param string $type
     * @param string $videoUrl
     * @param string $embedUrl
     * @param string $thumbUr
     */
    public function __construct(string $type, string $videoUrl, string $embedUrl, string $thumbUr)
    {
        $this->type = $type;
        $this->videoUrl = $videoUrl;
        $this->embedUrl = $embedUrl;
        $this->thumbUr = $thumbUr;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getVideoUrl(): string
    {
        return $this->videoUrl;
    }

    /**
     * @return string
     */
    public function getEmbedUrl(): string
    {
        return $this->embedUrl;
    }

    /**
     * @return string
     */
    public function getThumbUr(): string
    {
        return $this->thumbUr;
    }
}
