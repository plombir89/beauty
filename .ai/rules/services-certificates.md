---
paths:
  - 'app/Filament/Resources/{Services,Certificates}/**'
---

# Services Certificates

## Service and certificate images use public storage
Main images for services and certificates are stored on the Laravel public disk with DB values like `services/file.jpg` and `certificates/file.jpg`. Filament FileUpload/ImageColumn for these resources should use `disk('public')` and the matching directory, not `public_uploads` or `/img/...` paths.
