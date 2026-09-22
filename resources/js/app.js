/* Temple des Fées — interactions du site public.
   Sans dépendance : menu, bandeau, apparition au défilement, vue plein
   écran, visionneuse de fiche, transitions de page. */

const reduit = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- menu ---------- */
const cle = document.getElementById('cle');
const menu = document.getElementById('menu');

const accorderMenu = () => {
    if (!cle || !menu) return;
    if (window.innerWidth > 1120) {
        menu.hidden = false;
        cle.setAttribute('aria-expanded', 'false');
    } else if (cle.getAttribute('aria-expanded') !== 'true') {
        menu.hidden = true;
    }
};

cle?.addEventListener('click', () => {
    const ouvert = cle.getAttribute('aria-expanded') === 'true';
    cle.setAttribute('aria-expanded', String(!ouvert));
    menu.hidden = ouvert;
});
window.addEventListener('resize', accorderMenu);
accorderMenu();

/* ---------- bandeau ---------- */
const bandeau = document.getElementById('bandeau');
window.addEventListener(
    'scroll',
    () => bandeau?.classList.toggle('accroche', window.scrollY > 12),
    { passive: true },
);

/* ---------- apparition au défilement ----------

   Chaque chapitre monte en entrant dans le champ. Le filet de sécurité en
   bas du bloc découvre ce qui resterait masqué si l'observateur ne se
   déclenchait pas — une page qui n'affiche rien est pire qu'une page sans
   animation. */

if (!reduit()) {
    const io = new IntersectionObserver(
        (entrees) => {
            entrees.forEach((e) => {
                if (!e.isIntersecting) return;
                e.target.classList.add('vu');
                io.unobserve(e.target);
            });
        },
        { rootMargin: '0px 0px -7% 0px' },
    );

    document.querySelectorAll('.monte').forEach((el, i) => {
        el.style.transitionDelay = `${Math.min(i, 5) * 90}ms`;
        if (el.getBoundingClientRect().top > window.innerHeight * 1.02) io.observe(el);
        else el.classList.add('vu');
    });

    setTimeout(
        () =>
            document.querySelectorAll('.monte:not(.vu)').forEach((el) => {
                if (el.getBoundingClientRect().top < window.innerHeight * 1.2) el.classList.add('vu');
            }),
        2200,
    );
} else {
    document.querySelectorAll('.monte').forEach((el) => el.classList.add('vu'));
}

/* ---------- vue plein écran ----------

   Un seul mécanisme pour deux usages : la mosaïque de la galerie, où chaque
   vignette est son propre déclencheur, et la visionneuse des fiches, où
   c'est la grande photo qui agrandit la vue courante. Dans les deux cas la
   liste est lue sur le conteneur marqué data-lightbox. */

let lb = null;
let lbListe = [];
let lbIndex = 0;

const peindre = () => {
    const f = lbListe[lbIndex];
    const img = lb.querySelector('img');
    img.src = f.dataset.full;
    img.alt = f.querySelector('img')?.alt ?? '';
    lb.querySelector('.cap').textContent =
        `${f.dataset.legende ?? ''}  ·  ${lbIndex + 1} / ${lbListe.length}`;
};

const deplacer = (d) => {
    lbIndex = (lbIndex + d + lbListe.length) % lbListe.length;
    peindre();
};

const fermer = () => {
    if (!lb) return;
    lb.hidden = true;
    document.body.style.overflow = '';
};

const ouvrir = (items, index) => {
    if (!items.length) return;
    lbListe = items;
    lbIndex = Math.max(0, index);

    if (!lb) {
        lb = document.createElement('div');
        lb.id = 'lb';
        lb.innerHTML =
            '<button class="x" type="button">Fermer</button><img alt=""><p class="cap"></p>' +
            '<div class="nav"><button type="button" data-d="-1">← Précédente</button>' +
            '<button type="button" data-d="1">Suivante →</button></div>';
        document.body.appendChild(lb);
        lb.addEventListener('click', (ev) => {
            if (ev.target === lb || ev.target.classList.contains('x')) return fermer();
            const b = ev.target.closest('[data-d]');
            if (b) deplacer(Number(b.dataset.d));
        });
        document.addEventListener('keydown', (ev) => {
            if (!lb || lb.hidden) return;
            if (ev.key === 'Escape') fermer();
            if (ev.key === 'ArrowLeft') deplacer(-1);
            if (ev.key === 'ArrowRight') deplacer(1);
        });
    }

    lb.hidden = false;
    document.body.style.overflow = 'hidden';
    peindre();
};

