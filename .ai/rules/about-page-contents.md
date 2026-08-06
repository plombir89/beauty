---
paths:
  - 'app/Filament/Resources/AboutPageContents/**'
---

# About Page Contents

## About page content image uses public storage
About Page Content image is stored on the Laravel public disk with DB values like `about/file.jpg`. Filament FileUpload/ImageColumn for this resource should use `disk('public')` and `directory('about')`, not `public_uploads` or `img/...` paths.
