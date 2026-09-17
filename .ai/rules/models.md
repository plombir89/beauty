---
paths:
  - '{app/Http/Controllers/ServiceController.php,resources/views/pages/services/**,app/Filament/Resources/Services/**,app/Models/Service.php}'
---

# Models

## Service detail image fallback
Services have two public-disk images: `image` is used for service cards/listing pages, while optional `detail_image` is used on the opened service page and SEO image. If `detail_image` is empty, render/fallback to `image`.
