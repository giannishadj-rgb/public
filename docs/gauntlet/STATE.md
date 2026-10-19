# Checkpoint ? 18 October 2026

Current phase: completed staging-to-local homepage match. Latest user directive explicitly replaces the previous Gauntlet redesign: make http://caviarofswitzerland.local/ the same as https://staging.caviarofswitzerland.com/. Do not resume the previous counter/campaign design or speculative luxury improvements.

Implemented: staging silk-and-cream hero, collection, caviar, science/evidence, provenance, ritual and closing, plus matching homepage header/footer. Public staging CSS/JS exactly matched repository HEAD 367aec9, enabling targeted restoration. All 179 publicly referenced image/font assets verified byte-for-byte. Restored the changed hxh campaign exports from HEAD. Local product prices and links remain WordPress/WooCommerce driven.

Scope: page-home-2026.php; seven template-parts/home-2026 sections; homepage section CSS; inc/home-2026.php; inc/hx-system.php. Staging versions of shared system/chrome CSS isolated under assets/css/home-staging/ and motion under assets/home-staging/. inc/home-staging-navigation.php swaps chrome hooks only on the front homepage. Original shared navigation and other pages retain their existing versions. No database import or deployment.

Rollback backup outside public: ../design-sources/staging-home-sync-20261018/before/. Manifest and original public asset comparisons adjacent. Pre-existing dirty tree preserved; never blanket reset.

Verified: full-page Chromium reduced-motion captures at 390?1000 and 1440?1000 are pixel-identical to staging (RGB diff has no changed pixels). Text, section dimensions, page heights match. No JS exceptions, HTTP asset errors or horizontal overflow. Two inactive mobile ritual pictures stay lazy on both sites; no missing visible assets. Menu opens/Escape closes; all nine unique main destinations return 200 and stay local. Shop still uses its original shared chrome. Eleven PHP files lint; motion JS syntax clean. No compilation pipeline.

Evidence: QA.md; staging-match-qa.json; staging-match-functional.json; screenshots/sync-{staging,local}-{390,1440}.png. Previous 9.x design ratings and Lighthouse scores apply to the superseded design, not this sync.

Outstanding: none for requested matching task. SSH host-key mismatch prevented server access; no bypass attempted or needed. Used public staging reference and verified HEAD assets. No remote changes.

Next: act only on new requested changes or demonstrated mismatch. Preserve staging as the current design source of truth.
