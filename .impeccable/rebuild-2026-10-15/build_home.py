from pathlib import Path
import re

theme = Path('wp-content/themes/cos-child')
css = theme / 'assets/css/hx'
parts = theme / 'template-parts/home-2026'

# Keep the existing, opt-in system API and hero hooks; replace its visual values.
p = css / 'system.css'
s = p.read_text(encoding='utf-8')
s = s.replace('--hx-white:#FBFAF7', '--hx-white:#FCFBF8').replace('--hx-pearl:#F2F2EF', '--hx-pearl:#FCFBF8').replace('--hx-ivory:#F3EFE7', '--hx-ivory:#F4F1EA').replace('--hx-ink:#1C1D1F', '--hx-ink:#202221').replace('--hx-muted:#5C5C5A', '--hx-muted:#5D5F60')
s = s.replace("--hx-serif:'Cormorant Garamond','EB Garamond',Georgia,'Times New Roman',serif", "--hx-serif:'Unna',Georgia,serif")
s = s.replace('--hx-fs-heading:clamp(2.125rem,1.52rem + 2.48vw,3.75rem)', '--hx-fs-heading:clamp(2.5rem,1.65rem + 3.4vw,4.5rem)')
s = s.replace('--hx-fs-detail:.875rem', '--hx-fs-detail:1rem').replace('--hx-fs-label:.71875rem', '--hx-fs-label:.9375rem').replace('--hx-tr-btn:.16em', '--hx-tr-btn:.015em')
s = s.replace('--hx-dur-reveal:1200ms', '--hx-dur-reveal:600ms').replace('--hx-dur-image:1800ms', '--hx-dur-image:800ms').replace('--hx-stagger:120ms', '--hx-stagger:70ms').replace('--hx-rise:28px', '--hx-rise:12px')
s = s.replace('font-size:.75rem;', 'font-size:.9375rem;').replace('text-transform:uppercase;\n\ttext-align:center;', 'text-transform:none;\n\ttext-align:center;')
# Unna has real regular and italic at 400. Do not synthesize a third display voice.
s += '''
/* Commissioned warm atelier system, 15 October 2026. */
@font-face{font-family:Unna;src:url('../../fonts/unna-regular.ttf') format('truetype');font-style:normal;font-weight:400;font-display:swap}
@font-face{font-family:Unna;src:url('../../fonts/unna-italic.ttf') format('truetype');font-style:italic;font-weight:400;font-display:swap}
body.cos-h26{background:var(--hx-ivory);caret-color:var(--hx-ink);scrollbar-color:var(--hx-chrome) var(--hx-ivory)}
body.cos-h26 .h26 :is(h1,h2,h3,p,figure,ol,ul,dl,dd){margin:0}
body.cos-h26 :is(.hx-display,.hx-heading,.hx-title,.hx-numeral,.hx-step),body.cos-h26 :is(.hx-display,.hx-heading,.hx-title) em{font-weight:400}
body.cos-h26 .hx-lead{font-weight:400}
body.cos-h26 .hx-link{font-size:1rem;min-height:48px}
body.cos-h26 .hx-btn{font-size:.9375rem;letter-spacing:.015em;min-height:52px;text-transform:none}
body.cos-h26 .hx-btn::after{display:none}
body.cos-h26 .hx-wrap{max-width:calc(1440px + 2 * var(--hx-margin))}
body.cos-h26 .h26 :is(a,button){touch-action:manipulation}
'''
p.write_text(s, encoding='utf-8')

# Visible content and a single authored hero entrance; no section reveal machinery.
for name in ('collection','caviar','trust','provenance','final','closing'):
    p = parts / (name + '.php')
    s = p.read_text(encoding='utf-8')
    s = re.sub(r'\sdata-hx-(?:reveal|delay|stagger)(?:="[^"]*")?', '', s)
    p.write_text(s, encoding='utf-8')

p=parts/'collection.php'
s=p.read_text(encoding='utf-8').replace('one benefit (hidden on phones)', 'one benefit on every device')
s=s.replace('The Collection</h2>', 'The collection</h2>')
p.write_text(s,encoding='utf-8')

p=parts/'caviar.php'
s=p.read_text(encoding='utf-8')
s=re.sub(r'\s*<p class="hx-label hxv__label">.*?</p>', '', s)
s=s.replace('Caviar in every formula', 'The essence of caviar')
p.write_text(s,encoding='utf-8')

