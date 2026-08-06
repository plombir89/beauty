---
paths:
  - 'app/Filament/Resources/ExpertisePillars/**'
---

# Expertise Pillars

## Expertise pillar images use public storage
Expertise Pillars images are stored on the Laravel public disk with DB values like `pillars/file.jpg`. Filament FileUpload/ImageColumn for this resource should use `disk('public')` and `directory('pillars')`, not `img/...` paths.
