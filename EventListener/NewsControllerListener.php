<?php

namespace Plugin\NewsPages\EventListener;

use Eccube\Request\Context;
use Eccube\Repository\NewsRepository;
use Eccube\Event\EventArgs;
use Eccube\Common\EccubeConfig;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class NewsControllerListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var Context
     */
    protected $requestContext;

    /**
     * @var NewsRepository
     */
    protected $newsRepository;

    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    public function __construct(RequestStack $requestStack, Context $requestContext, NewsRepository $newsRepository , EccubeConfig $eccubeConfig)
    {
        $this->requestStack = $requestStack;
        $this->requestContext = $requestContext;
        $this->newsRepository = $newsRepository;
        $this->eccubeConfig = $eccubeConfig;
        
    }

    public function saveNewsThumbnail(EventArgs $event)
    {
        $request = $event->getRequest();
        $form = $event->getArgument('form');
        $News = $event->getArgument('News');
        log_info( '[NewsControllerListener]$event ', [$event]);
        log_info( '[NewsControllerListener]$event->getRequest() ', [$event->getRequest()]);
        log_info( '[NewsControllerListener]$event->getArgument(form) ', [$event->getArgument('form')]);
        log_info( '[NewsControllerListener]$event->getArgument(News) ', [$event->getArgument('News')]);
        log_info( '[NewsControllerListener]$form->getData() ', [$form->getData()]);
        // log_info( '[NewsControllerListener]$request->getQuery() ', [$request->getQuery()]);
        // log_info( '[NewsControllerListener]$request->getFiles() ', [$request->getFiles()]);
        $np_thumbnail_data = $form->get('np_thumbnail_data')->getData();
        log_info( '[NewsControllerListener]$np_thumbnail_data ', [$np_thumbnail_data]);

        if( $np_thumbnail_data !== null ){
            $filename = time() . '_' . $np_thumbnail_data->getClientOriginalName();
            $file_save_dir = $this->eccubeConfig['eccube_save_image_dir'].'/';
            // $dir = trim( $this->eccubeConfig( 'kernel.project_dir'). );
            try {
                $np_thumbnail_data->move( $file_save_dir , $filename );
            } catch (FileException $e) {
                log_info('Product review move error');
            }
            $News->setNpThumbnailUrl( $filename );
            $this->newsRepository->save( $News );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'admin.content.news.edit.complete' => ['saveNewsThumbnail', 512],
        ];
    }

}
