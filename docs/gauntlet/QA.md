# Latest verification ? staging sync, 18 October 2026

- Chromium full-page comparisons: 390?1000 and 1440?1000, reduced motion, scrolled/decoded before capture. Local/staging RGB pixel differences: zero at both sizes.
- Mobile page size: 390?8260; desktop: 1440?8501. Main text and every section top/height match.
- Screenshots: screenshots/sync-staging-390.png, sync-local-390.png, sync-staging-1440.png, sync-local-1440.png. Desktop image visually inspected.
- No console exceptions, HTTP asset failures or horizontal overflow. Two hidden mobile ritual images are still lazy on both sites; desktop loads all pictures.
- 179 referenced image/font files match staging bytes. Public CSS/JS matches HEAD 367aec9. Isolated CSS retains original relative font resolution.
- Mobile menu open and Escape-close pass. All nine main route destinations return 200 and remain on local origin. Shop retains original chrome stylesheet.
- Eleven changed PHP files lint clean; staging motion JS syntax passes. Theme has no compilation pipeline.
- Reports: staging-match-qa.json; staging-match-functional.json; staging-asset-sync.json. Reproduction: verify-staging-match.cjs and check-staging-functions.cjs.
- No fresh Lighthouse score or independent subjective design rating claimed: task is staging parity. Earlier flagship scores concern the superseded homepage.
- Remote SSH key mismatch was not bypassed. Public reference used; staging unchanged.

Acceptance failures: none identified for requested homepage parity at tested viewports.
