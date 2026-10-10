/* Homepage 2026 — header state, collection rail pagination, scroll reveals. */
(function () {
	'use strict';
	var root = document.querySelector('.h26');
	if (!root) return;

	// The global header only toggles .scrolled on scroll events; set the
	// initial state too, so a reload mid-page never shows a transparent header.
	var nav = document.getElementById('cos-nav-wrap');
	if (nav) nav.classList.toggle('scrolled', window.scrollY > 10);

	// Collection rail (mobile swipe): pagination reflects and drives position.
	var rail = root.querySelector('[data-h26-rail]');
	if (rail) {
		var list = rail.querySelector('.h26-rail__list');
		var items = Array.prototype.slice.call(list.children);
		var dotsWrap = rail.querySelector('.h26-rail__dots');
		var dots = Array.prototype.slice.call(dotsWrap.querySelectorAll('.h26-rail__dot'));
		var mq = window.matchMedia('(max-width: 699.98px)');
		var setCurrent = function (i) {
			dots.forEach(function (d, j) {
				if (i === j) d.setAttribute('aria-current', 'true');
				else d.removeAttribute('aria-current');
			});
		};
		var nearest = function () {
			var x = list.scrollLeft, best = 0, dist = Infinity;
			items.forEach(function (el, i) {
				var off = Math.abs(el.offsetLeft - items[0].offsetLeft - x);
				if (off < dist) { dist = off; best = i; }
			});
			return best;
		};
		var ticking = false;
		list.addEventListener('scroll', function () {
			if (ticking) return;
			ticking = true;
			requestAnimationFrame(function () { setCurrent(nearest()); ticking = false; });
		}, { passive: true });
		dots.forEach(function (d, i) {
			d.addEventListener('click', function () {
				var still = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
				list.scrollTo({ left: items[i].offsetLeft - items[0].offsetLeft, behavior: still ? 'auto' : 'smooth' });
				setCurrent(i);
			});
		});
		var sync = function () { dotsWrap.hidden = !mq.matches; };
		sync();
		if (mq.addEventListener) mq.addEventListener('change', sync);
	}

	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	// Scroll reveals — skipped entirely for reduced motion or without IO.
	if (reduce || !('IntersectionObserver' in window)) return;
	var els = Array.prototype.slice.call(root.querySelectorAll('.h26-reveal'));
	var vh = window.innerHeight;
	// Anything already on screen is marked visible before hiding the rest,
	// so nothing in the first viewport ever blinks out.
	els.forEach(function (el) { if (el.getBoundingClientRect().top < vh) el.classList.add('is-in'); });
	root.classList.add('h26-motion');
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (e) {
			if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
		});
	}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
	els.forEach(function (el) { if (!el.classList.contains('is-in')) io.observe(el); });
})();
