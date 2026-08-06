---
paths:
  - app/Filament/Support/Fields.php
---

# Support

## Shared media fields use public storage
Shared image uploads, image columns, and rich editor attachments must use Laravel's `public` disk with relative storage paths such as `blog/file.jpg` or `services/content/file.jpg`. Do not use `public_uploads`, `public_path()`, `/img/...`, or `img/uploads/...` for admin-managed media.