const listeDe = (conteneur) => [...conteneur.querySelectorAll('[data-full]')];

document.addEventListener('click', (e) => {
    const vignette = e.target.closest('[data-lightbox] figure[data-full]');
    if (vignette) {
        const items = listeDe(vignette.closest('[data-lightbox]'));
        return ouvrir(items, items.indexOf(vignette));
    }
    const scene = e.target.closest('[data-zoom]');
    if (scene) {
        const conteneur = scene.closest('[data-lightbox]');
        if (!conteneur) return;
        const items = listeDe(conteneur);
        return ouvrir(items, items.findIndex((v) => v.getAttribute('aria-current') === 'true'));
    }
});

// La scène est annoncée comme un bouton : elle doit répondre au clavier.
document.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter' && e.key !== ' ') return;
    const scene = e.target.closest?.('[data-zoom]');
    if (!scene) return;
    e.preventDefault();
    scene.click();
});

/* ---------- visionneuse de fiche ---------- */

document.querySelectorAll('.viseur').forEach((viseur) => {
    const vues = [...viseur.querySelectorAll('.scene img')];
    const vignettes = [...viseur.querySelectorAll('.vignette')];
    const compteur = viseur.querySelector('.compteur b');
    let courant = 0;

    const montrer = (i, prendreLeFocus = false) => {
        courant = (i + vues.length) % vues.length;
        vues.forEach((img, n) => img.classList.toggle('visible', n === courant));
        vignettes.forEach((v, n) => {
            if (n === courant) v.setAttribute('aria-current', 'true');
            else v.removeAttribute('aria-current');
            v.tabIndex = n === courant ? 0 : -1;
        });
        if (compteur) compteur.textContent = courant + 1;

        const active = vignettes[courant];
        active?.scrollIntoView({
            block: 'nearest',
            inline: 'nearest',
            behavior: reduit() ? 'auto' : 'smooth',
        });
        if (prendreLeFocus) active?.focus();
    };

    vignettes.forEach((v, i) => v.addEventListener('click', () => montrer(i)));

    viseur.querySelector('.rail')?.addEventListener('keydown', (e) => {
        const pas = {
            ArrowRight: 1,
            ArrowLeft: -1,
            Home: -courant,
            End: vues.length - 1 - courant,
        }[e.key];
        if (pas === undefined) return;
        e.preventDefault();
        montrer(courant + pas, true);
    });

    /* Glissement au doigt. Le seuil de 40 px évite de changer de photo sur un
       défilement vertical un peu oblique ; au-delà de 340 px on considère que
       le doigt a balayé l'écran et non la photo. */
    const scene = viseur.querySelector('.scene');
    let depart = null;

    scene?.addEventListener('pointerdown', (e) => {
        depart = e.pointerType === 'touch' ? { x: e.clientX, y: e.clientY } : null;
    });

    scene?.addEventListener('pointerup', (e) => {
        if (!depart) return;
        const dx = e.clientX - depart.x;
        const dy = e.clientY - depart.y;
        depart = null;
        if (Math.abs(dx) < 40 || Math.abs(dx) > 340 || Math.abs(dy) > Math.abs(dx)) return;

        // Le pointerup sera suivi d'un click, qui ouvrirait la vue plein écran
        // par-dessus la photo qu'on vient de faire défiler. On le neutralise
        // une fois, en phase de capture.
        scene.addEventListener(
            'click',
            (ev) => {
                ev.stopPropagation();
                ev.preventDefault();
            },
            { capture: true, once: true },
        );

        montrer(courant + (dx < 0 ? 1 : -1));
    });
});

