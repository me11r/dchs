<?php

namespace App\Entities\Fcm;

class FcmMessage
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var FcmMessageData | null
     */
    private $additionalData = null;

    /**
     * @var string
     */
    private $token = '';

    /**
     * @var string
     */
    private $clickAction = '';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return FcmMessage
     */
    public function setTitle(string $title): FcmMessage
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
     * @return FcmMessage
     */
    public function setBody(string $body): FcmMessage
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return FcmMessageData|null
     */
    public function getAdditionalData(): ?FcmMessageData
    {
        return $this->additionalData;
    }

    /**
     * @param FcmMessageData|null $additionalData
     * @return FcmMessage
     */
    public function setAdditionalData(?FcmMessageData $additionalData): FcmMessage
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return FcmMessage
     */
    public function setToken(string $token): FcmMessage
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getClickAction(): string
    {
        return $this->clickAction;
    }

    /**
     * @param string $clickAction
     * @return FcmMessage
     */
    public function setClickAction(string $clickAction): FcmMessage
    {
        $this->clickAction = $clickAction;
        return $this;
    }

}
