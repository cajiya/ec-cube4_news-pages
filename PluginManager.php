<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * https://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\NewsPages;

use Eccube\Entity\Layout;
use Eccube\Entity\Page;
use Eccube\Entity\Payment;
use Eccube\Entity\PageLayout;
use Eccube\Plugin\AbstractPluginManager;
use Eccube\Repository\PaymentRepository;
use Eccube\Repository\LayoutRepository;
use Eccube\Repository\PageLayoutRepository;
use Eccube\Repository\PageRepository;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

class PluginManager extends AbstractPluginManager
{

    private $orgTempDef = __DIR__.'/Resource/template/default/';

    private $createPages = array(
      [
        'name' => '[NewsPages]ニュース詳細',
        'url' => 'news_detail',
        'fileName' => 'News/detail'
      ],
      [
        'name' => '[NewsPages]ニュース一覧',
        'url' => 'news_index',
        'fileName' => 'News/index'
      ]
    );

    public function enable(array $meta, ContainerInterface $container)
    {
        $this->copyFiles($container);

        $entityManager = $container->get('doctrine')->getManager();
        $PageLayout = $entityManager->getRepository(Page::class)->findOneBy(['url' => $this->createPages[0]['url'] ]);
        if (is_null($PageLayout)) {
            $this->createPageLayout($container);
        }
    }

    /**
     * @param array $meta
     * @param ContainerInterface $container
     */
    public function disable(array $meta, ContainerInterface $container)
    {
        $this->removePageLayout($container);
        $this->removeFiles($container);
    }

    /**
     * @param ContainerInterface $container
     */
    private function createPageLayout(ContainerInterface $container)
    {

        $pages = $this->createPages;
        foreach( (array)$pages as $p ){

          // ページレイアウトにプラグイン使用時の値を代入
          /** @var \Eccube\Entity\Page $Page */
          $entityManager = $container->get('doctrine')->getManager();

          $Page = $entityManager->getRepository(Page::class)->newPage();
          $Page->setEditType(Page::EDIT_TYPE_DEFAULT);
          $Page->setName( $p['name'] );
          $Page->setUrl( $p['url'] );
          $Page->setFileName( $p['fileName'] );

          // DB登録
          $entityManager = $container->get('doctrine')->getManager();
          $entityManager->persist($Page);
          $entityManager->flush($Page);

          $Layout = $entityManager->getRepository(Layout::class)->find(Layout::DEFAULT_LAYOUT_UNDERLAYER_PAGE);
          $PageLayout = new PageLayout();
          $PageLayout->setPage($Page)
              ->setPageId($Page->getId())
              ->setLayout($Layout)
              ->setLayoutId($Layout->getId())
              ->setSortNo(0);

          $entityManager->persist($PageLayout);
          $entityManager->flush($PageLayout);

        }


    }

    /**
     * ページレイアウトを削除.
     *
     * @param ContainerInterface $container
     */
    private function removePageLayout(ContainerInterface $container)
    {

        $pages = $this->createPages;
        foreach( $pages as $p ){
          $entityManager = $container->get('doctrine')->getManager();
          $Page = $entityManager->getRepository(Page::class)->findOneBy(['url' => $p['url'] ]);
          if ($Page) {
              $Layout = $entityManager->getRepository(Layout::class)->find(Layout::DEFAULT_LAYOUT_UNDERLAYER_PAGE);
              $PageLayout = $entityManager->getRepository(PageLayout::class)->findOneBy(['Page' => $Page, 'Layout' => $Layout]);
              // Blockの削除
              $entityManager = $container->get('doctrine')->getManager();
              $entityManager->remove($PageLayout);
              $entityManager->remove($Page);
              $entityManager->flush();
          }

        }
    }

    /**
     * Copy block template.
     *
     * @param ContainerInterface $container
     */
    private function copyFiles(ContainerInterface $container)
    {
        $templateDir = $container->getParameter('eccube_theme_front_dir');
        $file = new Filesystem();
        $file->copy($this->orgTempDef . 'News/detail.twig' , $templateDir.'/News/detail.twig' );
        $file->copy($this->orgTempDef . 'News/index.twig' , $templateDir.'/News/index.twig' );
    }

    /**
     * Remove block template.
     *
     * @param ContainerInterface $container
     */
    private function removeFiles(ContainerInterface $container)
    {
        $templateDir = $container->getParameter('eccube_theme_front_dir');
        $file = new Filesystem();
        $file->remove( $templateDir.'/News/detail.twig' );
        $file->remove( $templateDir.'/News/index.twig' );
    }
}