/* ---------- transitions de page ----------

   Le navigateur sait déjà fondre une page dans l'autre (règle
   @view-transition dans app.css). Ce bloc ajoute le seul détail qu'il ne
   peut pas deviner : quelle image de la liste correspond à la grande photo
   de la fiche. Une fois les deux nommées pareil, il déplace la même image
   d'un cadre à l'autre au lieu de la faire disparaître puis réapparaître.

   Le nom est posé au dernier moment et retiré dès la fin du trajet : deux
   éléments portant le même nom au même instant annulent la transition. */

const NOM_VT = 'photo-fiche';

const photoDeLaFiche = () =>
    document.querySelector('.scene img.visible') ?? document.querySelector('.portail img');

const photoDeLaCarte = (url) => {
    if (!url) return null;
    const chemin = new URL(url, location.href).pathname;
    const lien = [...document.querySelectorAll('a.fiche, a.portrait')].find(
        (a) => new URL(a.href, location.href).pathname === chemin,
    );
    return lien?.querySelector('img') ?? null;
};

const nommer = (img, transition) => {
    if (!img) return;
    img.style.viewTransitionName = NOM_VT;
    transition?.finished.finally(() => {
        img.style.viewTransitionName = '';
    });
};

window.addEventListener('pageswap', (e) => {
    if (!e.viewTransition) return;
    nommer(photoDeLaCarte(e.activation?.entry?.url) ?? photoDeLaFiche(), e.viewTransition);
});

window.addEventListener('pagereveal', (e) => {
    if (!e.viewTransition) return;
    // Sur une fiche c'est la grande photo ; sur une liste, la vignette d'où
    // l'on vient — le retour arrière replie l'image sur son cadre.
    nommer(
        photoDeLaFiche() ?? photoDeLaCarte(window.navigation?.activation?.from?.url),
        e.viewTransition,
    );
});


/* ---------- lecture d'un Maine Coon ----------

   Deux entrees pour la meme information : les reperes poses sur la photo, et
   la liste des traits a cote. Survoler ou cliquer l'un allume l'autre.

   Les six textes sont ecrits cote serveur et deplies par defaut : sans
   JavaScript la page reste complete, elle perd seulement le pliage. On ne les
   replie donc qu'une fois le script en place. */

const lecteur = document.getElementById('lecture');

if (lecteur) {
    const reperes = [...lecteur.querySelectorAll('.repere')];
    const traits = [...lecteur.querySelectorAll('.traits button')];

    const montrer = (i) => {
        reperes.forEach((r) => r.setAttribute('aria-pressed', String(Number(r.dataset.point) === i)));
        traits.forEach((t) => {
            if (Number(t.dataset.point) === i) t.setAttribute('aria-current', 'true');
            else t.removeAttribute('aria-current');
        });
    };

    const depuis = (e) => {
        const b = e.target.closest('[data-point]');
        if (b) montrer(Number(b.dataset.point));
    };

    lecteur.addEventListener('click', depuis);
    lecteur.addEventListener('mouseover', depuis);
    lecteur.addEventListener('focusin', depuis);

    montrer(0);
}


/* ---------- hauteur réelle du bandeau ----------

   Le sommaire collant et les ancres doivent se placer juste sous le bandeau.
   Sa hauteur change entre le bureau et le téléphone, et selon la longueur de
   la marque : on la mesure plutôt que de la deviner. */

const mesurerBandeau = () => {
    if (!bandeau) return;
    document.documentElement.style.setProperty('--h-bandeau', `${Math.round(bandeau.offsetHeight)}px`);
};
mesurerBandeau();
window.addEventListener('resize', mesurerBandeau);

/* ---------- sommaire : la section en cours s'allume ----------

   Calcul direct plutot qu'un IntersectionObserver : on cherche la derniere
   section dont le haut est deja passe sous le bandeau. C'est trois lignes de
   geometrie, ca ne depend d'aucune heuristique de fenetre d'observation, et
   ca donne le meme resultat sur un saut d'ancre que sur un defilement lent.

   L'appel est cale sur requestAnimationFrame : la position n'est lue qu'une
   fois par image, jamais a chaque evenement de defilement. */

