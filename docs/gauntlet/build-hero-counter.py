from pathlib import Path
import subprocess,json,hashlib
r=Path(__file__).resolve().parents[2];p=r.parent/'design-sources/home-2026/gauntlet-supplied-20261018/DSC_5783.jpg';out=r/'wp-content/themes/cos-child/assets/images/home-2026';j={'source':str(p.relative_to(r.parent)),'sha256':hashlib.sha256(p.read_bytes()).hexdigest(),'operations':['Complete original photograph; proportional Lanczos downscale/encoding only; no retouching, background replacement or package alteration'],'outputs':[]}
for w in[640,960,1280,1600,2000]:
 for ext,args in[('avif',['-c:v','libaom-av1','-still-picture','1','-crf','20','-cpu-used','8']),('webp',['-c:v','libwebp','-quality','92']),('jpg',['-q:v','2'])]:
  q=out/f'auth-ritual-counter-{w}.{ext}';subprocess.run(['ffmpeg','-hide_banner','-loglevel','error','-y','-i',str(p),'-vf',f'scale={w}:-1:flags=lanczos,setsar=1','-frames:v','1',*args,str(q)],check=True);j['outputs'].append({'file':q.name,'bytes':q.stat().st_size,'sha256':hashlib.sha256(q.read_bytes()).hexdigest()})
(out/'authentic-hero-counter.origin.json').write_text(json.dumps(j,indent=2)+'\n',encoding='utf8');print('15 original-source exports complete')
