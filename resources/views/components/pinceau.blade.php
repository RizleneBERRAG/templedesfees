@props(['classe' => null])

{{--
    Le coup de pinceau.

    Un aplat d'or ne ressemble a rien : ce qui fait la peinture, c'est la
    trace seche — les stries que laissent les poils quand la brosse se vide,
    et les bords qui s'effilochent. Elles sont tracees une a une dans
    scripts/, puis figees ici : le dessin ne bouge plus d'un rendu a l'autre.

    L'or est celui de la charte, --dorure, repris en degrade SVG. Le composant
    ne se positionne pas lui-meme : il remplit son parent, qui decide de sa
    place et de sa taille.
--}}
<svg class="pinceau {{ $classe }}" viewBox="0 0 620 150" preserveAspectRatio="none"
     aria-hidden="true" focusable="false">
  <defs>
    <linearGradient id="pinceau-or" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="#FBF3DD"/>
      <stop offset="44%" stop-color="#E5C88C"/>
      <stop offset="100%" stop-color="#B48F48"/>
    </linearGradient>

    <mask id="pinceau-sec" maskUnits="userSpaceOnUse" x="0" y="0" width="620" height="150">
      <rect width="620" height="150" fill="#fff"/>
            <path d="M159.0,54.3 Q254.6,55.4 350.2,54.6" stroke="#000" stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.69"/>
      <path d="M110.6,103.5 Q315.7,102.9 520.8,103.3" stroke="#000" stroke-width="2.3" stroke-linecap="round" fill="none" opacity="0.59"/>
      <path d="M284.7,101.1 Q375.2,103.9 465.8,102.0" stroke="#000" stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.24"/>
      <path d="M298.1,88.0 Q427.5,90.4 556.9,88.7" stroke="#000" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.32"/>
      <path d="M151.4,45.5 Q267.6,49.3 383.7,46.7" stroke="#000" stroke-width="1.0" stroke-linecap="round" fill="none" opacity="0.29"/>
      <path d="M92.3,52.8 Q267.0,52.1 441.6,52.6" stroke="#000" stroke-width="3.1" stroke-linecap="round" fill="none" opacity="0.27"/>
      <path d="M267.8,81.4 Q410.4,80.2 553.0,81.0" stroke="#000" stroke-width="3.0" stroke-linecap="round" fill="none" opacity="0.22"/>
      <path d="M268.0,68.4 Q428.5,64.6 589.0,67.3" stroke="#000" stroke-width="1.1" stroke-linecap="round" fill="none" opacity="0.23"/>
      <path d="M157.8,59.9 Q325.5,58.7 493.2,59.5" stroke="#000" stroke-width="1.4" stroke-linecap="round" fill="none" opacity="0.19"/>
      <path d="M180.3,69.1 Q384.1,70.8 587.8,69.6" stroke="#000" stroke-width="2.6" stroke-linecap="round" fill="none" opacity="0.37"/>
      <path d="M38.9,90.0 Q306.4,94.2 573.9,91.2" stroke="#000" stroke-width="2.8" stroke-linecap="round" fill="none" opacity="0.58"/>
      <path d="M134.1,70.7 Q250.5,64.6 366.9,68.9" stroke="#000" stroke-width="2.4" stroke-linecap="round" fill="none" opacity="0.16"/>
      <path d="M68.8,58.2 Q248.6,53.3 428.4,56.7" stroke="#000" stroke-width="0.8" stroke-linecap="round" fill="none" opacity="0.19"/>
      <path d="M124.4,50.9 Q235.5,46.0 346.6,49.4" stroke="#000" stroke-width="3.1" stroke-linecap="round" fill="none" opacity="0.63"/>
      <path d="M119.9,61.2 Q277.3,68.1 434.7,63.2" stroke="#000" stroke-width="1.0" stroke-linecap="round" fill="none" opacity="0.64"/>
      <path d="M157.5,75.7 Q259.9,72.4 362.3,74.7" stroke="#000" stroke-width="1.0" stroke-linecap="round" fill="none" opacity="0.24"/>
      <path d="M68.6,100.4 Q207.3,95.4 346.0,98.9" stroke="#000" stroke-width="3.3" stroke-linecap="round" fill="none" opacity="0.52"/>
      <path d="M31.5,80.9 Q254.4,83.7 477.3,81.8" stroke="#000" stroke-width="3.3" stroke-linecap="round" fill="none" opacity="0.43"/>
      <path d="M125.2,61.8 Q254.3,65.7 383.4,62.9" stroke="#000" stroke-width="2.8" stroke-linecap="round" fill="none" opacity="0.46"/>
      <path d="M85.6,66.4 Q318.3,70.7 551.0,67.7" stroke="#000" stroke-width="3.4" stroke-linecap="round" fill="none" opacity="0.56"/>
      <path d="M228.2,99.6 Q313.6,93.1 399.0,97.7" stroke="#000" stroke-width="2.1" stroke-linecap="round" fill="none" opacity="0.41"/>
      <path d="M101.1,45.9 Q254.3,45.2 407.4,45.7" stroke="#000" stroke-width="2.6" stroke-linecap="round" fill="none" opacity="0.85"/>
      <path d="M296.7,107.7 Q442.9,103.9 589.1,106.6" stroke="#000" stroke-width="1.7" stroke-linecap="round" fill="none" opacity="0.39"/>
      <path d="M80.4,57.4 Q291.3,57.1 502.3,57.3" stroke="#000" stroke-width="3.1" stroke-linecap="round" fill="none" opacity="0.69"/>
      <path d="M244.7,88.4 Q303.4,92.4 362.0,89.6" stroke="#000" stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.57"/>
      <path d="M155.9,95.0 Q271.2,99.2 386.4,96.3" stroke="#000" stroke-width="2.8" stroke-linecap="round" fill="none" opacity="0.36"/>
      <path d="M133.3,110.1 Q288.8,105.5 444.4,108.7" stroke="#000" stroke-width="3.3" stroke-linecap="round" fill="none" opacity="0.77"/>
      <path d="M65.7,52.6 Q320.5,57.2 575.3,54.0" stroke="#000" stroke-width="2.9" stroke-linecap="round" fill="none" opacity="0.31"/>
      <path d="M205.4,110.7 Q318.3,103.9 431.1,108.6" stroke="#000" stroke-width="2.2" stroke-linecap="round" fill="none" opacity="0.34"/>
      <path d="M203.3,110.0 Q340.1,115.2 476.9,111.6" stroke="#000" stroke-width="3.2" stroke-linecap="round" fill="none" opacity="0.56"/>
      <path d="M82.2,100.2 Q243.9,101.4 405.5,100.5" stroke="#000" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.35"/>
      <path d="M139.6,61.6 Q256.9,61.1 374.1,61.5" stroke="#000" stroke-width="3.2" stroke-linecap="round" fill="none" opacity="0.37"/>
      <path d="M273.6,83.7 Q361.5,84.1 449.4,83.8" stroke="#000" stroke-width="3.2" stroke-linecap="round" fill="none" opacity="0.33"/>
      <path d="M29.2,79.6 Q241.8,83.8 454.4,80.9" stroke="#000" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.12"/>
      <path d="M154.7,55.7 Q341.6,56.0 528.6,55.8" stroke="#000" stroke-width="2.2" stroke-linecap="round" fill="none" opacity="0.40"/>
      <path d="M240.5,81.8 Q304.0,78.6 367.6,80.8" stroke="#000" stroke-width="2.2" stroke-linecap="round" fill="none" opacity="0.22"/>
      <path d="M164.1,96.5 Q325.1,95.7 486.0,96.3" stroke="#000" stroke-width="2.8" stroke-linecap="round" fill="none" opacity="0.70"/>
      <path d="M163.5,85.7 Q318.3,86.1 473.2,85.8" stroke="#000" stroke-width="2.6" stroke-linecap="round" fill="none" opacity="0.33"/>
      <path d="M283.9,76.5 Q403.4,73.1 523.0,75.5" stroke="#000" stroke-width="3.1" stroke-linecap="round" fill="none" opacity="0.44"/>
      <path d="M284.3,82.0 Q421.7,81.2 559.1,81.8" stroke="#000" stroke-width="1.1" stroke-linecap="round" fill="none" opacity="0.17"/>
      <path d="M90.4,48.9 Q224.7,54.5 359.0,50.6" stroke="#000" stroke-width="2.5" stroke-linecap="round" fill="none" opacity="0.77"/>
      <path d="M221.6,54.5 Q366.7,61.0 511.7,56.5" stroke="#000" stroke-width="1.1" stroke-linecap="round" fill="none" opacity="0.76"/>
      <path d="M286.9,58.9 Q367.3,63.6 447.7,60.3" stroke="#000" stroke-width="2.0" stroke-linecap="round" fill="none" opacity="0.75"/>
      <path d="M143.1,55.0 Q308.6,52.4 474.1,54.2" stroke="#000" stroke-width="1.6" stroke-linecap="round" fill="none" opacity="0.33"/>
      <path d="M29.4,93.1 Q256.7,90.7 484.1,92.4" stroke="#000" stroke-width="1.9" stroke-linecap="round" fill="none" opacity="0.18"/>
      <path d="M165.4,86.4 Q261.0,93.0 356.7,88.4" stroke="#000" stroke-width="3.4" stroke-linecap="round" fill="none" opacity="0.48"/>
      <ellipse cx="166.1" cy="65.8" rx="9.0" ry="5.9" fill="#000" opacity="0.34"/>
      <ellipse cx="177.0" cy="74.0" rx="31.7" ry="6.1" fill="#000" opacity="0.34"/>
      <ellipse cx="185.7" cy="99.8" rx="22.8" ry="5.5" fill="#000" opacity="0.28"/>
      <ellipse cx="145.3" cy="87.8" rx="19.1" ry="2.4" fill="#000" opacity="0.58"/>
      <ellipse cx="399.2" cy="93.7" rx="10.2" ry="6.3" fill="#000" opacity="0.27"/>
      <ellipse cx="499.6" cy="75.6" rx="16.8" ry="4.8" fill="#000" opacity="0.57"/>
      <ellipse cx="237.9" cy="58.7" rx="21.7" ry="3.2" fill="#000" opacity="0.29"/>
    </mask>
  </defs>

  <g mask="url(#pinceau-sec)">
    <path d="M26,92 C58,58 118,47 188,49 C262,51 338,38 424,42 C486,45 538,53 576,66 C551,75 512,72 476,77 C534,82 566,90 590,101 C548,112 472,102 400,106 C296,112 188,123 98,114 C58,110 31,103 26,92 Z" fill="url(#pinceau-or)"/>
        <path d="M545.1,66.1 C559.3,66.3 570.0,66.5 580.7,66.6" stroke="url(#pinceau-or)" stroke-width="0.9" stroke-linecap="round" fill="none" opacity="0.51"/>
    <path d="M557.3,51.2 C564.1,49.2 569.2,47.1 574.4,46.1" stroke="url(#pinceau-or)" stroke-width="1.6" stroke-linecap="round" fill="none" opacity="0.39"/>
    <path d="M568.1,71.8 C576.0,72.4 581.9,73.0 587.8,73.3" stroke="url(#pinceau-or)" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.78"/>
    <path d="M553.5,80.3 C571.6,82.0 585.2,83.8 598.8,84.6" stroke="url(#pinceau-or)" stroke-width="0.9" stroke-linecap="round" fill="none" opacity="0.48"/>
    <path d="M544.0,56.1 C554.1,54.5 561.7,53.0 569.3,52.2" stroke="url(#pinceau-or)" stroke-width="2.3" stroke-linecap="round" fill="none" opacity="0.61"/>
    <path d="M552.7,83.8 C565.6,81.7 575.4,79.6 585.1,78.5" stroke="url(#pinceau-or)" stroke-width="0.9" stroke-linecap="round" fill="none" opacity="0.44"/>
    <path d="M554.5,86.1 C564.7,85.9 572.3,85.7 580.0,85.5" stroke="url(#pinceau-or)" stroke-width="1.9" stroke-linecap="round" fill="none" opacity="0.48"/>
    <path d="M563.8,92.5 C573.1,92.6 580.1,92.7 587.1,92.8" stroke="url(#pinceau-or)" stroke-width="1.8" stroke-linecap="round" fill="none" opacity="0.74"/>
    <path d="M549.8,88.8 C568.0,88.5 581.6,88.1 595.2,87.9" stroke="url(#pinceau-or)" stroke-width="1.0" stroke-linecap="round" fill="none" opacity="0.69"/>
  </g>
</svg>
