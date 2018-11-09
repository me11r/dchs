<?php

namespace App\Entities\Fcm;


/**
 * Class FcmMessageData
 * @package App\Entities\Fcm
 */
class FcmMessageData
{
    /**
     * @var int
     */
    private $messageId;

    /**
     * @var int
     */
    private $infoId;

    /**
     * @var string
     */
    private $messageType = '';

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $body = '';

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     * @return FcmMessageData
     */
    public function setMessageId(int $messageId): FcmMessageData
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @return int
     */
    public function getInfoId(): int
    {
        return $this->infoId;
    }

    /**
     * @param int $infoId
     * @return FcmMessageData
     */
    public function setInfoId(int $infoId): FcmMessageData
    {
        $this->infoId = $infoId;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        return $this->messageType;
    }

    /**
     * @param string $messageType
     * @return FcmMessageData
     */
    public function setMessageType(string $messageType): FcmMessageData
    {
        $this->messageType = $messageType;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return FcmMessageData
     */
    public function setTitle(string $title): FcmMessageData
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return FcmMessageData
     */
    public function setBody(string $body): FcmMessageData
    {
        $this->body = $body;
        return $this;
    }

}