p=parts/'trust.php'
s=p.read_text(encoding='utf-8')
s=re.sub(r'\s*<p class="hx-label hxs__label">.*?</p>', '', s)
s=s.replace('<h2 id="hxs-title" class="hx-sr">Protect. Repair. Rejuvenate.</h2>', '<h2 id="hxs-title" class="hx-heading hxs__title">Swiss science,<br><em>considered care.</em></h2>')
s=re.sub(r'\s*<div class="hxs__scale"[^>]*>.*?</div>', '', s)
# Product names remain the attribution; repeating a packshot adds no evidence.
s=re.sub(r'\s*<\?php echo cos_h26_picture\( \'collection-object-\'.*?\?>', '', s)
p.write_text(s,encoding='utf-8')

(parts/'provenance.php').write_text('''<?php
/** Swiss provenance: branded formulation image; no claim that this depicts the actual facility. */
defined( 'ABSPATH' ) || exit;
?>
<section id="heritage" class="hxp" aria-labelledby="hxp-title">
  <div class="hxp__inner hx-wrap">
    <div class="hxp__copy">
      <h2 id="hxp-title" class="hx-heading">Fewer things,<br><em>made well.</em></h2>
      <p class="hx-lead hxp__lede">Precision is not a style here; it is how the work is done. Each formula is made in one place, patiently, and with care.</p>
      <p class="hxp__links"><a class="hx-link" href="<?php echo esc_url( home_url( '/heritage/' ) ); ?>">Our Swiss heritage<span class="hx-arrow" aria-hidden="true"></span></a></p>
    </div>
    <figure class="hxp__photo">
      <?php echo cos_h26_picture( 'philosophy-lab', array( 640, 1122 ), array( 1122, 1402 ), 'Caviar of Switzerland cream beneath a polished steel filling nozzle', array( 'sizes' => '(min-width: 900px) 47vw, 100vw', 'w' => 1122 ) ); ?>
      <figcaption>Care in every detail.</figcaption>
    </figure>
    <dl class="hxp__facts">
      <div><dt>Zug, Switzerland</dt><dd>Where every formula is created and filled</dd></div>
      <div><dt>20+ years</dt><dd>Making skincare in Zug</dd></div>
      <div><dt>ISO 22716</dt><dd>Good manufacturing practice for cosmetics</dd></div>
    </dl>
    <p class="hxp__formulate"><a class="hx-link" href="<?php echo esc_url( home_url( '/science/swiss-formulation/' ) ); ?>">How we formulate in Zug<span class="hx-arrow" aria-hidden="true"></span></a></p>
  </div>
</section>
''', encoding='utf-8')

p=parts/'final.php'
s=p.read_text(encoding='utf-8')
s=s.replace('class="hxr__head"','class="hxr__head hx-wrap"').replace('class="hxr__stage"','class="hxr__stage hx-wrap"')
s=s.replace('<span>The</span> <em>Ritual</em>', 'The daily <em>ritual</em>')
s=s.replace('Always in this order, from the lightest texture to the richest.', 'Four steps, morning and evening. From the lightest texture to the richest.')
s=s.replace("array( 4, 5 )", "array( 4, 5 )")
s=s.replace("'(min-width: 700px) 46vw, 86vw'", "'(min-width: 1100px) 23vw, (min-width: 640px) 44vw, 100vw'")
s=re.sub(r"\s*'art'\s*=> array\( array\( 'media'.*?\) \),", '', s)
s=s.replace('<span class="hxr__word" aria-hidden="true">', '<span class="hxr__word">')
s=re.sub(r'\s*<span class="hxr__track".*?</span></span>', '', s)
p.write_text(s,encoding='utf-8')

p=parts/'closing.php'
s=p.read_text(encoding='utf-8').replace('class="hxz__cta"','class="hx-btn hxz__cta"')
s=s.replace('<span class="hxz__cta-rule" aria-hidden="true"></span>', '<span class="hx-arrow" aria-hidden="true"></span>')
s=s.replace('class="hx-label"', 'class="hxz__house-name"').replace('class="hx-label hxz__place"', 'class="hxz__place"')
p.write_text(s,encoding='utf-8')

