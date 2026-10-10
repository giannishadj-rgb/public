# Claude implementation spec: homepage audit fixes

## Task

Implement the three verified findings from [the homepage technical audit](../audit/2026-10-06-homepage-technical.md), in priority order. Make focused changes to the existing WordPress child theme and verify the resulting behavior. This is an implementation task, not another audit or a homepage redesign.

Read applicable repository instructions first. Re-read the current files before editing; the audit's line numbers are navigation hints and may have shifted.

## Context and scope

The homepage should communicate luxury, elegance and confidence in a Swiss skincare brand. Preserve its current pearl/graphite palette, restrained typography, imagery, clinical hierarchy, section order, product descriptions, prices and purchase destinations.

Primary files:

- `wp-content/themes/cos-child/inc/forms.php` — shared newsletter markup and JavaScript.
- `wp-content/themes/cos-child/inc/navigation.php` — shared newsletter/footer styles, if styling changes are necessary.
- `wp-content/themes/cos-child/assets/css/home-2026-nocturne.css` — homepage presentation layer.
- `wp-content/themes/cos-child/template-parts/home-2026/final.php` — texture caption markup; edit only if CSS cannot solve the target issue.

Keep existing WordPress escaping, CSP nonce handling, newsletter endpoint, request payload and consent requirements. Do not introduce dependencies, change subscription API configuration, redesign the footer, implement theme switching or alter unrelated pages. The newsletter component is shared: validate its behavior on at least one other page using it.

## 1. P1: Accessible newsletter outcome feedback

### Current defect

In `inc/forms.php`, `showError()` appends an ordinary paragraph. The success branch replaces the entire form with another ordinary paragraph. These messages have no status semantics, and replacement can remove the focused submit button without a deliberate focus destination.

### Required implementation

1. Server-render an initially empty, persistent message container in the newsletter form. Give it a unique ID, `role="status"` and `aria-atomic="true"`. Keep it in the accessibility tree; do not use `hidden`, `display:none` or `visibility:hidden` for the announcement region.
2. Update this existing region through `textContent` for pending, success and failure feedback. Preserve the existing success/error wording; pending feedback can use the existing “Subscribing…” wording. Do not insert response text as HTML.
3. Prefer keeping the form, fields and submit control in the DOM for every state. Remove the success-path `form.innerHTML` replacement. Preserve focus unless the user moves it. Avoid duplicate announcements from competing alert/status regions.
4. Use explicit idle/pending/success/error state handling. Prevent concurrent requests and prevent another submission after a successful subscription. If preserving button focus requires `aria-disabled` instead of native `disabled`, enforce that state in the submit handler: ARIA alone does not block interaction. On failure, restore an actionable submit control and allow retry without losing the user's email or consent.
5. Expose the pending state programmatically, for example with `aria-busy` on the form. Clear busy state before publishing the settled outcome so the announcement is not suppressed. A delayed request must leave a clear pending state and must not trigger duplicates.
6. Preserve native email validation and required consent. Treat the existing generic network/API failure as a form-level error; do not mark the email invalid for a generic failure. If field-specific errors are introduced, use `aria-invalid` and `aria-describedby` for the relevant field.
7. Keep status styling consistent with the existing footer. Ensure readable contrast for feedback text and visible keyboard focus. An empty message region must not create a conspicuous blank block.

### Acceptance checks

- The announcement region exists before submission and remains the same DOM node through pending, failure, retry and success.
- With a valid email and checked consent, a mocked successful response produces the success message and a stable focus destination. Tab navigation can continue normally.
- A mocked non-success response and a rejected fetch each produce the error message, clear busy state, preserve field values and allow retry.
- Repeated activation while pending issues only one request. Activation after success does not issue another request.
- Empty/invalid email and unchecked consent retain native validation and issue no subscription request.
- Keyboard Enter submission and pointer submission both work.
- Verify outcome announcements with a screen reader if available. If unavailable, explicitly report that speech output was not tested; DOM assertions or axe do not prove announcements were spoken.
- Test with intercepted/mocked requests only. Do not send real subscription emails during validation.