const sommaire = document.querySelector('.sommaire');

if (sommaire) {
    const liens = [...sommaire.querySelectorAll('a[href^="#"]')];
    const sections = liens
        .map((a) => ({ lien: a, cible: document.getElementById(decodeURIComponent(a.getAttribute('href').slice(1))) }))
        .filter((s) => s.cible);

    if (sections.length) {
        let enAttente = false;
        let dernier = null;

        const placer = () => {
            enAttente = false;

            // La ligne de reference : juste sous le bandeau et le sommaire.
            const ligne = (bandeau?.offsetHeight ?? 74) + sommaire.offsetHeight + 8;

            let courante = sections[0];
            for (const s of sections) {
                if (s.cible.getBoundingClientRect().top <= ligne) courante = s;
            }

            // En bas de page, la derniere section gagne meme si son haut est
            // reste au-dessus de la ligne : sinon elle ne s'allume jamais.
            if (window.innerHeight + window.scrollY >= document.body.scrollHeight - 4) {
                courante = sections[sections.length - 1];
            }

            if (courante === dernier) return;
            dernier = courante;

            sections.forEach(({ lien }) => lien.removeAttribute('aria-current'));
            courante.lien.setAttribute('aria-current', 'true');

            const rail = sommaire.querySelector('.sommaire-in') ?? sommaire;
            if (rail.scrollWidth > rail.clientWidth) {
                courante.lien.scrollIntoView({
                    inline: 'center', block: 'nearest',
                    behavior: reduit() ? 'auto' : 'smooth',
                });
            }
        };

        const demander = () => {
            if (enAttente) return;
            enAttente = true;
            requestAnimationFrame(placer);
        };

        window.addEventListener('scroll', demander, { passive: true });
        window.addEventListener('resize', demander);
        placer();
    }
}

/* ---------- la liasse ----------
   Plusieurs registres dans le même cadre, un seul à l'écran. On passe de
   l'un à l'autre par les flèches, par les jalons, au clavier ou d'un glissé
   du pouce.

   En dessous de deux fiches on ne fait rien : la pile ordinaire est déjà la
   bonne réponse, et une flèche qui ne mène nulle part serait un mensonge. */
document.querySelectorAll('[data-liasse]').forEach((liasse) => {
    const scene = liasse.querySelector('.liasse-scene');
    const barre = liasse.querySelector('.liasse-barre');
    const volets = [...scene.children];
    if (volets.length < 2 || !barre) return;

    const jalons = barre.querySelector('.jalons');
    let index = 0;

    /* Le nom de chaque fiche est lu sur la fiche elle-même : il n'est écrit
       qu'à un seul endroit, il ne peut donc pas diverger. */
    volets.forEach((volet, i) => {
        const nom = volet.dataset.jalon
            || volet.querySelector('.entete b')?.textContent.trim()
            || `Fiche ${i + 1}`;

        const bouton = document.createElement('button');
        bouton.type = 'button';
        bouton.textContent = nom;
        bouton.addEventListener('click', () => aller(i));

        const li = document.createElement('li');
        li.append(bouton);
        jalons.append(li);
    });

    const boutons = [...jalons.querySelectorAll('button')];

    const mesurer = () => {
        scene.style.setProperty('--h-volet', `${volets[index].offsetHeight}px`);
    };

    const poser = (cible) => {
        index = cible;

        volets.forEach((volet, i) => {
            volet.dataset.etat = i === cible ? 'actif' : (i < cible ? 'avant' : 'apres');
            volet.toggleAttribute('inert', i !== cible);
            volet.setAttribute('aria-hidden', String(i !== cible));
        });

        boutons.forEach((b, i) => {
            if (i === cible) b.setAttribute('aria-current', 'true');
            else b.removeAttribute('aria-current');
        });

        mesurer();
    };

    /* Les flèches tournent en boucle : avec deux fiches, un bouton grisé une
       fois sur deux se remarque plus que le passage lui-même. */
    const aller = (vers) => {
        const cible = (vers + volets.length) % volets.length;
        if (cible !== index) poser(cible);
    };

    barre.querySelectorAll('[data-pas]').forEach((bouton) => {
        bouton.addEventListener('click', () => aller(index + Number(bouton.dataset.pas)));
    });

    liasse.addEventListener('keydown', (e) => {
        if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft') return;
        if (e.target.closest('input, textarea, select')) return;
        e.preventDefault();
        aller(index + (e.key === 'ArrowRight' ? 1 : -1));
    });

    /* Le glissé du pouce, au doigt seulement : à la souris, un cliqué-glissé
       sert à sélectionner du texte. */
    let depart = null;
    scene.addEventListener('pointerdown', (e) => {
        depart = e.pointerType === 'mouse' ? null : { x: e.clientX, y: e.clientY };
    });
    scene.addEventListener('pointerup', (e) => {
        if (!depart) return;
        const dx = e.clientX - depart.x;
        const dy = e.clientY - depart.y;
        depart = null;
        if (Math.abs(dx) > 48 && Math.abs(dx) > Math.abs(dy) * 1.6) {
            aller(index + (dx < 0 ? 1 : -1));
        }
    });

    liasse.dataset.feuillete = '';
    barre.hidden = false;
    poser(0);

    /* Les transitions n'arrivent qu'une fois les polices posées : mesurée
       avec la police de secours, une fiche ne fait pas la même hauteur, et
       la scène se verrait alors corriger sa taille en glissant. */
    const armer = () => requestAnimationFrame(() => { mesurer(); liasse.dataset.pret = ''; });
    if (document.fonts?.ready) document.fonts.ready.then(armer);
    else armer();

    /* Une fiche peut changer de hauteur sans que la fenêtre bouge : polices
       enfin chargées, tableau qui passe en colonne, texte replié. */
    if ('ResizeObserver' in window) {
        const veille = new ResizeObserver(mesurer);
        volets.forEach((volet) => veille.observe(volet));
    }
    window.addEventListener('resize', mesurer);
});