(css/'collection.css').write_text('''/* Four real objects, one curated collection. */
body.cos-h26 .hxc{background:var(--hx-white);padding-block:var(--hx-section-sm);color:var(--hx-text)}
.hxc__head{display:grid;gap:24px;margin-bottom:48px}
.hxc__lede{max-width:28em}
.hxc__list{list-style:none;padding:0;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:48px 20px}
.hxc__item{min-width:0;display:grid;grid-row:span 6;grid-template-rows:subgrid}
.hxc__link{display:grid;grid-row:span 6;grid-template-rows:subgrid;justify-items:center;text-align:center;color:inherit;text-decoration:none;min-width:0}
.hxc__stage{display:block;width:100%;aspect-ratio:2/3;overflow:clip}
.hxc__object,.hxc__object img{display:block;width:100%;height:100%;object-fit:contain;transition:transform 500ms var(--hx-ease)}
.hxc__step{display:flex;align-items:center;gap:10px;font:400 16px/1.4 var(--hx-sans);color:var(--hx-muted);padding-top:16px}
.hxc__num{font-variant-numeric:tabular-nums}
.hxc__verb{font-style:normal}
.hxc__name{font:400 clamp(22px,2vw,28px)/1.14 var(--hx-serif);color:var(--hx-ink);padding-top:12px;text-wrap:balance}
.hxc__name span{display:block}
.hxc__benefit{display:block;font:400 16px/1.55 var(--hx-sans);max-width:23em;padding-top:14px;text-wrap:pretty}
.hxc__meta{display:flex;flex-wrap:wrap;align-items:center;justify-content:center;font:400 16px/1.5 var(--hx-sans);padding-top:20px;gap:8px}
.hxc__price{font-weight:500;color:var(--hx-ink)}
.hxc__size{color:var(--hx-muted)}
.hxc__dot{width:1px;height:12px;background:var(--hx-chrome)}
.hxc__cta{display:inline-flex;align-items:center;min-height:48px;margin-top:8px;font:400 16px/1.5 var(--hx-sans);color:var(--hx-ink);text-decoration:underline;text-underline-offset:6px;text-decoration-color:var(--hx-chrome)}
.hxc__link:visited{color:inherit}
.hxc__link:focus-visible{outline:2px solid var(--hx-ink);outline-offset:6px}
@media(hover:hover){.hxc__link:hover .hxc__object img{transform:translateY(-6px)}.hxc__link:hover .hxc__cta{text-decoration-color:var(--hx-ink)}}
@media(min-width:900px){.hxc__head{grid-template-columns:1fr 1fr;align-items:end;margin-bottom:56px}.hxc__lede{justify-self:end}.hxc__list{grid-template-columns:repeat(4,minmax(0,1fr));gap:32px}.hxc__stage{aspect-ratio:3/4}}
@media(max-width:359px){.hxc__list{column-gap:16px}.hxc__name{font-size:21px}.hxc__meta{gap:5px}.hxc__benefit{font-size:16px}}
@media(prefers-reduced-motion:reduce){.hxc__object img{transition:none}.hxc__link:hover .hxc__object img{transform:none}}
''',encoding='utf-8')

(css/'caviar.css').write_text('''/* The ingredient as a light, photographic interlude. */
.hxv{background:var(--hx-ivory);color:var(--hx-text)}
.hxv__plate{position:relative;isolation:isolate;min-height:600px;display:grid;align-items:start}
.hxv__head{position:relative;z-index:1;width:100%;box-sizing:border-box;max-width:calc(var(--hx-max) + 2 * var(--hx-margin));padding:72px var(--hx-margin) 0;margin-inline:auto}
.hxv__title{max-width:8em}
.hxv__species{font:400 16px/1.5 var(--hx-sans);color:var(--hx-muted);margin-top:20px!important;max-width:22em}
.hxv__photo{position:absolute;inset:0;z-index:-1}
.hxv__photo :is(picture,img){display:block;width:100%;height:100%}
.hxv__photo img{object-fit:cover;object-position:60% center}
.hxv__notes{max-width:1440px;box-sizing:content-box;padding:48px var(--hx-margin) 72px;margin-inline:auto;display:grid;gap:32px}
.hxv__statement{font:400 clamp(28px,2.6vw,40px)/1.2 var(--hx-serif);color:var(--hx-ink);max-width:20em;text-wrap:balance}
.hxv__facts{display:grid;gap:24px;max-width:56em}
.hxv__fact{display:grid;gap:8px}.hxv__k{font:500 16px/1.5 var(--hx-sans);color:var(--hx-ink)}.hxv__v{font:400 16px/1.65 var(--hx-sans)}
@media(min-width:900px){.hxv__plate{min-height:650px;height:51vw;max-height:850px}.hxv__head{padding-top:96px}.hxv__notes{padding-block:64px 96px;grid-template-columns:1fr 1fr;column-gap:80px}.hxv__statement{grid-row:span 2}.hxv__facts{grid-template-columns:1fr 1fr;gap:32px}}
@media(max-width:699px){.hxv__plate{min-height:600px;height:150vw;max-height:760px}.hxv__head{padding-top:56px}.hxv__photo img{object-position:50% 100%}.hxv__title{font-size:40px;max-width:7em}.hxv__species{max-width:16em}.hxv__notes{padding-top:40px}}
''',encoding='utf-8')

