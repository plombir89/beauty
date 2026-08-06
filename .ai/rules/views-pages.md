---
paths:
  - resources/views/pages/about.blade.php
---

# Views Pages

## Render About Text As Rich Content
On the public About page, AboutPageContent.text must be rendered through App\Support\SiteRichContentRenderer inside .about-rich-content so stored rich editor HTML and YouTube custom blocks render consistently. AboutPageContent.text2 remains escaped plain text.
