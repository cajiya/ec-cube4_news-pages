# EC-CUBE4.x用 新着情報一覧／詳細ページ生成プラグイン

EC-CUBEデフォルトの「新着情報」の、一覧／詳細URLを生成する。

## 生成されるURL

`{$your_site_address}/news` // 新着情報一覧ページ

`{$your_site_address}/news/{$news_id}` // 新着情報詳細ページ

# インストール方法

```
cd app/Plugin;
git clone https://github.com/cajiya/ec-cube4_news-pages.git;
mv ec-cube4_news-pages NewsPages;
cd ../../;
php bin/console eccube:plugin:install --code="NewsPages"
```
