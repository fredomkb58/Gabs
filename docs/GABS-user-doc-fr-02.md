<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logique sans bruit && design sans echo }**

*Version v0120*

</div>

---

# Documentation Utilisateur - Niveau 2

**Fonctionnalités avancées — mise en pratique sur un cas concret.**

> Ce document suppose que vous avez déjà parcouru le Niveau 1. Les concepts de base (variables, préfixes, filtres simples, conditions, inclusions) ne sont pas réexpliqués ici.

---

## 📖 Table des matières

- [Cas 2 — Portfolio](#-cas-2--portfolio)
  - [Étape 1 — La grille des œuvres](#étape-1--la-grille-des-œuvres-boucle--slicing--filtres)
  - [Étape 2 — Filtrage par catégorie](#étape-2--filtrage-par-catégorie)
  - [Étape 3 — La page détail](#étape-3--la-page-détail-infos-de-boucle--variables-globales)
  - [Étape 4 — Structure et inclusions dynamiques](#étape-4--structure-et-inclusions-dynamiques)

---

## 🎨 Cas 2 — Portfolio

Un portfolio d'artiste : page d'accueil avec grille des œuvres, filtrage par catégorie, page détail, et une structure modulaire avec inclusions dynamiques.

Ce cas introduit les fonctionnalités avancées de GABS : **slicing**, **tri inverse**, **infos de boucle**, **variables globales** et **inclusions dynamiques**.

---

### Étape 1 — La grille des œuvres *(boucle + slicing + filtres)*

**Objectif :** afficher les 6 dernières œuvres de l'artiste sous forme de grille, avec titre, catégorie, année et un lien vers le détail.

---

#### Structure des fichiers

```
portfolio/
├── Gabs.php
├── funcs_gabs.php
├── index.php
├── data.php
└── home.gabs
```

---

#### Les données *(`data.php`)*

```php
<?php

$data = array(

    // --- Artiste ---
    's_artist_name'  => 'Sophie Renard',
    's_artist_bio'   => 'Photographe et illustratrice basée à Lyon.',
    's_artist_photo' => '/images/artist/sophie-renard.jpg',

    // --- Catalogue complet des œuvres ---
    // Les œuvres sont classées de la plus ancienne à la plus récente
    // On utilisera le tri inverse + slicing pour afficher les 6 dernières
    'a_works' => array(

        array(
            's_title'     => 'brume matinale',
            's_category'  => 'photographie',
            's_slug'      => 'brume-matinale',
            'h_thumb'     => '/images/works/brume-matinale.jpg',
            'n_year'      => 2022,
            'n_ts_date'   => mktime(0, 0, 0, 3, 12, 2022),
            'b_featured'  => false,
            'b_sold'      => true,
        ),
        array(
            's_title'     => 'urbanité #3',
            's_category'  => 'illustration',
            's_slug'      => 'urbanite-3',
            'h_thumb'     => '/images/works/urbanite-3.jpg',
            'n_year'      => 2023,
            'n_ts_date'   => mktime(0, 0, 0, 6, 5, 2023),
            'b_featured'  => false,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'lumière d\'hiver',
            's_category'  => 'photographie',
            's_slug'      => 'lumiere-hiver',
            'h_thumb'     => '/images/works/lumiere-hiver.jpg',
            'n_year'      => 2023,
            'n_ts_date'   => mktime(0, 0, 0, 11, 20, 2023),
            'b_featured'  => true,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'série bleue #1',
            's_category'  => 'illustration',
            's_slug'      => 'serie-bleue-1',
            'h_thumb'     => '/images/works/serie-bleue-1.jpg',
            'n_year'      => 2024,
            'n_ts_date'   => mktime(0, 0, 0, 2, 8, 2024),
            'b_featured'  => false,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'série bleue #2',
            's_category'  => 'illustration',
            's_slug'      => 'serie-bleue-2',
            'h_thumb'     => '/images/works/serie-bleue-2.jpg',
            'n_year'      => 2024,
            'n_ts_date'   => mktime(0, 0, 0, 4, 15, 2024),
            'b_featured'  => true,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'silence urbain',
            's_category'  => 'photographie',
            's_slug'      => 'silence-urbain',
            'h_thumb'     => '/images/works/silence-urbain.jpg',
            'n_year'      => 2024,
            'n_ts_date'   => mktime(0, 0, 0, 9, 3, 2024),
            'b_featured'  => false,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'forêt de janvier',
            's_category'  => 'photographie',
            's_slug'      => 'foret-janvier',
            'h_thumb'     => '/images/works/foret-janvier.jpg',
            'n_year'      => 2025,
            'n_ts_date'   => mktime(0, 0, 0, 1, 18, 2025),
            'b_featured'  => true,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'abstraction #7',
            's_category'  => 'illustration',
            's_slug'      => 'abstraction-7',
            'h_thumb'     => '/images/works/abstraction-7.jpg',
            'n_year'      => 2025,
            'n_ts_date'   => mktime(0, 0, 0, 5, 22, 2025),
            'b_featured'  => false,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'rouge et or',
            's_category'  => 'illustration',
            's_slug'      => 'rouge-et-or',
            'h_thumb'     => '/images/works/rouge-et-or.jpg',
            'n_year'      => 2026,
            'n_ts_date'   => mktime(0, 0, 0, 1, 10, 2026),
            'b_featured'  => true,
            'b_sold'      => false,
        ),

    ),

);
```

---

#### Le gabarit *(`home.gabs`)*

```html
<section class="artist-intro">
    <img src="{s_artist_photo}" alt="{s_artist_name|f_title}">
    <div>
        <h1>{s_artist_name|f_title}</h1>
        <p>{s_artist_bio}</p>
    </div>
</section>

<section class="works-grid">

    <h2>Dernières créations</h2>

    <!-- Tri inverse : du plus récent au plus ancien             -->
    <!-- Slicing [-6[ … ]0] : les 6 derniers éléments           -->
    <!-- Combiné : {a_works{[-6[!  = 6 derniers, ordre inverse  -->
    <div class="grid">
    {a_works{[-6[!

        <article class="work-card {b_featured{[ work-card--featured }b_featured}">

            <a href="/oeuvre/{s_slug}">
                <img src="{h_thumb|}" alt="{s_title|f_title}">
            </a>

            <div class="work-card-info">
                <h3><a href="/oeuvre/{s_slug}">{s_title|f_title}</a></h3>
                <!-- f_ucfirst : "photographie" → "Photographie" -->
                <span class="category">{s_category|f_ucfirst}</span>
                <span class="year">{n_year}</span>
            </div>

            <!-- Badges — syntaxe courte, toujours sur une seule ligne -->
            {b_featured{[ <span class="badge-featured">⭐ Coup de cœur</span> }b_featured}
            {b_sold{[ <span class="badge-sold">Vendu</span> }b_sold}

        </article>

    ]0]}a_works}
    </div>

    <!-- Infos de boucle : total du catalogue et nombre affiché -->
    <p class="works-count">
        {a_works_1_n} œuvres affichées sur {a_works_1_t} au catalogue
    </p>

</section>
```

---

#### Le contrôleur *(`index.php`)*

```php
<?php

require_once 'Gabs.php';
require_once 'funcs_gabs.php';
require_once 'data.php';

$gabs = new Gabs();
$gabs->conf(array('cach' => false, 'dbug' => false));

echo $gabs->get('home.gabs', $data, $aFuncsGabs);
```

---

#### Le résultat HTML *(extrait)*

```html
<section class="works-grid">

    <h2>Dernières créations</h2>

    <div class="grid">

        <article class="work-card work-card--featured">
            <a href="/oeuvre/rouge-et-or">
                <img src="/images/works/rouge-et-or.jpg" alt="Rouge Et Or">
            </a>
            <div class="work-card-info">
                <h3><a href="/oeuvre/rouge-et-or">Rouge Et Or</a></h3>
                <span class="category">Illustration</span>
                <span class="year">2026</span>
            </div>
            <span class="badge-featured">⭐ Coup de cœur</span>
        </article>

        <article class="work-card work-card--featured">
            <a href="/oeuvre/abstraction-7">...</a>
            ...
        </article>

        <!-- ... 4 autres œuvres ... -->

    </div>

    <p class="works-count">6 œuvres affichées sur 9 au catalogue</p>

</section>
```

---

#### Ce qu'on a vu dans cette étape

| Concept | Syntaxe |
|---------|---------|
| **Tri inverse** | `{a_works{!` — du dernier au premier |
| **Slicing depuis la fin** | `[-6[` — les 6 derniers éléments |
| **Combinaison tri + slicing** | `{a_works{[-6[!` — 6 derniers, ordre inverse |
| **Infos de boucle** | `{a_works_1_n}` affiché / `{a_works_1_t}` total |

---

> 🎯 **Étape suivante :** filtrage par catégorie avec conditions et classes CSS dynamiques.

---

*— fin de l'Étape 1 —*
