<?php

namespace Plugin\NewsPages\EventListener;

use Eccube\Common\EccubeConfig;
use Eccube\Request\Context;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class NewsPagesListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var Context
     */
    protected $requestContext;

    public function __construct(RequestStack $requestStack, EccubeConfig $eccubeConfig, Context $requestContext)
    {
        $this->requestStack = $requestStack;
        $this->eccubeConfig = $eccubeConfig;
        $this->requestContext = $requestContext;
    }

    public function onKernelRequest(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        if ($this->requestContext->isAdmin()) {

          log_info( '[AdminEditorWysiwyg]$event' , [$event] );
          $response = $event->getResponse();
          log_info( '[AdminEditorWysiwyg]$response' , [$response] );
          $content = $response->getContent();
          $code = <<< EOD
          <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.16/tinymce.min.js"></script>
          <!-- Initialize Quill editor -->
          <script>
            let selector = Array(
              '#page_admin_product_product_new .c-primaryCol textarea.form-control',
              '#page_admin_product_product_edit .c-primaryCol textarea.form-control',
              '#page_admin_content_news_edit .c-primaryCol textarea.form-control',
              '#page_admin_content_news .c-primaryCol textarea.form-control',
            );
            selector = selector.join();

            tinymce.init({
                selector: selector,
                language: "ja",
                plugins: "textcolor table lists link link image code",
                menubar: "false",
                toolbar: ['undo redo | bold italic | styleselect | forecolor backcolor ',
                          'numlist bullist | table | link | image'],
                height: 500,
                branding: false
            });
          </script></body>
EOD;
        // $content = str_replace( '</body>', $code, $content);
        // log_info( '[AdminEditorWysiwyg]$content' , [$content] );
        // $response->setContent($content);


        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.response' => ['onKernelRequest', 512],
        ];
    }

}
