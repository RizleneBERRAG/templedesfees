import io, os, re, sys, urllib.request

"""
Le banc d'essai.

L'apercu de la machine de developpement refuse de faire defiler une page :
tout ce qui n'est pas au premier ecran est invisible. Ce script va chercher un
morceau de page sur le site et le sert seul, en haut d'une page vide, avec la
meme feuille de style et le meme script.

    python scripts/banc.py galerie ".mosaique" mosaique
    python scripts/banc.py "" "#le-livre" livre
    python scripts/banc.py contact ".carte-lieu" carte

Le resultat est servi sur /apercus-banc/<nom>/. C'est un outil de mise au
point : rien n'y est destine au visiteur, et le dossier reste hors de
l'export statique.
"""

BASE = 'http://localhost/templedesfees/public/'

chemin = sys.argv[1] if len(sys.argv) > 1 else ''
selecteur = sys.argv[2] if len(sys.argv) > 2 else '.bande'
nom = sys.argv[3] if len(sys.argv) > 3 else 'bloc'

html = urllib.request.urlopen(BASE + chemin).read().decode('utf-8')

css = re.search(r'href="([^"]*build/assets/app-[^"]+\.css)"', html).group(1)
js = re.search(r'src="([^"]*build/assets/app-[^"]+\.js)"', html).group(1)

"""
Le decoupage se fait sur la balise ouvrante puis sur sa fermante, en comptant
les imbrications. Un simple index() sur '</div>' aurait coupe au premier
enfant ferme.
"""
if selecteur.startswith('#'):
    motif = re.compile(r'<(\w+)[^>]*\bid="' + re.escape(selecteur[1:]) + r'"')
elif selecteur.startswith('.'):
    motif = re.compile(r'<(\w+)[^>]*\bclass="[^"]*\b' + re.escape(selecteur[1:]) + r'\b')
else:
    motif = re.compile(r'<(' + re.escape(selecteur) + r')\b')

m = motif.search(html)
if not m:
    raise SystemExit(f'Bloc introuvable : {selecteur} dans /{chemin}')

balise = m.group(1)
debut = m.start()
profondeur = 0
pos = debut

for t in re.finditer(r'<(/?)' + balise + r'\b[^>]*?(/?)>', html[debut:]):
    if t.group(2) == '/':
        continue
    profondeur += -1 if t.group(1) else 1
    if profondeur == 0:
        pos = debut + t.end()
        break

bloc = html[debut:pos]

page = f'''<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Banc — {nom}</title>
<link rel="stylesheet" href="/templedesfees/public/fonts/fonts.css">
<link rel="stylesheet" href="{css}">
<style>
  body{{margin:0;background:var(--nuit);min-height:100vh;padding:30px 0}}
  /* Le meme etage qu'une .bande du site : sans lui, le grain recouvre le
     bloc et fausse la lecture des couleurs. */
  .banc{{position:relative;z-index:2}}
</style>
</head>
<body>
<div id="grain" aria-hidden="true"></div>
<div class="banc">{bloc}</div>
<script type="module" src="{js}"></script>
</body>
</html>
'''

dossier = rf'C:\xampp\htdocs\templedesfees\public\apercus-banc\{nom}'
os.makedirs(dossier, exist_ok=True)
io.open(os.path.join(dossier, 'index.html'), 'w', encoding='utf-8').write(page)

print(f'  /apercus-banc/{nom}/  —  {len(bloc)} octets pris dans /{chemin}  ({css.split("/")[-1]})')