/* ---------- les chiffres qui montent ----------

   Chaque <b data-compte="5"> part de zéro et monte jusqu'à sa valeur en
   entrant dans le champ. Le texte écrit dans la balise reste « 0 » : si le
   script ne passe pas, la page affiche un zéro plutôt qu'un trou — mais le
   filet de sécurité plus bas pose la vraie valeur dans tous les cas. */
{
    const compteurs = [...document.querySelectorAll('[data-compte]')];

    if (compteurs.length) {
        const format = new Intl.NumberFormat('fr-FR');
        const valeur = (el) => Number(el.dataset.compte);

        const poser = (el) => {
            const cible = valeur(el);
            el.textContent = Number.isFinite(cible) ? format.format(cible) : el.textContent;
            el.dataset.compte = '';
        };

        const monter = (el) => {
            const cible = valeur(el);
            if (!Number.isFinite(cible)) return;
            if (reduit() || cible === 0) { poser(el, cible); return; }

            el.dataset.compte = '';
            const duree = 760 + Math.min(cible, 80) * 11;
            const depart = performance.now();

            const pas = (t) => {
                const p = Math.min(1, (t - depart) / duree);
                /* Le compte ralentit avant de s'arrêter : un chiffre qui
                   s'immobilise net donne l'impression d'un saut. */
                el.textContent = format.format(Math.round(cible * (1 - (1 - p) ** 3)));
                if (p < 1) requestAnimationFrame(pas);
            };

            requestAnimationFrame(pas);
        };

        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver(
                (entrees) => entrees.forEach((e) => {
                    if (!e.isIntersecting) return;
                    io.unobserve(e.target);
                    monter(e.target);
                }),
                { rootMargin: '0px 0px -12% 0px' },
            );
            compteurs.forEach((el) => io.observe(el));
        } else {
            compteurs.forEach(poser);
        }

        /* Un chiffre resté à zéro est un mensonge, pas une animation ratée. */
        setTimeout(() => {
            document.querySelectorAll('[data-compte]:not([data-compte=""])').forEach((el) => {
                if (el.getBoundingClientRect().top < window.innerHeight * 1.2) poser(el);
            });
        }, 2400);
    }
}