(css/'science.css').write_text('''/* Readable Swiss science, with carefully attributed single-formula evidence. */
.hxs{background:var(--hx-white);padding-block:var(--hx-section-sm);color:var(--hx-text)}
.hxs__title{max-width:10em}
.hxs__pillars{display:grid;list-style:none;padding:0;gap:36px;margin-top:48px!important}
.hxs__verb{font-size:clamp(32px,3vw,44px);line-height:1.1}
.hxs__text{margin-top:16px!important;max-width:29em}
.hxs__actives{margin-top:16px!important;color:var(--hx-muted)}
.hxs__active{display:inline;white-space:normal}
.hxs__proof{margin-top:64px;border-top:1px solid var(--hx-line-strong);padding-top:40px}
.hxs__proof-title{font:400 32px/1.2 var(--hx-serif);color:var(--hx-ink)}
.hxs__proof-note{margin-top:16px!important;max-width:31em}
.hxs__results{list-style:none;padding:0;display:grid;gap:36px;margin-top:40px!important}
.hxs__result{min-width:0;position:relative}
.hxs__object{display:none}
.hxs__product{font:500 16px/1.5 var(--hx-sans);color:var(--hx-ink)}
.hxs__fig-n{font:400 56px/1.05 var(--hx-serif);color:var(--hx-ink);margin-top:18px!important;font-variant-numeric:lining-nums}
.hxs__pct{font-size:.6em;margin-left:3px}
.hxs__finding{font:400 26px/1.2 var(--hx-serif);margin-top:10px!important;color:var(--hx-ink)}
.hxs__method{margin-top:16px!important;max-width:29em}.hxs__kind{font-weight:500;color:var(--hx-text)}
.hxs__result--reported{border-top:1px solid var(--hx-line);padding-top:28px}
.hxs__result--reported .hxs__fig-n{font-size:36px}
.hxs__more{margin-top:24px!important}
@media(min-width:700px){.hxs__pillars{grid-template-columns:repeat(3,minmax(0,1fr));gap:36px}.hxs__pillar{padding-top:24px;border-top:1px solid var(--hx-line)}.hxs__results{grid-template-columns:repeat(2,minmax(0,1fr));column-gap:48px}.hxs__result--reported{grid-column:1/-1;display:grid;grid-template-columns:1fr 1fr;column-gap:48px}.hxs__result--reported .hxs__fig-n{grid-column:1;grid-row:2}.hxs__result--reported .hxs__finding{grid-column:1}.hxs__result--reported .hxs__method{grid-column:2;grid-row:1/4;margin-top:0!important}}
@media(min-width:1100px){.hxs__pillars{margin-top:64px!important;gap:56px}.hxs__proof{display:grid;grid-template-columns:1fr 2fr;gap:64px;margin-top:80px;padding-top:48px}.hxs__results{margin-top:0!important}}
''',encoding='utf-8')

(css/'provenance.css').write_text('''/* A quiet juxtaposition of Swiss craft and the branded formula. */
.hxp{background:var(--hx-ivory);padding-block:var(--hx-section-sm);color:var(--hx-text)}
.hxp__inner{display:grid;gap:40px}.hxp__copy{align-self:center}.hxp__copy h2{max-width:8em}.hxp__lede{max-width:28em;margin-top:28px!important}.hxp__links{margin-top:24px!important}
.hxp__photo{min-width:0}.hxp__photo :is(picture,img){display:block;width:100%;height:auto}.hxp__photo img{aspect-ratio:4/5;object-fit:cover}.hxp__photo figcaption{font:400 16px/1.5 var(--hx-sans);color:var(--hx-muted);padding-top:14px}
.hxp__facts{display:grid;gap:28px;border-top:1px solid var(--hx-line-strong);padding-top:32px}.hxp__facts dt{font:400 26px/1.2 var(--hx-serif);color:var(--hx-ink)}.hxp__facts dd{font:400 16px/1.6 var(--hx-sans);margin-top:10px!important;max-width:22em}
.hxp__formulate{margin-top:-16px!important}
@media(min-width:700px){.hxp__facts{grid-template-columns:repeat(3,minmax(0,1fr));gap:32px}}
@media(min-width:900px){.hxp__inner{grid-template-columns:5fr 6fr;column-gap:9%;row-gap:56px}.hxp__photo{grid-column:2;grid-row:1}.hxp__facts,.hxp__formulate{grid-column:1/-1}.hxp__photo img{max-height:680px}.hxp__formulate{justify-self:start}}
''',encoding='utf-8')

