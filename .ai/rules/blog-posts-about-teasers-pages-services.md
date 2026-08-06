---
paths:
  - 'app/Filament/Resources/{BlogPosts,AboutTeasers,Pages,Services}/**'
---

# Blog Posts About Teasers Pages Services

## Admin media directories are public storage relative paths
Use Filament FileUpload/RichEditor on `disk('public')` with storage-relative directories: `blog`, `blog/content`, `about`, `seo`, and `services/content`. The database stores these relative paths, and previews should not depend on `/img` or `public_uploads`.
