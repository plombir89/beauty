---
paths:
  - 'app/Filament/Resources/WhyChooseUsItems/**'
---

# Why Choose Us Items

## Why Choose images use public storage
Why Choose Us item images are stored on the Laravel public disk with DB values like `choose/file.jpg`. Filament FileUpload/ImageColumn for this resource should use `disk('public')` and `directory('choose')`; keep the upload compact instead of full-width.

## Generate slug from title
In Why Choose Us translation tabs, place Title before Slug and set `slugTarget => 'slug'` on Title so each locale's slug is generated automatically from that locale's title, matching Services and Blog resources.
