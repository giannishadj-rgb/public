"""7 Nov 2026 (owner): the shop's Complete Ritual photo, blush studio -> the site's cool pearl grey,
full-bleed with the type laid on it. Run from this folder: python build_ritual_pearl.py <images dir>
- recolour: every pixel to its own luminance, tinted to #F1F2F4 (the section's ground); the red
  Swiss squares on the labels keep their colour (soft mask on strongly red pixels)
- wide (desktop): 160px cropped off the left, the right 300px of ground resampled to 688px so the packs sit left and the type
  has open ground on the right (1900x941)
- tall (phones): the packs cropped x 300-1120, full height (820x941)"""
import sys, numpy as np
from PIL import Image
out = sys.argv[1]
src = Image.open('hxshop-ritual-studio-1672.original.jpg').convert('RGB')
a = np.asarray(src).astype(np.float32)
R, G, B = a[..., 0], a[..., 1], a[..., 2]
Y = 0.299 * R + 0.587 * G + 0.114 * B
Y = 255 * (Y / 255) ** 0.92                       # a touch lighter: the blush ground was mid-toned
tint = np.array([241, 242, 244], np.float32) / 242.0
neutral = np.clip(Y[..., None] * tint, 0, 255)
red = np.clip(((R - np.maximum(G, B)) - 50) / 40, 0, 1) * (R > 110)
m = red[..., None]
res = Image.fromarray((neutral * (1 - m) + a * m).round().astype(np.uint8))
W, H = res.size
# 7 Nov 2026 (owner: "shift products a bit to the left"): 160px of ground cropped off the left, the
# right 300px of ground resampled to 688px; the frame stays 1900x941, the packs now span 10-48%
L = 160
right = res.crop((W - 300, 0, W, H)).resize((688, H), Image.LANCZOS)
wide = Image.new('RGB', (W - L - 300 + 688, H)); wide.paste(res.crop((L, 0, W - 300, H)), (0, 0)); wide.paste(right, (W - L - 300, 0))
tall = res.crop((300, 0, 1120, H))
def save(im, base, widths):
    for w in widths:
        x = im if im.width == w else im.resize((w, round(im.height * w / im.width)), Image.LANCZOS)
        x.save(f'{out}/{base}-{w}.jpg', quality=84, optimize=True, progressive=True)
        x.save(f'{out}/{base}-{w}.webp', quality=80, method=6)
        x.save(f'{out}/{base}-{w}.avif', quality=60)
save(wide, 'hxshop-ritual-pearl-wide', [1200, 1900])
save(tall, 'hxshop-ritual-pearl-tall', [820])
print(wide.size, tall.size, 'bottom', np.asarray(tall)[-4:].mean(axis=(0, 1)).round(), 'top', np.asarray(tall)[:4].mean(axis=(0,1)).round())
