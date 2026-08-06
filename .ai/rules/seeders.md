---
paths:
  - '{app/Support/Seo.php,resources/views/pages/**,resources/views/components/site/**,database/seeders/**}'
---

# Seeders

## Public site images render from storage
Public-facing content, SEO images, and site logos should render from `/storage/...` using database values or storage-relative asset paths. Seeded media should use paths like `seo/9.jpg`, `blog/products.jpg`, `about/title-img.png`, and `site/logo.png`, not `/img/...`.
