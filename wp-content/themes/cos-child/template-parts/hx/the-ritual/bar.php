<?php
/**
 * The Ritual — section bar (hxrt-bar), sticky under the header (clarify pass, 23 Oct 2026).
 * The page's three parts as anchors, the current one marked (aria-current="location"); inside
 * "Step by step" the right side names the step in view ("Step 2 of 4 · Treat"). Same register
 * as the science chapters' sub-navigation. Without JS it is a plain row of in-page links.
 */
defined( 'ABSPATH' ) || exit;
$parts = $args['parts'] ?? array();
?>
<nav class="hxrt-bar" aria-label="The Ritual, on this page">
	<div class="hx-wrap hxrt-bar__in">
		<ol class="hxrt-bar__list">
			<?php foreach ( $parts as $p ) : ?>
			<li><a class="hxrt-bar__link" href="#<?php echo esc_attr( $p['id'] ); ?>" data-hxrt-part="<?php echo esc_attr( $p['id'] ); ?>"><?php echo esc_html( $p['short'] ); ?></a></li>
			<?php endforeach; ?>
		</ol>
		<p class="hxrt-bar__step" aria-hidden="true" hidden></p>
	</div>
</nav>
<script nonce="<?php echo esc_attr( function_exists( 'cos_csp_nonce' ) ? cos_csp_nonce() : '' ); ?>">
(function(){
// The bar prints before the parts it tracks, so start once the document is parsed.
if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',init);}else{init();}
function init(){
var bar=document.querySelector('.hxrt-bar');if(!bar||!document.querySelectorAll)return;
var links=bar.querySelectorAll('[data-hxrt-part]'),parts=[],i;
for(i=0;i<links.length;i++){var el=document.getElementById(links[i].getAttribute('data-hxrt-part'));if(el)parts.push([el,links[i]]);}
var steps=document.querySelectorAll('.hxrt-step'),out=bar.querySelector('.hxrt-bar__step'),cur=null,curStep=null,ticking=false;
function line(){return bar.getBoundingClientRect().bottom+48;}
function update(){
	ticking=false;var y=line(),now=null,st=null,j;
	for(j=0;j<parts.length;j++){if(parts[j][0].getBoundingClientRect().top<=y&&parts[j][0].getBoundingClientRect().bottom>y)now=parts[j];}
	if(now!==cur){if(cur)cur[1].removeAttribute('aria-current');if(now)now[1].setAttribute('aria-current','location');cur=now;}
	if(now&&now[0].id==='step-by-step'){for(j=0;j<steps.length;j++){if(steps[j].getBoundingClientRect().top<=y+window.innerHeight*.25)st=steps[j];}}
	if(st!==curStep){curStep=st;if(st){out.textContent=st.getAttribute('data-hxrt-label');out.hidden=false;}else{out.hidden=true;}}
}
function onScroll(){if(!ticking){ticking=true;requestAnimationFrame(update);}}
window.addEventListener('scroll',onScroll,{passive:true});window.addEventListener('resize',onScroll);update();
}
})();
</script>
