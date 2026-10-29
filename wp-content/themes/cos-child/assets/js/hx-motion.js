/* hx-motion.js — Caviar of Switzerland motion, ONE engine for the whole site (25 Oct 2026).
 * Owner request: "animate all the pages so they are uniform across the entire site". This is the
 * homepage engine of 20 Oct 2026 (the owner's chosen motion), now loaded on every page by
 * inc/hx-system.php and inc/home-2026.php. The CSS half lives in hx/system.css (all pages) and
 * home-staging/system.css (homepage); their reveal blocks are kept identical.
 *
 * THE LANGUAGE (same on every page):
 *   Opening   the first photograph racks into focus and the h1 is written by a band of light
 *             (CSS only, hx/system.css "Opening"; the homepage hero does the same in hero.css).
 *   Headings  h2 section headings are written by the same light as they scroll in.
 *   Text      blocks rise 14px and fade in (700ms), lists of cards stagger (capped at 150ms).
 *   Images    photographs settle 1.04 → 1 inside their frame; never hidden.
 *   Task pages (cart, checkout, account) have no reveals: only control feedback moves there.
 *
 * REVEALS — data-hx-reveal on any element:
 *   data-hx-reveal            text: fade + rise 14px (default; same as "rise")       700ms, --hx-ease
 *   data-hx-reveal="fade"     text: opacity only                                       600ms
 *   data-hx-reveal="light"    headings: a band of light writes the words in (starts mostly lit) 800ms
 *                             (an h2 marked "", "rise" or "fade" is promoted to "light")
 *   data-hx-reveal="mask" / "mask-x" / "settle"
 *                             images: the <img> inside settles 1.04→1 within its frame;  900ms
 *   data-hx-reveal="blind"    a group of photographs rises 14px into place, nothing hidden   800ms
 *   Delay:   data-hx-delay="240" (ms)  or  style="--hx-i:2" (× --hx-stagger 50ms); the CSS caps
 *            the total at --hx-delay-max (150ms).
 *   Stagger: data-hx-stagger on a parent → its [data-hx-reveal] descendants get --hx-i = 0,1,2…
 *   Hairline: .hx-line / [data-hx-line] draws left→right once in view (.is-drawn).
 *
 * HAND-AUTHORED PAGES (journal, FAQ, contact, help, shipping, skin concerns, sitemap, 404…) carry
 *   no reveal markup in their post_content, so autoTag() applies the same language at load:
 *   h2 → light; h3/h4, paragraphs, lists, quotes, tables, details, button rows and cards → rise;
 *   photographs in a clipping frame → settle; runs of 3+ cards → stagger. Skipped: the opening
 *   section (it has its own entrance), forms and anything inside them, navigation, hidden nodes.
 *
 * Trigger: the element's top passes 94% of the viewport. Instant (no transition) when: on screen
 *   or above at start; entering from above; after a jump (deep link, End/Home, scrollbar drag,
 *   find-in-page, anchor jump > 2 viewports); keyboard focus moves into it. Content is hidden only
 *   when this script ran AND motion is allowed; prefers-reduced-motion shows everything at once.
 *
 * ANCHORS — same-page links glide when short (≤ 2 viewports), jump when long; focus follows.
 * DRIFT — data-hx-drift="0.06": vertical parallax ≥900px, clamped ±120px.
 * API: window.hxMotion.refresh(root?) · window.hxMotion.revealAll()
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
		el.classList.add(el.hasAttribute('data-hx-reveal') ? 'is-in' : 'is-drawn');
	}

	function revealAll() {
		all(document, '[data-hx-reveal],.hx-line,[data-hx-line]').forEach(function (el) { show(el, true); });
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

	// h2 section headings are written by the light on every page, whatever the markup says
	function promoteHeadings(root) {
		all(root, 'h2[data-hx-reveal=""],h2[data-hx-reveal="rise"],h2[data-hx-reveal="fade"]').forEach(function (h) {
			h.setAttribute('data-hx-reveal', 'light');
		});
	}

	/* Hand-authored pages: apply the shared language to their plain markup. Runs only when the
	   page's main has no reveal markup of its own, and never on task pages. */
	var AUTO_SEL = 'h2,h3,h4,p,ul,ol,dl,blockquote,table,details,article,.wp-block-buttons,a[class*="card"],div[class*="card"]';
	function autoTag() {
		var main = document.getElementById('cos-main-content');
		var body = document.body;
		if (!main || !body) return;
		if (/(^|\s)(woocommerce-cart|woocommerce-checkout|woocommerce-account|cos-h26)(\s|$)/.test(body.className)) return;
		if (main.querySelector('[data-hx-reveal]')) return;
		var h1 = main.querySelector('h1');
		var opening = h1 ? (h1.closest('section,header') || h1.parentElement) : null;
		var skip = 'form,nav,dialog,[aria-hidden="true"],.hx-sr,.screen-reader-text';
		var picked = [];
		all(main, AUTO_SEL).forEach(function (el) {
			if (opening && opening.contains(el)) return;
			if (el.closest(skip) || el.querySelector('form,input,select,textarea')) return;
			if (!el.getClientRects().length) return;
			// outermost only: a card's own heading and text travel with the card
			for (var i = 0; i < picked.length; i++) { if (picked[i].contains(el)) return; }
			picked.push(el);
		});
		picked.forEach(function (el) {
			el.setAttribute('data-hx-reveal', el.tagName === 'H2' ? 'light' : '');
		});
		// a run of 3+ sibling cards / items reads as a list: stagger it
		picked.forEach(function (el) {
			var p = el.parentElement;
			if (!p || p.hasAttribute('data-hx-stagger')) return;
			var n = 0;
			for (var c = p.firstElementChild; c; c = c.nextElementSibling) { if (c.tagName === el.tagName && c.hasAttribute('data-hx-reveal')) n++; }
			if (n >= 3) p.setAttribute('data-hx-stagger', '');
		});
		// photographs outside the picked blocks settle inside their own clipping frame
		all(main, 'img').forEach(function (img) {
			if ((opening && opening.contains(img)) || img.closest('[data-hx-reveal]') || !img.parentElement) return;
			var cs = getComputedStyle(img.parentElement);
			if (/hidden|clip/.test(cs.overflow + cs.overflowX) && img.getBoundingClientRect().width > 160) img.setAttribute('data-hx-reveal', 'settle');
		});
	}

	function initReveals(root) {
		promoteHeadings(root || document);
		var els = all(root, '[data-hx-reveal],.hx-line,[data-hx-line]');
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
		try { autoTag(); } catch (err) { /* plain markup stays as it is */ }
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
