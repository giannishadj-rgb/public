"""Read-only source verification. No site images are written or regenerated."""
from pathlib import Path
import ast, hashlib, io, json, sys
import numpy as np
from PIL import Image, ImageFilter
ROOT=Path.cwd()
HERE=ROOT.parent/'design-sources/home-2026'
ASSET=ROOT/'wp-content/themes/cos-child/assets/images/home-2026'
OUT=Path(__file__).parent
data={'tests':[],'sources':[]}
def sha(p): return hashlib.sha256(Path(p).read_bytes()).hexdigest()
def metric(label,expected,actual,box=None):
    expected=expected.convert('RGBA' if actual.mode=='RGBA' else 'RGB')
    actual=actual.convert(expected.mode)
    if box: expected=expected.crop(box); actual=actual.crop(box)
    a=np.asarray(expected).astype(float);b=np.asarray(actual).astype(float)
    v={'test':label,'size':expected.size,'MAE_8bit':float(abs(a-b).mean()),'max_8bit':float(abs(a-b).max()),'exact_pixel_fraction':float(np.all(a==b,axis=2).mean())}
    data['tests'].append(v);return v
def compress(im,format,**kw):
    buf=io.BytesIO();im.save(buf,format=format,**kw);buf.seek(0);return Image.open(buf).copy(),buf.getvalue()
def file_info(p):
    p=Path(p);im=Image.open(p)
    data['sources'].append({'file':str(p),'sha256':sha(p),'bytes':p.stat().st_size,'size':im.size,'mode':im.mode})
    return im
hero=file_info(HERE/'hero-jar_master.png').convert('RGB')
# Verify shipping derivatives against their saved full-size canvas and supplied source.
for base,offset in [('hxh-wide',(700,120)),('hxh-tall',(-590,330))]:
    work=file_info(HERE/f'rebuild-2026-10-14/work/hero/{base}_master.png').convert('RGB')
    master_box=(640,330,1260,830)
    product=hero.crop(master_box)
    x,y=offset
    shipbox=(x+640,y+330,x+1260,y+830)
    metric(f'{base} work protected product versus owner master',product,work.crop(shipbox))
    widths=[1440,1920,2560] if base=='hxh-wide' else [540,720]
    for w in widths:
        expected=work if work.width==w else work.resize((w,round(work.height*w/work.width)),Image.Resampling.LANCZOS)
        jpg=file_info(ASSET/f'{base}-{w}.jpg')
        enc,raw=compress(expected,'JPEG',quality=86,optimize=True,progressive=True)
        m=metric(f'{base}-{w}.jpg versus documented export',enc,jpg)
        m['byte_identical_reexport']=raw==Path(ASSET/f'{base}-{w}.jpg').read_bytes()
        avif=file_info(ASSET/f'{base}-{w}.avif')
        metric(f'{base}-{w}.avif versus expected uncompressed pixels',expected,avif)
# Already-present pure campaign export, independently checked against exact supplied source.
for w in [1280,1672]:
    p=ASSET/f'hxh-campaign-{w}.jpg'
    if p.exists():
        expected=hero.resize((w,round(hero.height*w/hero.width)),Image.Resampling.LANCZOS)
        metric(f'hxh-campaign-{w}.jpg versus original proportional resize',expected,file_info(p))
# Collection: invoke only the pure master builder, never its file-writing main branch.
code=(HERE/'build_collection_objects.py').read_text(encoding='utf-8')
ns={'__name__':'provenance_read_only','__file__':str(HERE/'build_collection_objects.py')}
exec(compile(code,str(HERE/'build_collection_objects.py'),'exec'),ns)
for name in ['cleanser','serum','eye','cream']:
    file_info(HERE/f'packshots-2026-10-12/{name}-flat-2026-10-13.png')
    master=ns['build_master'](name)
    for w in [480,720,960]:
        rgba=master.resize((w,round(1.5*w)),Image.Resampling.LANCZOS)
        flat=Image.new('RGB',rgba.size,ns['JPG_GROUND']);flat.paste(rgba,mask=rgba.split()[3])
        expected,raw=compress(flat,'JPEG',quality=86,optimize=True,progressive=True)
        shipped=file_info(ASSET/f'collection-object-{name}-{w}.jpg')
        m=metric(f'collection-object-{name}-{w}.jpg versus source builder',expected,shipped)
        m['byte_identical_reexport']=raw==Path(ASSET/f'collection-object-{name}-{w}.jpg').read_bytes()
        file_info(ASSET/f'collection-object-{name}-{w}.avif')
# Lab trace: exact source/export linkage; original source builder explicitly redraws logo.
lab=file_info(HERE/'philosophy_graded.png').convert('RGB')
flat_lab=file_info(HERE/'lab-filling_flat-logo.png').convert('RGB')
sys.path.insert(0,str(HERE))
from grade import grade
metric('lab-filling_flat-logo plus house grade versus philosophy_graded',grade(flat_lab),lab)
for w in [640,1122]:
    expected=lab.resize((w,round(lab.height*w/lab.width)),Image.Resampling.LANCZOS)
    for ext in ['jpg','avif']:
        metric(f'philosophy-lab-{w}.{ext} versus philosophy_graded',expected,file_info(ASSET/f'philosophy-lab-{w}.{ext}'))
# NON-PRODUCT liquid alternative: run documented arithmetic in-memory only.
src=file_info(ROOT/'wp-content/uploads/2026/05/hyaluronic-acid.jpg').convert('RGB')
g=np.asarray(src.convert('L')).astype(np.float32)
lo,hi=np.percentile(g,.5),np.percentile(g,99.7)
t=np.clip((g-lo)/(hi-lo),0,1);bg=np.median(t)
t=np.clip(.88+(t-bg)*2.4,0,1);t=t*t*(3-2*t)*.55+t*.45
arr=np.array([44,44,34],np.float32)+(np.array([247,245,238],np.float32)-np.array([44,44,34],np.float32))*t[...,None]
im=Image.fromarray(np.clip(arr,0,255).astype(np.uint8))
for w in [960,1440,2400]:
    expected=im if im.width==w else im.resize((w,round(im.height*w/im.width)),Image.Resampling.LANCZOS)
    jpg=file_info(ASSET/f'caviar-macro-light-{w}.jpg')
    enc,raw=compress(expected,'JPEG',quality=86,optimize=True,progressive=True)
    m=metric(f'caviar-macro-light-{w}.jpg versus hyaluronic source builder',enc,jpg)
    m['byte_identical_reexport']=raw==Path(ASSET/f'caviar-macro-light-{w}.jpg').read_bytes()
    file_info(ASSET/f'caviar-macro-light-{w}.avif')
for f in ['build_hxh_hero.py','build_collection_objects.py','fix_lab.py','build_caviar_macro_light.py']:
    p=HERE/f;data['sources'].append({'file':str(p),'sha256':sha(p),'bytes':p.stat().st_size})
(OUT/'asset-provenance-data.json').write_text(json.dumps(data,indent=2),encoding='utf-8')
print(json.dumps(data['tests'],indent=2))
