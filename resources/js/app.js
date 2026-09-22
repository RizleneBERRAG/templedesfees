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

   Des repères posés sur une photo, et un panneau qui se remplit au survol
   comme au clic. Le panneau part rempli côté serveur avec le premier repère :
   sans JavaScript, la page reste lisible et complète, elle perd seulement
   l'interaction. */

const lecture = document.getElementById('lecture');
const lectureInfo = document.getElementById('lecture-info');

if (lecture && lectureInfo) {
    const points = JSON.parse(lecture.dataset.points || '[]');

    const afficher = (i) => {
        const p = points[i];
        if (!p) return;
        lectureInfo.innerHTML = `<span class="k"></span><h3></h3><p></p>`;
        lectureInfo.querySelector('.k').textContent = p.k;
        lectureInfo.querySelector('h3').textContent = p.t;
        lectureInfo.querySelector('p').textContent = p.d;
        lecture.querySelectorAll('.repere').forEach((r) =>
            r.setAttribute('aria-pressed', String(Number(r.dataset.point) === i)));
    };

    const depuis = (e) => {
        const b = e.target.closest('[data-point]');
        if (b) afficher(Number(b.dataset.point));
    };

    lecture.addEventListener('click', depuis);
    lecture.addEventListener('mouseover', depuis);
    lecture.addEventListener('focusin', depuis);
}


/* ---------- compteurs ----------

   Un nombre qui monte quand il entre dans le champ. Le chiffre final est
   déjà dans le HTML côté serveur : sans JavaScript, ou avec les animations
   réduites, il s'affiche tel quel — on n'anime jamais au prix de la
   lisibilité. */

const compter = (el) => {
    const cible = parseInt(el.dataset.compte, 10);
    if (Number.isNaN(cible)) return;
    if (reduit() || cible === 0) {
        el.textContent = cible;
        return;
    }
    const debut = performance.now();
    const tick = (t) => {
        const p = Math.min(1, (t - debut) / 900);
        el.textContent = Math.round(cible * (1 - Math.pow(1 - p, 3)));
        if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
};

const chiffres = [...document.querySelectorAll('[data-compte]')];

if (chiffres.length) {
    if (reduit()) {
        chiffres.forEach((el) => { el.textContent = el.dataset.compte; });
    } else {
        const ioc = new IntersectionObserver((entrees) => {
            entrees.forEach((e) => {
                if (!e.isIntersecting) return;
                compter(e.target);
                ioc.unobserve(e.target);
            });
        }, { rootMargin: '0px 0px -8% 0px' });

        chiffres.forEach((el) => {
            if (el.getBoundingClientRect().top < window.innerHeight * 1.02) compter(el);
            else ioc.observe(el);
        });
    }
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

            if (sommaire.scrollWidth > sommaire.clientWidth) {
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
