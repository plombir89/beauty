---
paths:
  - 'app/Filament/Resources/{ContactPolicies,ContactPolicyItems}/**'
---

# Contact Policies Contact Policy Items

## Contact Policy Text Uses Rich Editor
ContactPolicy.intro and ContactPolicyItem.text are edited with Filament RichEditor, store attachments on the public disk under contact/content, and support the shared YouTube custom block. Public contact policy output should use App\Support\SiteRichContentRenderer.
