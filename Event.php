<?php

namespace Plugin\NewsPages;

use Eccube\Event\TemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Event implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            '@admin/Content/news.twig' => 'adminContentNewsTwig'
        ];
    }

    public function adminContentNewsTwig(TemplateEvent $event)
    {
        $event->addSnippet('@NewsPages/admin/Content/news_url_view.twig');
    }
}
