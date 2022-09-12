<?php

namespace App\Channels;

class SmsMessage {
    /**
     * @var string
     */
    private $_recipient;

    /**
     * @var string
     */
    private $_content;

    /**
     * @return string
     */
    public function getRecipient(): string {
        return $this->_recipient;
    }

    /**
     * @param string $recipient
     * @return $this
     */
    public function setRecipient(string $recipient): SmsMessage {
        $this->_recipient = $recipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string {
        return $this->_content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): SmsMessage {
        $this->_content = $content;
        return $this;
    }
}