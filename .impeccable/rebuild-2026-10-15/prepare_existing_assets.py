from pathlib import Path
from PIL import Image, PngImagePlugin

root = Path(__file__).resolve().parents[2]
source = root / 'wp-content/themes/cos-child/assets/images'
out = root / 'assets/plates'
out.mkdir(parents=True, exist_ok=True)
items = {
    'wordmark': source / 'logo.png',
    'campaign': source / 'home-2026/hxh-tall-720.jpg',
    'collection-object-a': source / 'home-2026/card-cleanser-photo-960.jpg',
    'collection-object-b': source / 'home-2026/card-cream-photo-960.jpg',
    'collection-object-c': source / 'home-2026/card-eye-photo-960.jpg',
}
for name, path in items.items():
    im = Image.open(path)
    meta = PngImagePlugin.PngInfo()
    meta.add_text('Description', 'Pre-existing owner-approved product asset; exact packaging retained. Origin: ' + str(path.relative_to(root)))
    meta.add_text('impeccable_prompt', 'Origin: ' + str(path.relative_to(root)) + '. Lossless format conversion only; no product pixels redesigned.')
    im.save(out / (name + '.png'), pnginfo=meta)
    print(name, im.size)
