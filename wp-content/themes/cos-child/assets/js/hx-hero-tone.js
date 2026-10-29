/* hx-hero-tone.js — ink or pearl, read from the opening photograph (25 Oct 2026).
 *
 * 1. The header (owner: "header menu transparent on all pages"). The bar floats on every page's
 *    opening with no haze behind it, so its colour comes from what is under it: this measures the
 *    band of the opening photograph (or the video's frame) behind the bar and sets
 *    body.hx-head-light when pearl labels read better than ink there (hx/chrome.css).
 * 2. The old pages' type (journal, articles, ingredients, contact, FAQ, help, shipping): their
 *    photographs live in post_content; a truly dark one marks the hero .is-dark-low, and the h1 and
 *    lead turn pearl on a dark shade (hx/typeset-site.css), where a pearl wash would turn muddy.
 * Same-origin media only; anything unreadable keeps the default (ink, light wash).
 */
(function () {
	'use strict';
	var OLD = '.cos-art-hero,.cos-faq-hero,.cos-ing-hero,.cos-jnl-hero,.cos-ct-hero,.cos-help-hero,.ship-hero,.priv-hero,.terms-hero';
	var main = document.getElementById('cos-main-content');
	if (!main || !document.createElement('canvas').getContext) return;
	var h1 = main.querySelector('h1');
	var opening = (h1 && h1.closest('section,header')) || main.firstElementChild;
	if (!opening) return;
	// the opening's photograph or film: the first one wide enough to be the plate, at the top
	var media = Array.prototype.filter.call(opening.querySelectorAll('img,video'), function (m) {
		var r = m.getBoundingClientRect();
		return r.width >= window.innerWidth * 0.6 && r.top <= 2;
	})[0];
	if (!media) return;
	var isOld = opening.matches(OLD);
	document.body.classList.add('hx-head-photo'); // the bar floats on a photograph: its labels get a halo (hx/chrome.css)

	function lin(v) { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }

	// mean relative luminance of a region (0..1 of the box) of the media as displayed (cover)
	function regionLum(box, rx, ry, rw, rh) {
		var W = 96, H = Math.max(1, Math.round(W * box.height / box.width));
		var c = document.createElement('canvas');
		c.width = W; c.height = H;
		var g = c.getContext('2d', { willReadFrequently: true });
		var iw = media.videoWidth || media.naturalWidth, ih = media.videoHeight || media.naturalHeight;
		var fit = getComputedStyle(media).objectFit;
		var s = fit === 'cover' || !fit ? Math.max(box.width / iw, box.height / ih) : Math.min(box.width / iw, box.height / ih);
		var dw = iw * s, dh = ih * s;
		var pos = (getComputedStyle(media).objectPosition || '50% 50%').split(' ');
		var px = parseFloat(pos[0]) / 100, py = parseFloat(pos[1] || '50%') / 100;
		var ox = (box.width - dw) * (isNaN(px) ? 0.5 : px), oy = (box.height - dh) * (isNaN(py) ? 0.5 : py);
		var k = W / box.width;
		g.drawImage(media, ox * k, oy * k, dw * k, dh * k);
		var d = g.getImageData(Math.floor(rx * W), Math.floor(ry * H), Math.max(1, Math.floor(rw * W)), Math.max(1, Math.floor(rh * H))).data;
		var sum = 0, n = 0;
		for (var i = 0; i < d.length; i += 4) { sum += 0.2126 * lin(d[i]) + 0.7152 * lin(d[i + 1]) + 0.0722 * lin(d[i + 2]); n++; }
		return n ? sum / n : 1;
	}

	function measure() {
		var box = media.getBoundingClientRect();
		if (!box.width || !box.height || !(media.videoWidth || media.naturalWidth)) return;
		try {
			var bar = document.getElementById('cos-nav-wrap');
			var barH = bar ? bar.getBoundingClientRect().height : 80;
			var top = regionLum(box, 0, 0, 1, Math.min(1, barH / box.height));
			// ink (#1C1D1F) and pearl (#F8F8F8) read equally at a relative luminance near 0.2:
			// darker than that behind the bar, the labels and logo turn pearl
			document.body.classList.toggle('hx-head-light', top < 0.2);
			if (isOld) {
				// only truly dark photographs (under ~0.08 where the type sits) take pearl type;
				// ink on the pearl wash reads on everything else
				opening.classList.toggle('is-dark-low', regionLum(box, 0, 0.55, 0.45, 0.45) < 0.08);
			}
		} catch (err) { /* cross-origin or decode failure: keep the defaults */ }
	}

	var ready = media.tagName === 'VIDEO' ? media.readyState >= 2 : (media.complete && media.naturalWidth);
	if (ready) measure();
	else media.addEventListener(media.tagName === 'VIDEO' ? 'loadeddata' : 'load', measure, { once: true });
	// a film's first frame is often a fade from black: read it again once it is playing
	if (media.tagName === 'VIDEO') media.addEventListener('playing', function () { setTimeout(measure, 1200); }, { once: true });
	window.addEventListener('resize', function () { clearTimeout(measure.t); measure.t = setTimeout(measure, 200); }, { passive: true });
})();
