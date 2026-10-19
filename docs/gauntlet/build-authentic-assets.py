"""Homepage-only source-direct photographic exports. Never redraw product pixels."""
from pathlib import Path
import subprocess, hashlib, json, math
ROOT=Path(__file__).resolve().parents[2]
SRC=ROOT.parent/'design-sources/home-2026'
OUT=ROOT/'wp-content/themes/cos-child/assets/images/home-2026'
manifest={'date':'2026-10-18','scope':'Homepage source-direct replacements after failed flat-master fidelity audit','operations':['Crop surrounding photographic margin only; all package pixels retained','Proportional Lanczos downscale from original JPG/PPT media, pad on white for common stage baseline','AVIF/WebP/JPEG encoding only; no generative editing, relabeling, mirroring, perspective change, backdrop normalization or package recoloring'],'sources':{},'outputs':[]}
def run(source,base,w,vf):
 for ext,args in [('avif',['-c:v','libaom-av1','-still-picture','1','-crf','20','-cpu-used','8']),('webp',['-c:v','libwebp','-quality','92']),('jpg',['-q:v','2'])]:
  out=OUT/f'{base}-{w}.{ext}'
  subprocess.run(['ffmpeg','-hide_banner','-loglevel','error','-y','-i',str(source),'-vf',vf,'-frames:v','1',*args,str(out)],check=True)
  manifest['outputs'].append({'file':out.name,'sha256':hashlib.sha256(out.read_bytes()).hexdigest(),'bytes':out.stat().st_size})
def source_info(p):return {'path':str(p.relative_to(ROOT.parent)), 'sha256':hashlib.sha256(p.read_bytes()).hexdigest()}
# x bounds include the complete pack and original reflection; ytop/foot are source-photo coordinates.
# Heights derive from the relative front-on arrangement in authentic PPTimage54.
specs={'cleanser':(1137,2380,1136,5750,932),'serum':(128,1380,202,4230,782),'eye':(23,1076,141,3708,690),'cream':(210,2455,328,2150,354)}
for key,(left,right,top,foot,packh) in specs.items():
 p=SRC/'packshots-2026-10-12'/f'{key}.jpg'
 manifest['sources'][key]={**source_info(p),'top_foot':[top,foot],'relative_pack_height':packh}
 scale=packh/(foot-top)
 cy=max(0,2*math.floor((top-20/scale)/2));bottom=2*math.ceil((foot+65/scale)/2)
 # Crop stays inside authentic input; reflection beyond file edge is simply absent.
 from PIL import Image
 original=Image.open(p);bottom=min(bottom,original.height)
 left=2*(left//2);cw=2*((right-left)//2);ch=2*((bottom-cy)//2)
 for w in [320,480,640,960]:
  factor=w/640;sh=2*round(ch*scale*factor/2);actual=sh/ch
  canvas_h=round(1100*factor);pad_y=round(1020*factor-(foot-cy)*actual)
  assert pad_y>=0 and pad_y+sh<=canvas_h and actual<1,(key,w,pad_y,sh,canvas_h)
  vf=f'crop={cw}:{ch}:{left}:{cy},scale=-2:{sh}:flags=lanczos,pad={w}:{canvas_h}:(ow-iw)/2:{pad_y}:color=white,setsar=1'
  run(p,'auth-pack-'+key,w,vf)
 print('Exported authentic',key,flush=True)
for base,file,widths in [('auth-ritual-spoon','brand-ppt-image54.png',[320,480,640,817]),('auth-care-on-ice','brand-ppt-image5.jpeg',[540,800,1200,1600,1853])]:
 p=SRC/'gauntlet-supplied-20261018'/file
 manifest['sources'][base]=source_info(p)
 for w in widths:run(p,base,w,f'scale={w}:-1:flags=lanczos,setsar=1')
 print('Exported authentic',base,flush=True)
(OUT/'authentic-originals.origin.json').write_text(json.dumps(manifest,indent=2)+'\n',encoding='utf-8')
print('Source-direct exports complete:',len(manifest['outputs']),flush=True)
