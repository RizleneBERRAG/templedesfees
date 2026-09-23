import io, re, os, urllib.request

"""
Le banc d'essai du livre.

Le livre vit au milieu de la page d'accueil, et l'apercu de cette machine
refuse de faire defiler : impossible de le regarder. On le sort donc seul sur
une page, en haut, avec la meme feuille de style et le meme script que le
site. C'est un outil de mise au point, pas une page du site.
"""

base = 'http://localhost/templedesfees/public/'
html = urllib.request.urlopen(base).read().decode('utf-8')

css = re.search(r'href="([^"]*build/assets/app-[^"]+\.css)"', html).group(1)
js = re.search(r'src="([^"]*build/assets/app-[^"]+\.js)"', html).group(1)

debut = html.index('<div class="livre-bloc"')
fin = html.index('</section>', debut)
livre = html[debut:fin]

page = f'''<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Banc d'essai — le livre</title>
<link rel="stylesheet" href="/templedesfees/public/fonts/fonts.css">
<link rel="stylesheet" href="{css}">
<style>
  body{{margin:0;background:var(--nuit);min-height:100vh;
    display:flex;align-items:center;justify-content:center;padding:26px}}
  /* Sur le site, le livre vit dans une .bande qui passe au-dessus du grain.
     Ici il n'y en a pas : on donne au banc le meme etage, sinon le grain
     recouvre le livre et fausse la lecture. */
  .banc{{width:min(1020px,100%);position:relative;z-index:2}}
</style>
</head>
<body>
<div id="grain" aria-hidden="true"></div>
<div class="banc">{livre}</div>
<script type="module" src="{js}"></script>
</body>
</html>
'''

dossier = r'C:\xampp\htdocs\templedesfees\public\apercus-livre'
os.makedirs(dossier, exist_ok=True)
io.open(os.path.join(dossier, 'index.html'), 'w', encoding='utf-8').write(page)
print('banc ecrit :', len(page), 'octets — feuille', css.split('/')[-1])
