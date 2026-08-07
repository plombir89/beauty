---
paths:
  - 'app/Filament/Resources/WhyChooseUsItems/**'
---

# Why Choose Us Items

## Why Choose images use public storage
Why Choose Us item images are stored on the Laravel public disk with DB values like `choose/file.jpg`. Filament FileUpload/ImageColumn for this resource should use `disk('public')` and `directory('choose')`; keep the upload compact instead of full-width.

## Generate slug from title
In Why Choose Us translation tabs, place Title before Slug and set `slugTarget => 'slug'` on Title so each locale's slug is generated automatically from that locale's title, matching Services and Blog resources.

## Why Choose Body Uses Rich Editor
WhyChooseUsItem.body is edited with Filament RichEditor, stores attachments on the public disk under choose/content, and supports the shared YouTube custom block. Keep Title before Slug with locale slug generation unchanged.

## Convert Legacy Why Choose Body On Edit
Some existing WhyChooseUsItem.body translations may still be legacy arrays of plain-text paragraphs from the old lines field. Edit pages must normalize those arrays to escaped HTML before form fill so Filament RichEditor does not treat them as TipTap JSON.
