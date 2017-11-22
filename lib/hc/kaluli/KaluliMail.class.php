<?php

use Nette\Mail\Message;

class KaluliMail extends Message{

    public $config;

    protected $from;

    protected $to;

    protected $title;

    protected $body;

    protected $cc;

    function __construct($to){

        $this->setFrom("remind@kaluli.com");

        if ( is_array($to) ) {

            foreach ($to as $email) {

                $this->addTo($email);

            }

        } else {

            $this->addTo($to);

        }

    }

    public function from($from=null){

        if ( !$from ) {

            throw new InvalidArgumentException("send email address required！");

        }

        $this->setFrom($from);

        return $this;

    }

    public static function to($to=null){

        if ( !$to ) {

            throw new InvalidArgumentException("receive email address required！");

        }

        return new self($to);

    }

    public function title($title=null){

        if ( !$title ) {

            throw new InvalidArgumentException("email title required！");

        }

        $this->setSubject($title);

        return $this;

    }

    public function content($content=null){

        if ( !$content ) {

            throw new InvalidArgumentException("email content required！");

        }

        $this->setHTMLBody($content);

        return $this;

    }

    public function cc($cc = null) {
        if(!$cc) {
            return $this;
        }
        if ( is_array($cc) ) {

            foreach ($cc as $email) {

                $this->addCc($email);

            }

        } else {

            $this->addCc($cc);

        }
        return $this;
    }
}
