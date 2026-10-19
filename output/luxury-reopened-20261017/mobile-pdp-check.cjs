const {webkit,chromium,devices}=require('playwright');
const AxeBuilder=require('@axe-core/playwright').default;
const fs=require('fs'),path=require('path');
const out=path.join(__dirname,'mobile-artifacts');
const routes={cream:'24h-regeneration-cream',serum:'advanced-repair-serum',eye:'revitalizing-eye-cream',cleanser:'micellar-water-cleanser',set:'the-complete-ritual'};
async function enlarge(p){await p.evaluate(()=>{const all=[...document.querySelectorAll('body,body *')].map(e=>({e,f:parseFloat(getComputedStyle(e).fontSize),l:parseFloat(getComputedStyle(e).lineHeight)}));for(const{e,f,l}of all){e.style.setProperty('font-size',f*2+'px','important');if(Number.isFinite(l))e.style.setProperty('line-height',l*2+'px','important')}})}
async function evidence(p){return p.evaluate(()=>{let q=s=>{let e=document.querySelector(s),r=e?.getBoundingClientRect();return e?{text:e.innerText,x:r.x,y:r.y+scrollY,w:r.width,h:r.height,display:getComputedStyle(e).display}:null};return {width:document.documentElement.scrollWidth,viewport:innerWidth,title:q('.hxpd-name'),promise:q('.hxpd-promise'),price:q('.hxpd-price'),form:q('.hxpd-form'),bar:q('.hxpd-bar'),qty:q('.hxpd-qty'),button:q('#cos-pdp-atc-btn'),main:document.querySelector('main').getBoundingClientRect().toJSON()}})}
(async()=>{let results={};const b=await webkit.launch();
for(const width of [390,320])for(const[name,slug]of Object.entries(routes)){
 const context=await b.newContext({...devices['iPhone 13'],viewport:{width,height:664}}),p=await context.newPage();
 await p.goto('http://caviarofswitzerland.local/products/'+slug+'/');await p.evaluate(()=>document.fonts.ready);await p.waitForTimeout(700);
 results[`${width}-${name}`]=await evidence(p);
 if(name==='cream'||name==='set')await p.screenshot({path:path.join(out,`verified-${width}-${name}-top.png`),scale:'css'});
 if(name==='cream'){
  results[`${width}-axe`]=(await new AxeBuilder({page:p}).withTags(['wcag2a','wcag2aa','wcag21aa']).analyze()).violations.map(v=>({id:v.id,impact:v.impact,nodes:v.nodes.map(n=>n.target)}));
  await p.locator('[aria-label="Increase quantity"]').click();if(await p.locator('#cos-qty').inputValue()!=='2')throw Error('quantity increase failed');await p.locator('[aria-label="Decrease quantity"]').click();
 }
 await enlarge(p);results[`text200-${width}-${name}`]=await evidence(p);
 if(name==='cream'||name==='set'){
  await p.screenshot({path:path.join(out,`verified-text200-${width}-${name}-top.png`),scale:'css'});
  await p.locator('.hxpd-form').scrollIntoViewIfNeeded();await p.waitForTimeout(500);
  await p.screenshot({path:path.join(out,`verified-text200-${width}-${name}-form.png`),scale:'css'});
  results[`text200-${width}-${name}-form`]=await evidence(p);
 }
 if(name==='cream'&&width===320){
  await p.locator('#cos-pdp-atc-btn').click();await p.locator('[data-hxpd-confirm]').getByText('View bag').waitFor();results['text200-normal-add']='passed';
  await p.keyboard.press('Escape');await p.evaluate(()=>scrollTo(0,document.documentElement.scrollHeight));await p.waitForTimeout(700);await p.locator('[data-hxpd-bar-btn]').click();await p.waitForTimeout(700);results['text200-sticky-add']=await p.locator('[data-hxpd-bar-btn]').innerText();await p.keyboard.press('Escape');
  await p.screenshot({path:path.join(out,'verified-text200-320-cream-footer-bar.png'),scale:'css'});
 }
 await context.close();
}
await b.close();const d=await chromium.launch();for(const width of [1440,720]){let p=await d.newPage({viewport:{width,height:950}});await p.goto('http://caviarofswitzerland.local/products/24h-regeneration-cream/');await p.waitForTimeout(700);results[`desktop-${width}`]=await evidence(p);await p.screenshot({path:path.join(out,`verified-desktop-${width}-cream.png`)});await p.close()}await d.close();
fs.writeFileSync(path.join(out,'pdp-verification.json'),JSON.stringify(results,null,2));console.log(JSON.stringify(results,null,2));
})();
