<?php

namespace Plugin\NewsPageSelfReliance43;

use Eccube\Event\TemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class Event implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents() : array
    {
        return [
            '@admin/Content/news.twig' => 'adminContentNewsTwig',
            '@admin/Content/news_edit.twig' => 'adminContentNewsEditTwig'
        ];
    }

    public function adminContentNewsTwig(TemplateEvent $event)
    {
        $event->addSnippet('@NewsPageSelfReliance43/admin/Content/news_url_view.twig');
    }

    public function adminContentNewsEditTwig(TemplateEvent $event)
    {
        $event->addSnippet('@NewsPageSelfReliance43/admin/Content/news_edit_snipet.twig');
    }
}
