---
paths:
  - app/Http/Controllers/ContactController.php
---

# Controllers

## Contact Policy Uses Main Key
The public Contact page loads the seeded ContactPolicy with key `main` and eager-loads only active policy items. Keep this aligned with ElegantBeautySeeder so the policy block does not disappear.
