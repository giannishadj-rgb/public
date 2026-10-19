from pathlib import Path
p=Path('wp-content/themes/cos-child/woocommerce/archive-product.php')
s=p.read_text(encoding='utf-8')
a=s.index('\t<!-- 2. The Complete Ritual -->')
b=s.index('\t<!-- 3. The four formulas -->')
c=s.index('\t<!-- 4. About the collection')
s=s[:a]+s[b:c].replace('<!-- 3. The four formulas -->','<!-- 2. The four formulas -->')+s[a:b].replace('<!-- 2. The Complete Ritual -->','<!-- 3. The Complete Ritual -->')+s[c:]
s=s.replace(' *   2. The Complete Ritual (#cos-ritual) — real price + saving from WooCommerce.\n *   3. The four formulas (#cos-products) — step, name, one benefit, size, price, add to bag.', ' *   2. The four formulas (#cos-products) — step, name, one benefit, size, price, add to bag.\n *   3. The Complete Ritual (#cos-ritual) — real price + saving from WooCommerce.')
s=s.replace('\t\t\t\t<p class="hx-label hxshop-hero__label cos-shop-hero-eyebrow hx-load-fade" style="--hx-i:0">The Collection</p>\n','')
p.write_text(s,encoding='utf-8')
