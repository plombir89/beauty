---
paths:
  - 'app/Filament/Resources/HomeHeroBlocks/**'
---

# Home Hero Blocks

## Home hero is singleton and uses storage
The site has exactly one Home Hero block. The Filament Home Hero resource must allow editing only: no create page/action and no delete or bulk-delete. Its image is stored on the public disk with DB values like `home/file.jpg`, using `disk('public')` and `directory('home')`.
