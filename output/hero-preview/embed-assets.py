from pathlib import Path
import base64

root = Path(__file__).resolve().parents[2]
directory = Path(__file__).resolve().parent
theme = root / 'wp-content/themes/cos-child/assets'
assets = {
    '__COS_SERIF__': ('font/woff2', theme / 'fonts/cormorant-garamond-var-latin.woff2'),
    '__COS_SANS__': ('font/woff2', theme / 'fonts/inter-var-latin.woff2'),
    '__COS_LOGO__': ('image/svg+xml', theme / 'images/logo-master.svg'),
    '__COS_DESKTOP__': ('image/webp', theme / 'images/home-2026/wc-hero-wide-1440.webp'),
    '__COS_MOBILE__': ('image/webp', theme / 'images/home-2026/wc-hero-tall-640.webp'),
}
target = directory / 'caviar-hero-preview.html'
markup = target.read_text(encoding='utf-8')
for token, (mime, path) in assets.items():
    markup = markup.replace(token, f'data:{mime};base64,' + base64.b64encode(path.read_bytes()).decode('ascii'))
target.write_text(markup, encoding='utf-8')
assert target.stat().st_size < 1_000_000
assert '__COS_' not in markup
assert '\\"' not in markup
print(f'Embedded preview: {target.stat().st_size:,} bytes')
