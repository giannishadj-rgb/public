"""Direct, unretouched photographic exports from newly located supplied originals."""
from pathlib import Path
import subprocess, hashlib, json
ROOT=Path(__file__).resolve().parents[2]
SRC=ROOT.parent/'design-sources/home-2026/gauntlet-supplied-20261018/product-photo-candidates'
OUT=ROOT/'wp-content/themes/cos-child/assets/images/home-2026'
manifest={'date':'2026-10-18','operations':['Proportional Lanczos downscale and encoding only; complete original composition retained','No generative editing, relabeling, package recoloring, backdrop replacement or geometric changes'],'sources':{},'outputs':[]}
for base,file,widths in [('auth-ritual-spoon-original','all CaviarOfSwitzerland_spoon 2018.jpg',[320,480,640,960,1200]),('auth-ritual-nocturne','5419.JPG',[540,800,1200,1600,2000])]:
 p=SRC/file;manifest['sources'][base]={'path':str(p.relative_to(ROOT.parent)),'sha256':hashlib.sha256(p.read_bytes()).hexdigest()}
 for w in widths:
  for ext,args in [('avif',['-c:v','libaom-av1','-still-picture','1','-crf','20','-cpu-used','8']),('webp',['-c:v','libwebp','-quality','92']),('jpg',['-q:v','2'])]:
   out=OUT/f'{base}-{w}.{ext}'
   subprocess.run(['ffmpeg','-hide_banner','-loglevel','error','-y','-i',str(p),'-vf',f'scale={w}:-1:flags=lanczos,setsar=1','-frames:v','1',*args,str(out)],check=True)
   manifest['outputs'].append({'file':out.name,'bytes':out.stat().st_size,'sha256':hashlib.sha256(out.read_bytes()).hexdigest()})
 print(base,flush=True)
(OUT/'authentic-campaign.origin.json').write_text(json.dumps(manifest,indent=2)+'\n',encoding='utf8')
