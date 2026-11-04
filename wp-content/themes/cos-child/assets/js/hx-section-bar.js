/* Section bar (hx-bar, template-parts/hx/section-bar.php), 31 Oct 2026; was the Ritual page's inline
   script (23 Oct). Marks the in-page part in view with aria-current="location". When the bar has an
   aside (.hx-bar__aside), it names the [data-hx-bar-label] element in view inside the current part
   (The Ritual: "Step 2 of 4 · Treat"). On narrow screens the list scrolls sideways, so the current
   link is kept in view. Loaded deferred, so the document is already parsed. */
(function () {
	var bar = document.querySelector('.hx-bar');
	if (!bar || !window.requestAnimationFrame) return;
	var list = bar.querySelector('.hx-bar__list'), out = bar.querySelector('.hx-bar__aside');

	function reveal(a) {
		if (!list || list.scrollWidth <= list.clientWidth) return;
		var l = a.offsetLeft - list.offsetLeft, r = l + a.offsetWidth;
		if (l < list.scrollLeft || r > list.scrollLeft + list.clientWidth) list.scrollTo({ left: Math.max(0, l - 16), behavior: 'smooth' });
	}

	/* When the row still scrolls (1 Nov 2026), fade the edge that has more links behind it, so a
	   clipped label reads as "continues" rather than as the end of the bar. */
	function edges() {
		if (!list) return;
		var max = list.scrollWidth - list.clientWidth;
		bar.classList.toggle('is-more-start', max > 1 && list.scrollLeft > 1);
		bar.classList.toggle('is-more-end', max > 1 && list.scrollLeft < max - 1);
	}
	if (list) {
		list.addEventListener('scroll', edges, { passive: true });
		window.addEventListener('resize', edges);
		edges();
	}

	var here = bar.querySelector('[aria-current="page"]');
	if (here) reveal(here);

	// each link's part, plus any further sections the chapter covers (data-hx-bar-also)
	var links = bar.querySelectorAll('[data-hx-bar-part]'), parts = [], i;
	for (i = 0; i < links.length; i++) {
		var ids = [links[i].getAttribute('data-hx-bar-part')].concat((links[i].getAttribute('data-hx-bar-also') || '').split(' '));
		for (var k = 0; k < ids.length; k++) {
			var el = ids[k] && document.getElementById(ids[k]);
			if (el) parts.push([el, links[i]]);
		}
	}
	if (!parts.length) return;

	var cur = null, curLabel = null, ticking = false;
	function update() {
		ticking = false;
		var y = bar.getBoundingClientRect().bottom + 48, now = null, lab = null, j, r;
		for (j = 0; j < parts.length; j++) {
			r = parts[j][0].getBoundingClientRect();
			if (r.top <= y && r.bottom > y) now = parts[j];
		}
		if (now !== cur) {
			if (cur) cur[1].removeAttribute('aria-current');
			if (now) { now[1].setAttribute('aria-current', 'location'); reveal(now[1]); }
			cur = now;
		}
		if (!out) return;
		if (now) {
			var labels = now[0].querySelectorAll('[data-hx-bar-label]');
			for (j = 0; j < labels.length; j++) {
				if (labels[j].getBoundingClientRect().top <= y + window.innerHeight * 0.25) lab = labels[j];
			}
		}
		if (lab !== curLabel) {
			curLabel = lab;
			if (lab) { out.textContent = lab.getAttribute('data-hx-bar-label'); out.hidden = false; } else { out.hidden = true; }
		}
	}
	function onScroll() { if (!ticking) { ticking = true; requestAnimationFrame(update); } }
	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', onScroll);
	update();
})();
