<?php

class hcRabbitMQUtils {

    public static function getPublisherOptions($consumer) {
        if ($consumer == 'shihuo_item' || $consumer == 'shihuo_youhui' || $consumer == 'shihuo_news' || $consumer == 'shihuo_user' || $consumer == 'shihuo_find'){
            $options = sfConfig::get('app_mabbitmq_options_shihuo');
        } else {
            $options = sfConfig::get('app_mabbitmq_options');
        }
        $rabbitmq_options = $options['params'];
        $exchanges = $options['exchanges'];
        return array_merge($rabbitmq_options, $exchanges[$consumer]);
    }

}