## 2. P2: Enlarge the texture-caption product link target

### Current defect

The “24h Regeneration Cream” link in `final.php` measures approximately 165 × 17px. The relevant rule is `.h26-texture__caption a` in `home-2026-nocturne.css`.

### Required implementation

- Make this anchor an inline-flex target with centered alignment and a minimum height of 44px. Retain its existing font size, color, underline and destination.
- Keep the visible text positioned naturally within the caption. Adjust the caption's spacing only as necessary to accommodate the larger target.
- Preserve a visible `:focus-visible` outline. Ensure neither the outline nor the target is clipped or overlapped.
- Scope the rule to this caption. Do not enlarge every inline link on the site or use a detached invisible overlay.

### Acceptance checks

- The rendered anchor bounding box is at least 44px high at 320px, 390px and desktop widths; its existing width already exceeds 44px.
- Tapping/clicking within the expanded vertical area activates the existing product link.
- No new document horizontal overflow or overlap with adjacent content.
- The caption remains understated and keyboard-operable.

This is a usability improvement. Do not describe the original 17px measurement alone as a confirmed WCAG AA violation.

## 3. P3: Consolidate audited component colors into semantic tokens

### Current defect

`home-2026-nocturne.css` has a scoped token system, but button rules, desktop ritual overrides and clinical evidence text repeat raw colors directly. Audited locations include approximately lines 79–80, 205–210, 255–262 and 446–447.

### Required implementation

- Reuse existing semantic tokens where their meaning and exact value match. Introduce a small number of section/button tokens where a deliberate variant needs its own value.
- Replace raw color literals in the audited component rules with the corresponding variables. Preserve the current values exactly, including hover, focus and divider alpha values when those rules are consolidated.
- Preserve intentional section and breakpoint differences. In particular, do not replace ritual desktop `#151515` with the general `#1C1C1C` just because both are dark colors.
- Keep the filled button's text/background pair independent of the inverted dark-section text tokens. A token refactor must not invert the button's colors accidentally.
- Clinical note and primary clinical text must reference the same intended section palette as the rest of that evidence block.
- Define tokens in existing scoped palette/section rules and consume them in component rules. Avoid adding another late override block just to supersede old declarations.
- Do not sweep unrelated photo overlays, global navigation, third-party styles or every color literal in the repository into this change.

### Acceptance checks

- Before/after computed foreground, background, border, hover and focus colors match for the audited components.
- Check the ritual below 900px, on a wide desktop, and on a portrait tablet at least 900px wide: its aspect-ratio variants must remain intact.
- Desktop hero, dark caviar section, light clinical section and footer retain their existing contrast and visual hierarchy.
- No new global styles or automatic dark-mode behavior.

## Validation and delivery

Use existing repository tooling where practical. `tests/06-accessibility.spec.ts` and the installed Playwright/axe tooling provide existing patterns. Add focused behavior tests for the newsletter state transitions with network interception; avoid snapshot churn or tests that merely mirror token declarations. An automated accessibility scan is useful supporting evidence, not a substitute for checking status behavior and focus.

Perform PHP syntax checks on modified PHP files. Check the homepage at 320px, 390px, tablet and desktop widths; inspect the caption, newsletter states, and color variants. Recheck mobile navigation Escape/focus return and carousel controls as a small regression pass. Maintain the existing reduced-motion behavior and image loading priorities.

Do not refresh visual baselines merely to make unexpected differences pass. Explain any intentional layout difference caused by the larger link target.

Deliver:

1. The implemented changes, grouped by the three findings.
2. Files changed and a brief rationale for each.
3. Validation actually run, with results; distinguish mocked network checks from real service integration and viewport emulation from physical touch testing.
4. Any remaining limitations or failures, including untested screen-reader speech.

Do not claim a new audit score or full WCAG compliance without a fresh audit. Complete a final visual polish pass after the fixes, then recommend rerunning `$impeccable audit`.
