<?php

namespace Plugin\NewsPageSelfReliance\EventListener;

use Eccube\Request\Context;
use Eccube\Repository\NewsRepository;
use Eccube\Event\EventArgs;
use Eccube\Common\EccubeConfig;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class NpsrControllerListener implements EventSubscriberInterface
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
        $np_thumbnail_data = $form->get('np_thumbnail_data')->getData();

        if( $np_thumbnail_data !== null ){
            $filename = time() . '_' . $np_thumbnail_data->getClientOriginalName();
            $file_save_dir = $this->eccubeConfig['eccube_save_image_dir'].'/';
            try {
                $np_thumbnail_data->move( $file_save_dir , $filename );
            } catch (FileException $e) {
                log_info('[NPSR]画像保存時にエラー発生', [$e]);
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
