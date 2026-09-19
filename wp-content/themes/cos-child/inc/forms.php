<?php
/**
 * COS Child Theme — Forms
 *
 * The footer newsletter signup (Klaviyo). Split into two functions —
 * markup and submit-handler script — because inc/navigation.php's
 * cos_child_global_footer() prints them at two different points in the
 * footer (form inline in .cos-nl-wrap, script after the footer element),
 * and preserving that exact output order matters more than having a
 * single call site.
 */

function cos_child_newsletter_form(): void { ?>
<form class="cos-nl-form" method="post" aria-label="Newsletter sign-up">
      <div class="cos-nl-row">
        <label for="cos-nl-email" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0,0,0,0)">Email address</label>
        <input id="cos-nl-email" class="cos-nl-input" type="email" name="email" placeholder="Email for Caviar of Switzerland updates" autocomplete="email" required/>
        <button type="submit" class="cos-nl-btn">Join the Caviar Circle</button>
      </div>
      <label class="cos-nl-consent">
        <input type="checkbox" name="gdpr" required/>
        I agree to receive communications from Caviar of Switzerland. <a href="/privacy/" style="color:rgba(248,247,244,.6);text-decoration:underline">Privacy Policy</a>.
      </label>
      <noscript><p style="font-size:10px;color:rgba(248,247,244,.6);margin-top:8px">JavaScript is required to join the Caviar Circle here. Please email us instead — see the Contact page.</p></noscript>
    </form>
<?php }

function cos_child_newsletter_form_script(): void { ?>
<script nonce="<?php echo esc_attr( cos_csp_nonce() ); ?>">
(function(){
  var form=document.querySelector('.cos-nl-form');
  if(!form)return;
  var btn=form.querySelector('.cos-nl-btn');
  var input=form.querySelector('.cos-nl-input');
  var originalText=btn.textContent;
  function showError(msg){
    var err=form.querySelector('.cos-nl-err');
    if(!err){err=document.createElement('p');err.className='cos-nl-err';err.style.cssText='font-size:10px;color:#c47a7a;margin-top:4px';form.appendChild(err);}
    err.textContent=msg;
  }
  function resetBtn(){btn.textContent=originalText;btn.disabled=false;}
  form.addEventListener('submit',function(e){
    e.preventDefault();
    var email=input.value.trim();
    if(!email)return;
    btn.textContent='Subscribing…';
    btn.disabled=true;
    fetch('https://a.klaviyo.com/client/subscriptions/?company_id=Rd6DHd',{
      method:'POST',
      headers:{'content-type':'application/json','revision':'2024-02-15'},
      body:JSON.stringify({data:{type:'subscription',attributes:{list_id:'XuVPEG',email:email}}})
    }).then(function(res){
      if(res.ok||res.status===202){
        form.innerHTML='<p style="font-size:13px;font-weight:300;color:rgba(248,247,244,.8);line-height:1.65">Thank you — welcome to the world of Caviar of Switzerland.</p>';
      }else{
        resetBtn();showError('Something went wrong. Please try again.');
      }
    }).catch(function(){resetBtn();showError('Something went wrong. Please try again.');});
  });
})();
</script>
<?php }
