# Builds the two hero stages from the owner's lineup photo: a wide desktop stage (wall extended
# up and to the right, for the buy panel) and a tall phone stage (tighter crop, wall extended up).
import sys, numpy as np
from PIL import Image
src, outdir = sys.argv[1], sys.argv[2]
im = np.asarray(Image.open(src).convert('RGB')).astype(np.float32)
H, W, _ = im.shape
rng = np.random.default_rng(7)
SD = 1.4
def smooth(a, n, axis):
    k = np.ones(n) / n
    pad = [(0, 0)] * a.ndim; pad[axis] = (n // 2, n // 2)
    p = np.pad(a, pad, mode='edge')
    return np.apply_along_axis(lambda v: np.convolve(v, k, 'valid'), axis, p)
def extend_right(a, start, add):
    h = a.shape[0]
    edge = smooth(a[:, -40:].mean(1), 41, 0)                        # per-row wall value from the clean far-right columns
    lift = (a[10:200, 760:900].mean((0, 1)) - a[10:200, -40:].mean((0, 1)))   # how much brighter the wall's middle is (clean upper wall only)
    n = a.shape[1] - start + add
    t = np.linspace(0, 1, n)[None, :, None]
    bump = np.sin(np.pi * np.clip(t * 1.15, 0, 1)) * 0.75            # brightens, then falls back to a dark corner
    field = edge[:, None, :] + lift[None, None, :] * bump
    field = field * (1 - 0.03 * t)
    field += rng.normal(0, SD, field.shape)
    out = np.concatenate([a[:, :start], field], 1)
    F = 120
    for i in range(F):                                               # feather the seam
        w = (i / F) ** 1.5
        out[:, start - F + i] = a[:, start - F + i] * (1 - w) + field[:, 0] * w
    return out
def extend_top(a, add):
    band = smooth(a[2:14].mean(0), 61, 0)
    t = np.linspace(1, 0, add)[:, None, None]
    ext = np.repeat(band[None], add, 0) * (1 - 0.03 * t)
    ext += rng.normal(0, SD, ext.shape)
    return np.concatenate([ext, a], 0)
def save(a, name):
    Image.fromarray(a.clip(0, 255).astype(np.uint8)).save(f'{outdir}/{name}.png')
    print(name, a.shape[1], a.shape[0])
wide = extend_top(extend_right(im, 1560, 900), 520)
save(wide, 'stage-wide')
tall = extend_top(im[:, 220:1490], 2400)
save(tall, 'stage-tall')
