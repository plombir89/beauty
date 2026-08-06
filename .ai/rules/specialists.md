---
paths:
  - 'app/Filament/Resources/Specialists/**'
---

# Specialists

## Specialist images use public storage
Specialist profile images are stored on the Laravel public disk with DB values like `specialists/file.jpg`. Filament FileUpload/ImageColumn for specialists should use `disk('public')` and `directory('specialists')`, not `public_uploads` or `img/...` paths.
