from pathlib import Path
import subprocess,json,hashlib
from PIL import Image
r=Path(__file__).resolve().parents[2];src=r.parent/'design-sources/home-2026/gauntlet-supplied-20261018/product-photo-candidates';out=r/'wp-content/themes/cos-child/assets/images/home-2026'
j={'operations':['Crop only photographic surround to4:5; complete packages retained','Proportional Lanczos scaling and encoding only; no retouching or package edits'],'sources':{},'outputs':[]}
for key,name in [('cleanser','5580.JPG'),('serum','5491.JPG'),('eye','5517a.JPG'),('cream','5438.JPG')]:
 p=src/name;im=Image.open(p);iw,ih=im.size;cw=4*(int(ih*.8)//4);ch=cw*5//4;cx=2*((iw-cw)//4);cy=2*((ih-ch)//4)
 j['sources'][key]={'file':str(p.relative_to(r.parent)),'sha256':hashlib.sha256(p.read_bytes()).hexdigest(),'crop':[cx,cy,cw,ch]}
 for w in [320,480,640,960]:
  for ext,args in [('avif',['-c:v','libaom-av1','-still-picture','1','-crf','20','-cpu-used','8']),('webp',['-c:v','libwebp','-quality','92']),('jpg',['-q:v','2'])]:
   target=out/f'auth-stone-{key}-{w}.{ext}';subprocess.run(['ffmpeg','-hide_banner','-loglevel','error','-y','-i',str(p),'-vf',f'crop={cw}:{ch}:{cx}:{cy},scale={w}:-1:flags=lanczos,setsar=1','-frames:v','1',*args,str(target)],check=True)
   j['outputs'].append({'file':target.name,'sha256':hashlib.sha256(target.read_bytes()).hexdigest(),'bytes':target.stat().st_size})
 print(key,flush=True)
(out/'authentic-stone.origin.json').write_text(json.dumps(j,indent=2)+'\n',encoding='utf8')
