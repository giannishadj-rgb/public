/* hx-motion.js — homepage / sitewide motion (system agent, 17 Oct 2026).
 *
 * Policy: reading and shopping never wait for a scroll observer or a stagger timer.
 * Every [data-hx-reveal] is marked .is-in immediately, so text, prices and CTAs are
 * visible at first paint. The only scroll-linked effects are decorative:
 *   • image settle  — [data-hx-reveal="settle|mask|mask-x"] (and img[data-hx-reveal="settle"]):
 *                     images below the fold start at scale 1.035 and ease to 1 when they
 *                     enter (CSS in hx/system.css). The image is fully visible throughout.
 *   • hairline draw — .hx-line / [data-hx-line] gets .is-drawn when it enters.
 *   • drift         — [data-hx-drift="<factor>"]: --hx-dy = offset from viewport centre ×
 *                     factor, clamped to ±6px; only ≥900px wide.
 * With prefers-reduced-motion, or without IntersectionObserver, nothing moves and
 * html.hx-motion is never set. API: window.hxMotion.refresh(root), .revealAll().
 */
(function () {
	'use strict';
	var doc = document.documentElement;
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var canObserve = 'IntersectionObserver' in window;
	var motion = !reduce && canObserve;
	var IMG_SEL = '[data-hx-reveal="settle"],[data-hx-reveal="mask"],[data-hx-reveal="mask-x"]';
	var io = null;

	function inView(el) {
		var r = el.getBoundingClientRect();
		return r.top < window.innerHeight && r.bottom > 0;
	}

	function settle(root) {
		root = root || document;
		var all = root.querySelectorAll('[data-hx-reveal]');
		var lines = root.querySelectorAll('.hx-line,[data-hx-line]');
		Array.prototype.forEach.call(all, function (el) {
			var isImg = el.matches(IMG_SEL);
			// Text, prices, CTAs, and any image already on screen: visible now, no transition.
			if (!motion || !isImg || inView(el)) {
				el.classList.add('is-in', 'hx-instant');
				return;
			}
			io.observe(el);
		});
		Array.prototype.forEach.call(lines, function (el) {
			if (!motion || inView(el)) { el.classList.add('is-drawn'); return; }
			io.observe(el);
		});
	}

	function revealAll() {
		Array.prototype.forEach.call(document.querySelectorAll('[data-hx-reveal]'), function (el) { el.classList.add('is-in'); });
		Array.prototype.forEach.call(document.querySelectorAll('.hx-line,[data-hx-line]'), function (el) { el.classList.add('is-drawn'); });
	}

	/* Drift: rAF-throttled, transform-only, clamped. */
	var drifters = [];
	var ticking = false;
	var wide = window.matchMedia ? window.matchMedia('(min-width: 900px)') : { matches: true };
	function drift() {
		ticking = false;
		var vh = window.innerHeight;
		drifters.forEach(function (el) {
			if (!wide.matches) { el.style.removeProperty('--hx-dy'); return; }
			var r = el.getBoundingClientRect();
			if (r.bottom < -100 || r.top > vh + 100) return;
			var f = parseFloat(el.getAttribute('data-hx-drift')) || 0.05;
			var dy = ((r.top + r.height / 2) - vh / 2) * -f;
			dy = Math.max(-6, Math.min(6, dy));
			el.style.setProperty('--hx-dy', dy.toFixed(2) + 'px');
		});
	}
	function onScroll() { if (!ticking) { ticking = true; window.requestAnimationFrame(drift); } }

	function init() {
		if (motion) {
			io = new IntersectionObserver(function (entries) {
				entries.forEach(function (e) {
					if (!e.isIntersecting) return;
					e.target.classList.add(e.target.hasAttribute('data-hx-reveal') ? 'is-in' : 'is-drawn');
					io.unobserve(e.target);
				});
			}, { rootMargin: '0px 0px -6% 0px', threshold: 0.05 });
			doc.classList.add('hx-motion');
		} else {
			doc.classList.remove('hx-motion');
		}
		settle(document);
		if (motion) {
			drifters = Array.prototype.slice.call(document.querySelectorAll('[data-hx-drift]'));
			if (drifters.length) {
				window.addEventListener('scroll', onScroll, { passive: true });
				window.addEventListener('resize', onScroll, { passive: true });
				drift();
			}
		}
		// Printing or a find-in-page jump must never meet an un-settled state.
		window.addEventListener('beforeprint', revealAll);
	}

	window.hxMotion = { refresh: settle, revealAll: revealAll };
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
	else init();
})();
