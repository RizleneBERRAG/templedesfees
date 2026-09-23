import io, random

"""
Le coup de pinceau.

Un aplat d'or ne ressemble a rien : ce qui fait la peinture, c'est la trace
seche — les stries que laissent les poils quand la brosse se vide, et les
bords qui s'effilochent. Les stries sont donc tirees ici une a une, avec une
graine fixe pour que le dessin soit toujours le meme d'un rendu a l'autre.
"""

random.seed(7)

W, H = 620, 150

# Le corps de la trace : bords irreguliers, extremites effilees.
corps = (
    "M26,92 "
    "C58,58 118,47 188,49 "
    "C262,51 338,38 424,42 "
    "C486,45 538,53 576,66 "
    "C551,75 512,72 476,77 "
    "C534,82 566,90 590,101 "
    "C548,112 472,102 400,106 "
    "C296,112 188,123 98,114 "
    "C58,110 31,103 26,92 Z"
)

# Quelques poils qui debordent a droite, la ou la brosse quitte le papier.
poils = []
for i in range(9):
    y = random.uniform(48, 104)
    x0 = random.uniform(540, 574)
    lon = random.uniform(16, 46)
    ep = random.uniform(0.8, 2.6)
    pente = random.uniform(-6, 6)
    poils.append(
        '<path d="M%.1f,%.1f C%.1f,%.1f %.1f,%.1f %.1f,%.1f" '
        'stroke="url(#pinceau-or)" stroke-width="%.1f" stroke-linecap="round" fill="none" '
        'opacity="%.2f"/>'
        % (x0, y, x0 + lon * .4, y + pente * .4, x0 + lon * .7, y + pente * .8,
           x0 + lon, y + pente, ep, random.uniform(.35, .8))
    )

# Le masque : le blanc garde, le noir creuse. Les stries sont noires, plus
# denses vers la fin de la trace, la ou la brosse a le moins de matiere.
stries = []
for i in range(46):
    y = random.uniform(44, 112)
    t = (y - 44) / 68
    x0 = random.uniform(24, 300)
    x1 = random.uniform(max(x0 + 60, 340), 600)
    ep = random.uniform(0.7, 3.4)
    op = random.uniform(.18, .72) * (0.6 + 0.8 * abs(t - .5) * 2)
    courbe = random.uniform(-7, 7)
    stries.append(
        '<path d="M%.1f,%.1f Q%.1f,%.1f %.1f,%.1f" stroke="#000" stroke-width="%.1f" '
        'stroke-linecap="round" fill="none" opacity="%.2f"/>'
        % (x0, y, (x0 + x1) / 2, y + courbe, x1, y + courbe * .3, ep, min(op, .85))
    )

# Quelques manques francs, comme un papier qui n'a pas pris.
for i in range(7):
    cx = random.uniform(120, 560)
    cy = random.uniform(52, 104)
    rx = random.uniform(8, 34)
    ry = random.uniform(2, 7)
    stries.append(
        '<ellipse cx="%.1f" cy="%.1f" rx="%.1f" ry="%.1f" fill="#000" opacity="%.2f"/>'
        % (cx, cy, rx, ry, random.uniform(.25, .6))
    )

svg = f'''@props(['classe' => null])

{{{{--
    Le coup de pinceau.

    Un aplat d'or ne ressemble a rien : ce qui fait la peinture, c'est la
    trace seche — les stries que laissent les poils quand la brosse se vide,
    et les bords qui s'effilochent. Elles sont tracees une a une dans
    scripts/, puis figees ici : le dessin ne bouge plus d'un rendu a l'autre.

    L'or est celui de la charte, --dorure, repris en degrade SVG. Le composant
    ne se positionne pas lui-meme : il remplit son parent, qui decide de sa
    place et de sa taille.
--}}}}
<svg class="pinceau {{{{ $classe }}}}" viewBox="0 0 {W} {H}" preserveAspectRatio="none"
     aria-hidden="true" focusable="false">
  <defs>
    <linearGradient id="pinceau-or" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#FBF3DD"/>
      <stop offset="44%" stop-color="#E5C88C"/>
      <stop offset="100%" stop-color="#B48F48"/>
    </linearGradient>

    <mask id="pinceau-sec" maskUnits="userSpaceOnUse" x="0" y="0" width="{W}" height="{H}">
      <rect width="{W}" height="{H}" fill="#fff"/>
      {chr(10).join('      ' + x for x in stries)}
    </mask>
  </defs>

  <g mask="url(#pinceau-sec)">
    <path d="{corps}" fill="url(#pinceau-or)"/>
    {chr(10).join('    ' + x for x in poils)}
  </g>
</svg>
'''

io.open(r'C:\xampp\htdocs\templedesfees\resources\views\components\pinceau.blade.php',
        'w', encoding='utf-8').write(svg)
print('composant pinceau ecrit :', len(svg), 'octets,', len(stries), 'stries,', len(poils), 'poils')