(css/'ritual.css').write_text('''/* Four visible steps. Native reading order and comfortable touch access. */
.hxr{background:var(--hx-white);padding-block:var(--hx-section-sm);color:var(--hx-text)}
.hxr__head{display:grid;gap:24px;margin-bottom:48px!important}.hxr__title{font:400 var(--hx-fs-heading)/1.06 var(--hx-serif);color:var(--hx-ink)}.hxr__title em{font-weight:400}.hxr__lede{font:400 18px/1.6 var(--hx-sans);max-width:26em}
.hxr__steps{list-style:none;padding:0;display:grid;gap:48px}.hxr__step{min-width:0}.hxr__link{display:block;color:inherit;text-decoration:none}.hxr__link:visited{color:inherit}
.hxr__frame{display:block;position:relative;overflow:clip}.hxr__shot,.hxr__shot img{display:block;width:100%;height:100%;object-fit:cover;filter:none;transition:transform 600ms var(--hx-ease)}.hxr__frame{aspect-ratio:4/5}.hxr__word{display:none}
.hxr__text{display:block;padding-top:24px}.hxr__stepno{font:400 25px/1.2 var(--hx-serif);color:var(--hx-ink)}.hxr__stepno::after{content:' — '}.hxr__inner,.hxr__more{display:block;opacity:1;transform:none}.hxr__how{display:block;margin-top:14px;font:400 16px/1.7 var(--hx-sans);max-width:34em}.hxr__rule,.hxr__track{display:none}
.hxr__link:focus-visible{outline:2px solid var(--hx-ink);outline-offset:7px}
@media(hover:hover){.hxr__link:hover .hxr__shot img{transform:scale(1.025)}}
@media(min-width:640px){.hxr__steps{grid-template-columns:repeat(2,minmax(0,1fr));gap:48px 32px}.hxr__frame{aspect-ratio:4/5}}
@media(min-width:1100px){.hxr__head{grid-template-columns:1fr 1fr;align-items:end;margin-bottom:56px!important}.hxr__lede{justify-self:end}.hxr__steps{grid-template-columns:repeat(4,minmax(0,1fr));gap:24px}.hxr__frame{aspect-ratio:3/4}}
@media(prefers-reduced-motion:reduce){.hxr__shot img{transition:none}.hxr__link:hover .hxr__shot img{transform:none}}
''',encoding='utf-8')

(css/'closing.css').write_text('''/* The house statement, one final action, useful service facts. */
.hxz{background:var(--hx-ivory);padding-block:var(--hx-section-sm);color:var(--hx-ink)}
.hxz__inner{display:grid;gap:48px}.hxz__title{font-size:clamp(40px,5.6vw,80px);line-height:1.04;max-width:15em}.hxz__line{display:block}.hxz__title em{font-weight:400}
.hxz__act{display:grid;justify-items:start;gap:24px}.hxz__cta{max-width:100%;white-space:normal}.hxz__assure{list-style:none;padding:0;display:flex;flex-wrap:wrap;gap:12px 28px;font:400 16px/1.5 var(--hx-sans);color:var(--hx-muted)}
.hxz__sign{border-top:1px solid var(--hx-line-strong);padding-top:28px}.hxz__rule{display:none}.hxz__house{display:flex;align-items:center;flex-wrap:wrap;gap:16px;font:400 16px/1.5 var(--hx-sans)}.hxz__cross{width:22px;height:22px;flex:none}.hxz__house-name{color:var(--hx-ink)}.hxz__place{color:var(--hx-muted);margin-left:auto}
@media(max-width:459px){.hxz__place{flex-basis:100%;margin-left:38px}}
@media(min-width:900px){.hxz__inner{gap:56px}.hxz__act{display:flex;align-items:center;justify-content:space-between;gap:32px}.hxz__assure{justify-content:flex-end}}
''',encoding='utf-8')
print('Updated seven stylesheets and six homepage sections; backups preserved.')
