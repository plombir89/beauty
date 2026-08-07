---
paths:
  - 'app/Filament/Resources/AboutPageContents/**'
---

# About Page Contents

## About page content image uses public storage
About Page Content image is stored on the Laravel public disk with DB values like `about/file.jpg`. Filament FileUpload/ImageColumn for this resource should use `disk('public')` and `directory('about')`, not `public_uploads` or `img/...` paths.

## About Text Uses Rich Editor
AboutPageContent.text is edited with Filament RichEditor, stores attachments on the public disk under about/content, and supports the shared YouTube custom block.

## About Secondary Text Uses Rich Editor
AboutPageContent.text and AboutPageContent.text2 are edited with Filament RichEditor, store attachments on the public disk under about/content, and support the shared YouTube custom block. Render both through App\Support\SiteRichContentRenderer on the public About page.
