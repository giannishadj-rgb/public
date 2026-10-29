/* hx-motion.js — Caviar of Switzerland homepage motion (14 Oct 2026 rebuild).
 * Owner: system agent. Dependency-free, deferred. CSS half lives in assets/css/home-staging/system.css.
 *
 * 20 Oct 2026 (animate pass): below-fold reveals finish in 600–900ms and start at most 150ms
 * after intersecting; photographs are never hidden by a reveal (see system.css).
 *
 * REVEALS — add data-hx-reveal to any element:
 *   data-hx-reveal            text: fade + rise 14px (default; same as "rise")       700ms, --hx-ease
 *   data-hx-reveal="fade"     text: opacity only                                       600ms
 *   data-hx-reveal="light"    headings: a band of light writes the words in (starts mostly lit) 800ms
 *   data-hx-reveal="mask" / "mask-x" / "settle"
 *                             images: the <img> inside settles 1.04→1 within its frame;  900ms
 *                             the frame and photograph are never clipped, faded or darkened
 *   data-hx-reveal="blind"    a group of photographs rises 14px into place, nothing hidden   800ms
 *   Delay:   data-hx-delay="240" (ms)  or  style="--hx-i:2" (× --hx-stagger 50ms); the CSS caps
 *            the total at --hx-delay-max (150ms), so nothing waits long after it intersects.
 *   Stagger: put data-hx-stagger on a parent → its [data-hx-reveal] descendants get --hx-i = 0,1,2…
 *            in document order (an explicit --hx-i/data-hx-delay on a child still wins).
 *   Trigger: as soon as the element's top passes 94% of the viewport height (rootMargin -6% bottom).
 *   Instant (no transition) when: the element is on screen or above it at start; it enters from
 *   above (scrolling back up); it arrives by a jump (deep link, End/Home, scrollbar drag,
 *   find-in-page, a long anchor jump: any scroll of more than one viewport between two events);
 *   or keyboard focus moves into it.
 *   Guarantees: content is never hidden unless this script ran AND motion is allowed. Elements
 *   already in (or above) the viewport at start are shown instantly — no flash, no animation;
 *   for above-the-fold motion use the CSS-only .hx-load-* classes. Under prefers-reduced-motion
 *   nothing moves and everything is visible. Only opacity / transform / mask animate.
 *
 * ANCHORS — same-page links (a[href="#id"]): a short distance (≤ 2 viewports) glides with native
 *   smooth scrolling; a longer one jumps, so no one waits through a page-long glide. Either way
 *   the target honours its scroll-margin, the URL hash updates, and focus moves to the target
 *   (tabindex="-1" added when needed) for keyboard and screen-reader users. Reduced motion: jumps.
 *   There is no CSS scroll-behavior:smooth, which would also animate deep links (/#ritual) on load.
 *
 * DRIFT (parallax) — data-hx-drift or data-hx-drift="0.06" (factor, default 0.06, max 0.2):
 *   translates the element vertically by (element centre − viewport centre) × −factor, clamped to
 *   ±120px, via the CSS `translate` property (composes with reveal transforms). Active only at
 *   ≥900px wide, without reduced motion, and only while the element is near the viewport.
 *   Give a drifting image some bleed inside an .hx-frame (e.g. img{height:112%;margin-top:-6%}).
 *
 * API: window.hxMotion.refresh(root?) — observe [data-hx-reveal]/[data-hx-drift] added later.
 *      window.hxMotion.revealAll()     — show everything immediately.
 */
