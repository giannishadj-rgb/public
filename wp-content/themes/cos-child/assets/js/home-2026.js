/* Homepage 2026 — header state, scroll reveals, parallax. */
(function () {
	'use strict';
	var root = document.querySelector('.h26');
	if (!root) return;

	// The global header only toggles .scrolled on scroll events; set the
	// initial state too, so a reload mid-page never shows a transparent header.
	var nav = document.getElementById('cos-nav-wrap');
	if (nav) nav.classList.toggle('scrolled', window.scrollY > 10);

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

	// Parallax (10 Oct 2026): each [data-h26-parallax] gets --h26-py, a drift of
	// factor × its own height as it crosses the viewport (0 when centred). The CSS
	// moves a photo inside its clipped frame, or, on the evidence plate, the text
	// layer over a photo that must stay put for its callout leaders.
	var layers = Array.prototype.slice.call(root.querySelectorAll('[data-h26-parallax]'));
	if (!layers.length) return;
	root.classList.add('h26-parallax');
	var ticking = false;
	function drift() {
		ticking = false;
		var h = window.innerHeight;
		layers.forEach(function (el) {
			var r = el.getBoundingClientRect();
			if (r.bottom < -100 || r.top > h + 100) return;
			var p = (r.top + r.height / 2 - h / 2) / (h / 2 + r.height / 2); // −1 leaving top … 1 entering bottom
			el.style.setProperty('--h26-py', (-p * parseFloat(el.getAttribute('data-h26-parallax')) * r.height).toFixed(1) + 'px');
		});
	}
	function queue() { if (!ticking) { ticking = true; window.requestAnimationFrame(drift); } }
	window.addEventListener('scroll', queue, { passive: true });
	window.addEventListener('resize', queue);
	drift();
})();
