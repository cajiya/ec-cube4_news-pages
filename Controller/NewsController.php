<?php

namespace Plugin\NewsPages\Controller;

use Eccube\Controller\AbstractController;

use Eccube\Entity\News;
use Eccube\Repository\NewsRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
// use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;



class NewsController extends AbstractController
{

  /**
   * @var NewsRepository
   */
  protected $newsRepository;


  /**
   * NewsController constructor.
   */
  public function __construct(
    NewsRepository $newsRepository
  )
  {
    $this->newsRepository = $newsRepository;
  }


  /**
   * ニュース一覧画面.
   *
   * @Route( "/news" , name="news_index" )
   * @Template("News/index.twig")
   */

  public function index( Request $request, PaginatorInterface $paginator )
  {
    // handleRequestは空のqueryの場合は無視するため
    // if ($request->getMethod() === 'GET') {
    //     $request->query->set('pageno', $request->query->get('pageno', ''));
    // }

    $qb = $this->newsRepository->getQueryBuilderAll();
    log_info('[NewsPages]$qb',[$qb]);

    $query = $qb->getQuery()->useResultCache(true, $this->eccubeConfig['eccube_result_cache_lifetime_short']);

    /** @var SlidingPagination $pagination */
    $pagination = $paginator->paginate(
        $query,
        $request->query->get('pageno', 1)
    );
    foreach( $pagination as $news ){
      log_info('[NewsPages]$news',[$news]);
    }
    
    return [
      'pagination' => $pagination,
    ];
  }

  /**
   * ニュース詳細画面.
   *
   * @Route("/news/{id}" , name="news_detail" )
   * @Template("News/detail.twig")
   * @ParamConverter("News", options={"id" = "id"})
   */

  public function detail( Request $request, News $News )
  {
    if ( !$this->checkVisibility($News) ) {
      throw new NotFoundHttpException();
    }
    log_info('[NewsPages]$News',[$News]);
    $NewsUrl = $News->getUrl();
    log_info('[NewsPages]$NewsUrl',[$NewsUrl]);
    if ( $NewsUrl !== null ){
      log_info('[NewsPages]REDIRECT START');
      return new RedirectResponse( $NewsUrl );
    }
    return [
      'news' => $News,
    ];
  }

  /**
   * 閲覧可能なニュースかどうかを判定
   *
   * @param News $News
   *
   * @return boolean 閲覧可能な場合はtrue
   */
  protected function checkVisibility(News $News)
  {
      $is_admin = $this->session->has('_security_admin');

      // 管理ユーザの場合はステータスやオプションにかかわらず閲覧可能.
      if (!$is_admin) {
          // 公開ステータスでない商品は表示しない.
          // if ( $News->isVisible() !== 0 ) {
              // return false;
          // }
      }

      return true;
  }
}
