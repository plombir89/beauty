---
paths:
  - 'app/Filament/Resources/Specialists/**'
---

# Specialists

## Specialist images use public storage
Specialist profile images are stored on the Laravel public disk with DB values like `specialists/file.jpg`. Filament FileUpload/ImageColumn for specialists should use `disk('public')` and `directory('specialists')`, not `public_uploads` or `img/...` paths.

## Specialist Bio Uses Rich Editor
Specialist.bio is edited with Filament RichEditor, stores attachments on the public disk under specialists/content, and supports the shared YouTube custom block. Render bios through App\Support\SiteRichContentRenderer where they appear publicly.