(function () {
	'use strict';
	var doc = document.documentElement;
	var mqReduce = window.matchMedia ? window.matchMedia('(prefers-reduced-motion: reduce)') : null;
	var mqWide = window.matchMedia ? window.matchMedia('(min-width: 900px)') : null;
	var reduced = !!(mqReduce && mqReduce.matches);
	var hasIO = 'IntersectionObserver' in window;
	var seen = typeof WeakSet === 'function' ? new WeakSet() : null;
	var revealIO = null;
	var jumpUntil = 0; // reveals intersecting before this time (ms) show instantly
	function now() { return window.performance && performance.now ? performance.now() : Date.now(); }
	function markJump(ms) { jumpUntil = Math.max(jumpUntil, now() + (ms || 200)); }

	function all(root, sel) {
		return Array.prototype.slice.call((root || document).querySelectorAll(sel));
	}

	// instant: .hx-instant marks "arrived without an entrance" for good (CSS skips entrance
	// animations on it); .hx-snap only suppresses transitions for two frames, so hover easing
	// on the photographs inside keeps working afterwards.
	function show(el, instant) {
		if (instant) {
			el.classList.add('hx-instant', 'hx-snap');
			var raf = window.requestAnimationFrame || function (f) { return setTimeout(f, 16); };
			raf(function () { raf(function () { el.classList.remove('hx-snap'); }); });
		}
		el.classList.add('is-in');
	}

	function revealAll() {
		all(document, '[data-hx-reveal]').forEach(function (el) { show(el, true); });
		if (revealIO) revealIO.disconnect();
	}

	function applyStagger(root) {
		all(root, '[data-hx-stagger]').forEach(function (group) {
			all(group, '[data-hx-reveal]').forEach(function (el, i) {
				if (!el.style.getPropertyValue('--hx-i')) el.style.setProperty('--hx-i', String(i));
			});
		});
		if (root && root.nodeType === 1 && root.closest) {
			var g = root.closest('[data-hx-stagger]');
			if (g && g !== root) applyStagger(g);
		}
	}

	function initReveals(root) {
		var els = all(root, '[data-hx-reveal]');
		if (root && root.nodeType === 1 && root.hasAttribute('data-hx-reveal')) els.unshift(root);
		if (!els.length) return;
		applyStagger(root || document);
		var vh = window.innerHeight || doc.clientHeight;
		els.forEach(function (el) {
			if (seen) { if (seen.has(el)) return; seen.add(el); }
			var d = el.getAttribute('data-hx-delay');
			if (d && !isNaN(parseFloat(d))) el.style.setProperty('--hx-delay', parseFloat(d) + 'ms');
			if (reduced || !revealIO) { show(el, true); return; }
			var r = el.getBoundingClientRect();
			// Already on screen (or scrolled past, e.g. reload mid-page): show now, no flash.
			if (r.top < vh * 0.98) {
				show(el, true);
				return;
			}
			revealIO.observe(el);
		});
	}

	/* ── jumps, focus and anchors ─────────────────────────────────── */
	var lastY = window.pageYOffset || 0;
	function onScroll() {
		var y = window.pageYOffset || 0;
		if (Math.abs(y - lastY) > (window.innerHeight || doc.clientHeight)) markJump(200);
		lastY = y;
	}

	function revealWithin(el) {
		// keyboard focus landed inside a hidden reveal: show it (and any hidden ancestors) at once
		for (var n = el; n && n.closest; n = n.parentElement) {
			n = n.closest('[data-hx-reveal]:not(.is-in)');
			if (!n) break;
			show(n, true);
			if (revealIO) revealIO.unobserve(n);
		}
	}

	function targetOf(a) {
		if (!a || !a.hash || a.hash.length < 2) return null;
		if (a.origin !== location.origin || a.pathname.replace(/\/$/, '') !== location.pathname.replace(/\/$/, '')) return null;
		try { return document.getElementById(decodeURIComponent(a.hash.slice(1))); } catch (err) { return null; }
	}

	function onAnchorClick(e) {
		if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
		var a = e.target.closest ? e.target.closest('a[href*="#"]') : null;
		var t = targetOf(a);
		if (!t) return;
		e.preventDefault();
		var vh = window.innerHeight || doc.clientHeight;
		var margin = parseFloat(getComputedStyle(t).scrollMarginTop) || 0;
		var dist = Math.abs(t.getBoundingClientRect().top - margin);
		var glide = !reduced && dist <= vh * 2;
		if (!glide) markJump(400);
		t.scrollIntoView({ behavior: glide ? 'smooth' : 'auto', block: 'start' });
		if (history.pushState) history.pushState(null, '', a.hash); else location.hash = a.hash;
		if (!t.hasAttribute('tabindex') && !/^(A|BUTTON|INPUT|SELECT|TEXTAREA)$/.test(t.tagName)) t.setAttribute('tabindex', '-1');
		t.focus({ preventScroll: true });
		revealWithin(t);
	}

	/* ── drift ─────────────────────────────────────────────────────── */
	var drifters = [];
	var driftActive = typeof Set === 'function' ? new Set() : null;
	var driftIO = null;
	var ticking = false;
	var driftOn = false;

	function driftFrame() {
		ticking = false;
		if (!driftOn) return;
		var vh = window.innerHeight;
		var mid = vh / 2;
		driftActive.forEach(function (el) {
			var r = el.getBoundingClientRect();
			// measure without our own offset so the motion is stable
			var cur = parseFloat(el.style.getPropertyValue('--hx-dy')) || 0;
			var centre = r.top - cur + r.height / 2;
			var f = Math.min(0.2, Math.max(0, parseFloat(el.getAttribute('data-hx-drift')) || 0.06));
			var y = Math.max(-120, Math.min(120, (centre - mid) * -f));
			el.style.setProperty('--hx-dy', y.toFixed(2) + 'px');
		});
	}
	function requestDrift() {
		if (!ticking && driftOn) { ticking = true; window.requestAnimationFrame(driftFrame); }
	}
	function setDrift() {
		var want = !!(hasIO && driftActive && !reduced && mqWide && mqWide.matches && drifters.length);
		if (want === driftOn) return;
		driftOn = want;
		if (want) {
			window.addEventListener('scroll', requestDrift, { passive: true });
			window.addEventListener('resize', requestDrift, { passive: true });
			requestDrift();
		} else {
			window.removeEventListener('scroll', requestDrift);
			window.removeEventListener('resize', requestDrift);
			drifters.forEach(function (el) { el.style.removeProperty('--hx-dy'); });
		}
	}
	function initDrift(root) {
		if (!hasIO || !driftActive) return;
		if (!driftIO) {
			driftIO = new IntersectionObserver(function (entries) {
				entries.forEach(function (e) {
					if (e.isIntersecting) driftActive.add(e.target); else driftActive.delete(e.target);
				});
				requestDrift();
			}, { rootMargin: '25% 0px 25% 0px' });
		}
		all(root, '[data-hx-drift]').forEach(function (el) {
			if (drifters.indexOf(el) !== -1) return;
			drifters.push(el);
			driftIO.observe(el);
		});
		setDrift();
	}

	function onMotionPrefChange() {
		reduced = !!(mqReduce && mqReduce.matches);
		if (reduced) { doc.classList.remove('hx-motion'); revealAll(); }
		setDrift();
	}
	function listen(mq, fn) {
		if (!mq) return;
		if (mq.addEventListener) mq.addEventListener('change', fn); else if (mq.addListener) mq.addListener(fn);
	}

	function refresh(root) {
		initReveals(root || document);
		initDrift(root || document);
	}

	function boot() {
		// 20 Oct 2026 (owner: "animate the home page"): scroll reveals are back on. Content is
		// hidden only once this line has run (html.hx-motion), only below the fold, and never
		// under reduced motion; anything already on screen is shown at once.
		if (!reduced && hasIO) {
			revealIO = new IntersectionObserver(function (entries) {
				var jumped = now() < jumpUntil;
				entries.forEach(function (e) {
					if (!e.isIntersecting) return;
					// entering from above (scrolling back up past skipped content) or after a jump: no transition
					var fromAbove = e.boundingClientRect.top < (e.rootBounds ? e.rootBounds.top : 0) + 1;
					show(e.target, jumped || fromAbove);
					revealIO.unobserve(e.target);
				});
			}, { rootMargin: '0px 0px -6% 0px', threshold: 0 });
			doc.classList.add('hx-motion');
		}
		// a deep link (/#ritual) may land after this runs: treat the first moments as a jump
		if (location.hash && location.hash.length > 1) markJump(1200);
		window.addEventListener('scroll', onScroll, { passive: true });
		document.addEventListener('focusin', function (e) { revealWithin(e.target); });
		document.addEventListener('click', onAnchorClick);
		initReveals(document);
		initDrift(document);
		listen(mqReduce, onMotionPrefChange);
		listen(mqWide, setDrift);
		window.addEventListener('beforeprint', revealAll);
	}

	window.hxMotion = { refresh: refresh, revealAll: revealAll };
	try {
		if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot); else boot();
	} catch (err) {
		// Never leave content hidden.
		doc.classList.remove('hx-motion');
	}
})();
