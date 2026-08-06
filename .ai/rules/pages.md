---
paths:
  - '{app/Http/Controllers/AboutController.php,resources/views/pages/about.blade.php}'
---

# Pages

## About page includes active team
The About page should render the Team section from active specialists ordered by `sort_order`, eager-loading active ordered services for the service chips. Specialist images on this page should be rendered from `asset('storage/'.ltrim($specialist->image, '/'))`.
