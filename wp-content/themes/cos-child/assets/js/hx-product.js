/* hx-product.js — product pages only (hxpd, 16 Oct 2026).
 * 1. Gallery meter on phones (swipe row): counter + hairline progress.
 * 2. Quantity stepper (clamped to the input's min/max).
 * 3. Add to bag over AJAX (WooCommerce wc-ajax=add_to_cart), then WooCommerce's own
 *    'added_to_cart' event so the bag count and analytics fragments update. Any failure
 *    falls back to the normal form POST, which shows WooCommerce's notices.
 * 4. Phone bar: visible while the primary button is out of view; submits the same form.
 * 5. Links that point at a closed <details> open it.
 */
(function () {
	'use strict';
	var d = document;

	/* 1 — gallery meter */
	var gal = d.querySelector('[data-hxpd-gallery]');
	if (gal) {
		var track = gal.querySelector('.hxpd-gallery__track');
		var fill = gal.querySelector('.hxpd-gallery__fill');
		var idx = gal.querySelector('[data-hxpd-index]');
		var n = fill ? +fill.getAttribute('data-hxpd-n') : 0;
		if (track && fill && n > 1) {
			fill.style.width = (100 / n) + '%';
			var tick = false;
			var update = function () {
				tick = false;
				var w = track.clientWidth || 1;
				var i = Math.min(n - 1, Math.max(0, Math.round(track.scrollLeft / w)));
				fill.style.transform = 'translateX(' + (i * 100) + '%)';
				if (idx) idx.textContent = String(i + 1);
			};
			track.addEventListener('scroll', function () { if (!tick) { tick = true; requestAnimationFrame(update); } }, { passive: true });
		}
	}

	/* 2 — quantity */
	var form = d.querySelector('[data-hxpd-form]');
	var qty = d.getElementById('cos-qty');
	function clamp(v) {
		var min = +qty.min || 1, max = +qty.max || 10;
		v = parseInt(v, 10);
		return Math.max(min, Math.min(max, isNaN(v) ? min : v));
	}
	if (qty) {
		d.querySelectorAll('[data-hxpd-step]').forEach(function (b) {
			b.addEventListener('click', function () { qty.value = clamp((+qty.value || 1) + (+b.getAttribute('data-hxpd-step'))); });
		});
		qty.addEventListener('change', function () { qty.value = clamp(qty.value); });
	}

	/* 3 — add to bag */
	var confirmEl = d.querySelector('[data-hxpd-confirm]');
	var mainBtn = d.getElementById('cos-pdp-atc-btn');
	var barBtn = d.querySelector('[data-hxpd-bar-btn]');
	var busy = false;
	function label(btn, text) { var l = btn && btn.querySelector('[data-hxpd-label]'); if (l) l.textContent = text; }
	function setState(text, cls) {
		[mainBtn, barBtn].forEach(function (b) {
			if (!b) return;
			label(b, text);
			b.classList.remove('is-busy', 'is-added');
			if (cls) b.classList.add(cls);
		});
	}
	function ajaxUrl() {
		var p = window.wc_add_to_cart_params;
		return p && p.wc_ajax_url ? p.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart') : '/?wc-ajax=add_to_cart';
	}
	if (form && window.fetch && window.FormData) {
		form.addEventListener('submit', function (e) {
			if (form.hasAttribute('data-hxpd-native')) return; // fallback pass
			e.preventDefault();
			if (busy) return;
			busy = true;
			if (qty) qty.value = clamp(qty.value);
			setState('Adding…', 'is-busy');
			var body = new FormData();
			body.append('product_id', form.getAttribute('data-product-id'));
			body.append('quantity', qty ? qty.value : '1');
			fetch(ajaxUrl(), { method: 'POST', body: body, credentials: 'same-origin' })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (!res || res.error || !res.fragments) throw new Error('add failed');
					if (window.jQuery) window.jQuery(document.body).trigger('added_to_cart', [res.fragments, res.cart_hash]);
					busy = false;
					setState('Added to your bag', 'is-added');
					if (confirmEl) {
						confirmEl.innerHTML = '';
						confirmEl.appendChild(d.createTextNode('Added to your bag. '));
						var a = d.createElement('a');
						a.className = 'hxpd-confirm__link';
						a.href = (window.wc_add_to_cart_params && window.wc_add_to_cart_params.cart_url) || '/cart/';
						a.textContent = 'View bag';
						confirmEl.appendChild(a);
					}
					setTimeout(function () { setState('Add to bag'); }, 2600);
				})
				.catch(function () {
					// let WooCommerce handle it the classic way (it prints the reason)
					form.setAttribute('data-hxpd-native', '');
					form.submit();
				});
		});
	}

	/* 4 — phone bar */
	var bar = d.querySelector('[data-hxpd-bar]');
	if (bar && mainBtn && 'IntersectionObserver' in window) {
		var show = function (on) {
			bar.classList.toggle('is-on', on);
			bar.setAttribute('aria-hidden', on ? 'false' : 'true');
			if (barBtn) barBtn.setAttribute('tabindex', on ? '0' : '-1');
			d.body.classList.toggle('hxpd-bar-on', on);
		};
		new IntersectionObserver(function (es) { show(!es[0].isIntersecting); }, { threshold: 0 }).observe(form || mainBtn);
		if (barBtn) barBtn.addEventListener('click', function () {
			if (form.requestSubmit) form.requestSubmit(); else form.dispatchEvent(new Event('submit', { cancelable: true }));
		});
	}

	/* 5 — open a details target */
	d.querySelectorAll('[data-hxpd-open]').forEach(function (a) {
		a.addEventListener('click', function () {
			var t = d.getElementById(a.getAttribute('data-hxpd-open'));
			if (t && t.tagName === 'DETAILS') t.open = true;
		});
	});
})();
