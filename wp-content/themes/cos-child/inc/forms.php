<?php
/**
 * COS Child Theme — Forms
 *
 * The footer newsletter signup, "The Caviar Circle" (Klaviyo). Split into two
 * functions — markup and submit-handler script — because inc/navigation.php's
 * cos_child_global_footer() prints them at two different points in the
 * footer (form inline in .hxf-nl, script after the footer element).
 *
 * Styles: assets/css/hx/chrome.css (.hxf-form*, .hxf-consent, .hxf-status).
 * The legacy .cos-nl-* class names stay on the elements: the script below and
 * the test suite select by them.
 */

function cos_child_newsletter_form(): void { ?>
<form class="hxf-form cos-nl-form" method="post" aria-label="Join the Caviar Circle" novalidate>
      <div class="hxf-form__row cos-nl-row">
        <label for="cos-nl-email" class="hx-sr">Email address</label>
        <input id="cos-nl-email" class="hxf-form__input cos-nl-input" type="email" name="email" placeholder="Email address" autocomplete="email" inputmode="email" spellcheck="false" aria-describedby="cos-nl-status" required/>
        <button type="submit" class="hxf-form__btn cos-nl-btn">Join<span class="hx-sr"> the Caviar Circle</span><span class="hx-arrow" aria-hidden="true"></span></button>
      </div>
      <label class="hxf-consent cos-nl-consent">
        <input type="checkbox" name="gdpr" required/>
        <span class="cos-nl-consent-text">I agree to receive communications from Caviar of Switzerland. <a href="/privacy/">Privacy Policy</a>.</span>
      </label>
      <?php /* Persistent live region (critique 11 Oct 2026, P1): pending, success and
         error are announced here; the form fields are hidden, never destroyed. */ ?>
      <p id="cos-nl-status" class="hxf-status cos-nl-status" role="status" aria-live="polite" tabindex="-1"></p>
      <noscript><p class="hxf-noscript">JavaScript is required to join the Caviar Circle here. Please email us instead — see the Contact page.</p></noscript>
    </form>
<?php }

function cos_child_newsletter_form_script(): void { ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  var form=document.querySelector('.cos-nl-form');
  if(!form)return;
  var btn=form.querySelector('.cos-nl-btn');
  var input=form.querySelector('.cos-nl-input');
  var consent=form.querySelector('input[name="gdpr"]');
  var status=form.querySelector('.cos-nl-status');
  var originalHTML=btn.innerHTML;
  function say(html,tone){
    if(tone) status.setAttribute('data-tone',tone); else status.removeAttribute('data-tone');
    status.innerHTML=html;
  }
  function resetBtn(){btn.innerHTML=originalHTML;btn.disabled=false;}
  function fail(){
    // Keep the email, give the field back, and offer a second route.
    resetBtn();
    say('We couldn’t add you just now. Please try again, or <a href="/contact/">contact us</a> and we’ll add you.','error');
    input.focus();
  }
  form.addEventListener('submit',function(e){
    e.preventDefault();
    var email=input.value.trim();
    // novalidate: the browser bubbles are replaced by messages in the live region.
    if(!email||!input.checkValidity()){say('Please enter a valid email address.','error');input.focus();return;}
    if(consent&&!consent.checked){say('Please tick the box to agree to receive our emails.','error');consent.focus();return;}
    btn.textContent='Joining…';
    btn.disabled=true;
    say('Subscribing…');
    fetch('https://a.klaviyo.com/client/subscriptions/?company_id=Rd6DHd',{
      method:'POST',
      headers:{'content-type':'application/json','revision':'2024-02-15'},
      body:JSON.stringify({data:{type:'subscription',attributes:{list_id:'XuVPEG',email:email}}})
    }).then(function(res){
      if(res.ok||res.status===202){
        // Hide the fields (not innerHTML-replace) so the live region survives,
        // then move focus to the confirmation the submit button used to hold.
        Array.prototype.forEach.call(form.querySelectorAll('.cos-nl-row,.cos-nl-consent'),function(el){el.style.display='none';});
        say('Thank you, you’re on the list. Welcome to the Caviar Circle.');
        status.focus();
      }else{fail();}
    }).catch(fail);
  });
})();
</script>
<?php }
