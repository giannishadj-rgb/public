/* hx-motion.js — Caviar of Switzerland homepage motion (14 Oct 2026 rebuild).
 * Owner: system agent. Dependency-free, deferred. CSS half lives in assets/css/hx/system.css.
 *
 * REVEALS — add data-hx-reveal to any element:
 *   data-hx-reveal            fade + rise 28px (default; same as "rise")       1200ms, --hx-ease
 *   data-hx-reveal="fade"     opacity only                                      1400ms
 *   data-hx-reveal="mask"     curtain lifts from bottom (clip-path); <img> inside settles 1.08→1   1800ms
 *   data-hx-reveal="mask-x"   curtain opens left→right; <img> inside settles 1.08→1               1800ms
 *   data-hx-reveal="settle"   fade + <img> inside (or the img itself) scales 1.04→1                1800ms
 *   Delay:   data-hx-delay="240" (ms)  or  style="--hx-i:2" (× --hx-stagger 120ms)
 *   Stagger: put data-hx-stagger on a parent → its [data-hx-reveal] descendants get --hx-i = 0,1,2…
 *            in document order (an explicit --hx-i/data-hx-delay on a child still wins).
 *   Trigger: when the element's top passes ~88% of the viewport height (rootMargin -12% bottom).
 *   Guarantees: content is never hidden unless this script ran AND motion is allowed. Elements
 *   already in (or above) the viewport at start are shown instantly — no flash, no animation;
 *   for above-the-fold motion use the CSS-only .hx-load-* classes. Under prefers-reduced-motion
 *   nothing moves and everything is visible. Only opacity / transform / clip-path animate.
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

	function all(root, sel) {
		return Array.prototype.slice.call((root || document).querySelectorAll(sel));
	}

	function show(el, instant) {
		if (instant) el.classList.add('hx-instant');
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
		// Shopping and reading content is visible immediately. The hero keeps the
		// authored load-time entrance; section hooks remain for compatibility.
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
