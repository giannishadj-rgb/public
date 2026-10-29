(function () {
	'use strict';

	var video = document.getElementById('hxrt-hero-video');
	var control = document.querySelector('.hxrt-hero__control');
	if (!video || !control) return;

	var label = control.querySelector('.hxrt-hero__control-label');
	var reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

	function render(paused) {
		control.classList.toggle('is-paused', paused);
		control.setAttribute('aria-label', paused ? 'Play hero video' : 'Pause hero video');
		label.textContent = paused ? 'Play' : 'Pause';
	}

	function pause() {
		video.pause();
		render(true);
	}

	function play() {
		var playback = video.play();
		if (playback && typeof playback.then === 'function') {
			playback.then(function () { render(false); }).catch(pause);
		} else {
			render(false);
		}
	}

	control.addEventListener('click', function () {
		if (video.paused) play();
		else pause();
	});

	function handleMotionPreference(event) {
		if (event.matches) pause();
	}

	if (typeof reducedMotion.addEventListener === 'function') {
		reducedMotion.addEventListener('change', handleMotionPreference);
	} else if (typeof reducedMotion.addListener === 'function') {
		reducedMotion.addListener(handleMotionPreference);
	}

	if (reducedMotion.matches) pause();
	else play();
}());
