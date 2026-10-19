from pathlib import Path
import json
from PIL import Image
im=Image.open('../design-sources/home-2026/gauntlet-supplied-20261018/DSC_5783.jpg').convert('RGB')
def lum(rgb):
 vals=[v/255 for v in rgb];return sum((v/12.92 if v<=.04045 else ((v+.055)/1.055)**2.4)*k for v,k in zip(vals,[.2126,.7152,.0722]))
out=[]
for c in json.loads(Path('docs/gauntlet/hero-contrast-geometry.json').read_text()):
 p=c['photo'];s=max(p['w']/im.width,p['h']/im.height);ox=(im.width*s-p['w'])*(0 if c['width']<360 else .5);oy=(im.height*s-p['h'])*.8
 for t in c['text']:
  r=t['rect']
  if r['y']+r['h']>p['y']+p['h']:continue
  box=(max(0,int((r['x']-p['x']+ox)/s)),max(0,int((r['y']-p['y']+oy)/s)),min(im.width,int((r['x']+r['w']-p['x']+ox)/s)),min(im.height,int((r['y']+r['h']-p['y']+oy)/s)));maxima=[v[1] for v in im.crop(box).getextrema()];fg=lum([int(v.strip()) for v in t['color'][4:-1].split(',')]);ratio=(fg+.05)/(lum(maxima)+.05);out.append({'width':c['width'],'selector':t['selector'],'text':t.get('text'),'conservativeContrast':round(ratio,2)});print(out[-1])
Path('docs/gauntlet/hero-photo-contrast-results.json').write_text(json.dumps({'method':'Conservative RGB channel maxima across actual text-node line rectangles mapped to original photo; no image editing','results':out},indent=2))
