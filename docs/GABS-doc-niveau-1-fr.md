<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logique sans bruit && design sans echo }**

*Version v0120*

</div>

---

# GABS — Documentation Utilisateur - Niveau 1

**Apprenez GABS par l'exemple — du premier gabarit à l'application complète.**

---

## 📖 Table des matières

- [Introduction](#-introduction)
- [Cas 1 — Blog](#-cas-1--blog)
  - [Étape 1 — La page article](#étape-1--la-page-article-variables--filtres)
  - [Étape 2 — Les conditions](#étape-2--on-ajoute-les-conditions)
  - [Étape 3 — Les commentaires](#étape-3--la-liste-des-commentaires-à-venir)
  - [Étape 4 — La structure](#étape-4--header-footer-et-inclusions-à-venir)
- [Cas 2 — Portfolio](#-cas-2--portfolio-à-venir)
- [Cas 3 — Multi-langue](#-cas-3--multi-langue-à-venir)

---

## 💡 Introduction

Cette documentation vous guide à travers des **cas concrets et progressifs**.

Pas besoin d'avoir tout lu avant de commencer : chaque étape s'appuie sur la précédente, les explications sont là où elles sont utiles, et le code parle de lui-même.

**Ce dont vous avez besoin :**
- PHP 5.6 ou supérieur
- Un serveur local (XAMPP, WAMP, Laragon, ou `php -S localhost:8000`)
- `Gabs.php` — [téléchargeable ici](https://github.com/fredomkb58/Gabs)

---

## 📝 Cas 1 — Blog

Un blog est l'exemple parfait pour découvrir GABS : il y a du texte, des données structurées, des conditions d'affichage, des listes et des fichiers à inclure. On va construire ça étape par étape.

À la fin de ce cas, vous saurez utiliser **l'essentiel de GABS**.

---

### Étape 1 — La page article *(variables + filtres)*

**Ce qu'on veut afficher :**

Une page d'article de blog avec le titre, l'auteur, la date de publication, une image, le contenu et quelques métadonnées (catégorie, temps de lecture).

---

#### Structure des fichiers

Voici comment organiser votre projet pour cette étape :

```
mon-blog/
├── Gabs.php          ← le moteur GABS
├── funcs_gabs.php    ← la librairie de filtres GABS
├── index.php         ← votre contrôleur PHP
├── data.php          ← vos données PHP
└── article.gabs      ← votre gabarit HTML
```

> **Pourquoi séparer `data.php` et `index.php` ?**
> Pour l'exemple, c'est plus clair. En pratique, vos données viendront d'une base de données ou d'un fichier, mais la logique reste la même : on prépare un tableau PHP, on passe ce tableau à GABS.

---

#### Les données PHP *(`data.php`)*

On commence par préparer le tableau de données. Chaque clé du tableau deviendra une balise dans le gabarit.

```php
<?php

$data = array(

    // --- Titre et auteur ---
    's_title'    => 'Découvrir la photographie argentique en 2026',
    's_author'   => 'marie dupont',   // en minuscules volontairement : un filtre s'en chargera
    's_avatar'   => 'marie-dupont.jpg',

    // --- Dates (timestamps Unix) ---
    // mktime( heure, minute, seconde, mois, jour, année )
    'n_ts_published' => mktime(9, 30, 0, 1, 15, 2026),  // 15 janvier 2026
    'n_ts_updated'   => mktime(14, 0, 0, 2, 3, 2026),   // 3 février 2026

    // --- Image principale ---
    'h_img_url'  => '/images/articles/argentique-2026.jpg',  // préfixe h_ = pas d'échappement
    's_img_alt'  => 'Appareils photo argentiques sur une table en bois',

    // --- Contenu ---
    // Le préfixe h_ indique que cette donnée contient du HTML : elle ne sera pas échappée
    'h_content'  => '<p>La photographie argentique connaît un regain d\'intérêt…</p>
                     <p>Entre nostalgie et recherche d\'authenticité…</p>',

    // --- Métadonnées ---
    's_category'  => 'photographie',
    'n_read_time' => 7,   // minutes de lecture

    // --- URL canonique (pour les liens) ---
    'c_url_author' => '/auteur/marie-dupont',   // préfixe c_ = code, échappé mais pas modifié

);
```

> **Les préfixes, c'est quoi ?**
> GABS utilise les 2 premiers caractères de la clé pour savoir comment traiter la donnée :
> - `s_` → **string** : texte, échappé automatiquement (protection XSS)
> - `n_` → **number** : nombre, échappé automatiquement
> - `h_` → **html** : contenu HTML brut, affiché tel quel *(à utiliser avec confiance seulement)*
> - `c_` → **code** : URL ou attribut HTML, échappé automatiquement

---

#### Le gabarit GABS *(`article.gabs`)*

Maintenant, le gabarit. On place les clés du tableau PHP entre accolades `{ }` au bon endroit dans le HTML.

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{s_title|f_title}</title>  <!-- f_title : première lettre majuscule, reste en minuscules -->
</head>
<body>

<article class="article">

    <!-- ============================================================
         EN-TÊTE DE L'ARTICLE
         ============================================================ -->

    <header class="article-header">

        <!-- Le titre : on applique f_title pour normaliser la casse -->
        <h1>{s_title|f_title}</h1>

        <div class="article-meta">

            <!-- L'auteur : f_ucwords met chaque mot en majuscule -->
            <!-- Ex : "marie dupont" devient "Marie Dupont"        -->
            <a href="{c_url_author}" class="author">
                <img src="/images/avatars/{s_avatar}" alt="{s_author|f_ucwords}">
                <span>{s_author|f_ucwords}</span>
            </a>

            <!-- La date de publication : f_date formate le timestamp en "15/01/2026" -->
            <time class="published">
                Publié le {n_ts_published|f_date}
            </time>

            <!-- La date de mise à jour : f_date_time ajoute l'heure "03/02/2026 14:00" -->
            <time class="updated">
                Mis à jour le {n_ts_updated|f_date_time}
            </time>

        </div>

        <!-- Métadonnées : catégorie et temps de lecture -->
        <div class="article-tags">

            <!-- f_ucfirst : première lettre en majuscule                -->
            <!-- Ex : "photographie" devient "Photographie"              -->
            <span class="category">{s_category|f_ucfirst}</span>

            <!-- f_num : formate le nombre selon les conventions FR      -->
            <!-- Ici peu utile pour 7, mais bon réflexe sur les nombres  -->
            <span class="read-time">{n_read_time|f_num} min de lecture</span>

        </div>

    </header>

    <!-- ============================================================
         IMAGE PRINCIPALE
         ============================================================ -->

    <!-- h_img_url : préfixe h_ donc pas d'échappement nécessaire  -->
    <!-- Le '|}' final sur h_ est optionnel mais explicite          -->
    <figure class="article-figure">
        <img src="{h_img_url|}" alt="{s_img_alt}">
        <figcaption>{s_img_alt}</figcaption>
    </figure>

    <!-- ============================================================
         CONTENU DE L'ARTICLE
         ============================================================ -->

    <!-- h_content contient du HTML : on utilise '|}' pour l'afficher tel quel -->
    <div class="article-content">
        {h_content|}
    </div>

</article>

</body>
</html>
```

> **Les filtres, comment ça marche ?**
> On ajoute `|f_nom_du_filtre` juste après la clé, à l'intérieur des accolades.
> On peut en enchaîner plusieurs : `{s_name|f_trim|f_title}` — ils s'appliquent de gauche à droite.
> Le `|}` final (pipe sans filtre) signifie "affiche la donnée brute, sans échappement".

---

#### Le contrôleur PHP *(`index.php`)*

C'est ici que tout s'assemble : on charge GABS, les filtres, les données, et on lance le rendu.

```php
<?php

// --- 1. On charge le moteur GABS et la librairie de filtres ---
require_once 'Gabs.php';
require_once 'funcs_gabs.php';  // donne accès à $aFuncsGabs

// --- 2. On charge les données ---
require_once 'data.php';        // donne accès à $data

// --- 3. On crée une instance de GABS ---
$gabs = new Gabs();

// --- 4. On configure GABS pour le développement ---
// cach => false : le cache est désactivé (pratique pour voir les modifications en direct)
// dbug => true  : le mode debug est activé (affiche les données si {_} est dans le gabarit)
$gabs->conf(array(
    'cach' => false,
    'dbug' => true,
));

// --- 5. On lance le rendu et on affiche le résultat ---
echo $gabs->get('article.gabs', $data, $aFuncsGabs);
//                   ↑             ↑         ↑
//                gabarit       données    filtres
```

> **Pourquoi `dbug => true` en développement ?**
> Cela active la balise spéciale `{_|}` : si vous l'ajoutez dans votre gabarit, GABS affichera toutes vos données en clair — très utile pour vérifier ce que contient votre tableau.

---

#### Le résultat HTML

En ouvrant `index.php` dans votre navigateur, GABS va fusionner les données et le gabarit pour produire ce HTML :

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Découvrir La Photographie Argentique En 2026</title>
</head>
<body>

<article class="article">

    <header class="article-header">

        <h1>Découvrir La Photographie Argentique En 2026</h1>

        <div class="article-meta">

            <a href="/auteur/marie-dupont" class="author">
                <img src="/images/avatars/marie-dupont.jpg" alt="Marie Dupont">
                <span>Marie Dupont</span>
            </a>

            <time class="published">
                Publié le 15/01/2026
            </time>

            <time class="updated">
                Mis à jour le 03/02/2026 14:00
            </time>

        </div>

        <div class="article-tags">
            <span class="category">Photographie</span>
            <span class="read-time">7 min de lecture</span>
        </div>

    </header>

    <figure class="article-figure">
        <img src="/images/articles/argentique-2026.jpg"
             alt="Appareils photo argentiques sur une table en bois">
        <figcaption>Appareils photo argentiques sur une table en bois</figcaption>
    </figure>

    <div class="article-content">
        <p>La photographie argentique connaît un regain d'intérêt…</p>
        <p>Entre nostalgie et recherche d'authenticité…</p>
    </div>

</article>

</body>
</html>
```

---

#### Ce qu'on a appris dans cette étape

| Concept | Ce qu'on a vu |
|---------|---------------|
| **Préfixes** | `s_` `n_` `h_` `c_` — chaque préfixe a un rôle et une protection |
| **Variables** | `{s_title}` — la syntaxe de base |
| **Filtres** | `{s_author\|f_ucwords}` — transformer une valeur dans le gabarit |
| **Enchaînement** | `{s_name\|f_trim\|f_title}` — plusieurs filtres à la suite |
| **Données brutes** | `{h_content\|}` — afficher du HTML sans échappement |
| **Timestamps** | `{n_ts_published\|f_date}` — formater une date depuis un timestamp |
| **Configuration** | `$gabs->conf()` — adapter GABS à son environnement |

---

> 🎯 **Prêt pour l'étape suivante ?**
> À l'étape 2, on va enrichir cette page avec des **conditions** : afficher un badge "À la une", gérer le statut de l'article, ajouter une classe CSS dynamique selon la catégorie.

---

*— fin de l'Étape 1 —*

---

### Étape 2 — On ajoute les conditions *(true/false)*

**Ce qu'on veut faire :**

Enrichir la page article avec des éléments qui s'affichent ou non selon l'état des données : un badge "À la une", un avertissement si l'article est en brouillon, et une classe CSS dynamique selon la catégorie.

---

#### Ce qu'on ajoute dans les données *(`data.php`)*

On complète le tableau `$data` existant avec trois nouvelles clés booléennes — le préfixe `b_` indique à GABS qu'il s'agit d'une condition.

```php
<?php

$data = array(

    // --- (toutes les données de l'Étape 1 restent inchangées) ---
    's_title'        => 'Découvrir la photographie argentique en 2026',
    's_author'       => 'marie dupont',
    's_avatar'       => 'marie-dupont.jpg',
    'n_ts_published' => mktime(9, 30, 0, 1, 15, 2026),
    'n_ts_updated'   => mktime(14, 0, 0, 2, 3, 2026),
    'h_img_url'      => '/images/articles/argentique-2026.jpg',
    's_img_alt'      => 'Appareils photo argentiques sur une table en bois',
    'h_content'      => '<p>La photographie argentique connaît un regain d\'intérêt…</p>',
    's_category'     => 'photographie',
    'n_read_time'    => 7,
    'c_url_author'   => '/auteur/marie-dupont',

    // --- Nouvelles données booléennes (Étape 2) ---
    // Les booléens utilisent le préfixe b_ et ne contiennent que true ou false
    'b_featured' => true,   // true  = article "À la une"
    'b_draft'    => false,  // false = article publié (pas un brouillon)
    'b_photo'    => true,   // true  = catégorie photo (pour la classe CSS)

);
```

> **Pourquoi des booléens séparés ?**
> En Logic-Less, c'est le PHP qui décide si quelque chose est vrai ou faux — pas le gabarit. Le gabarit se contente d'afficher en fonction de cette décision. C'est cette séparation claire qui rend le code maintenable.

---

#### Les conditions dans le gabarit *(`article.gabs`)*

GABS propose deux syntaxes pour les conditions. On va utiliser les deux dans cet exemple.

**Syntaxe complète** — quand on a du contenu à afficher dans les deux cas (vrai ET faux) :

```
{b_variable{
    contenu si VRAI
}b_variable{
    contenu si FAUX
}b_variable}
```

**Syntaxe courte** — quand on affiche quelque chose uniquement dans un seul cas :

```
{b_variable{[ contenu si VRAI seulement }b_variable}
{b_variable{ contenu si FAUX seulement ]}b_variable}
```

> ⚠️ **Contrainte technique — à retenir absolument :**
> La syntaxe courte **doit toujours tenir sur une seule ligne**, sans retour à la ligne à l'intérieur. Si votre contenu est long ou multiligne, utilisez obligatoirement la syntaxe complète à la place.

Voici le gabarit mis à jour — on ne montre ici que les parties modifiées ou ajoutées :

```html
<article class="article">

    <header class="article-header">

        <h1>{s_title|f_title}</h1>

        <!-- ============================================================
             BADGE "À LA UNE" — syntaxe courte, affichage si VRAI
             {b_featured{[ … }b_featured} = "affiche si b_featured est true"
             ⚠️ SYNTAXE COURTE = TOUJOURS SUR UNE SEULE LIGNE
             ============================================================ -->
        {b_featured{[ <span class="badge badge-featured">⭐ À la une</span> }b_featured}

        <!-- ============================================================
             AVERTISSEMENT BROUILLON — syntaxe courte, affichage si FAUX
             {b_draft{ … ]}b_draft} = "affiche si b_draft est false"
             ⚠️ SYNTAXE COURTE = TOUJOURS SUR UNE SEULE LIGNE
             Ici : si l'article N'EST PAS un brouillon, on affiche "Publié"
             ============================================================ -->
        {b_draft{ <span class="badge badge-published">✓ Publié</span> ]}b_draft}

        <!-- ============================================================
             SI b_draft est true (brouillon), on affiche cet avertissement
             ⚠️ SYNTAXE COURTE = TOUJOURS SUR UNE SEULE LIGNE
             ============================================================ -->
        {b_draft{[ <div class="alert alert-draft">⚠️ Cet article est en brouillon — non visible du public.</div> }b_draft}

        <div class="article-meta">
            <a href="{c_url_author}" class="author">
                <img src="/images/avatars/{s_avatar}" alt="{s_author|f_ucwords}">
                <span>{s_author|f_ucwords}</span>
            </a>
            <time class="published">Publié le {n_ts_published|f_date}</time>
            <time class="updated">Mis à jour le {n_ts_updated|f_date_time}</time>
        </div>

        <!-- ============================================================
             CLASSE CSS DYNAMIQUE selon la catégorie
             {b_photo{[ photo }b_photo} injecte "photo" dans l'attribut class
             si b_photo est true — rien n'est affiché si false
             ============================================================ -->
        <div class="article-tags article-tags--{b_photo{[ photo }b_photo}">
            <span class="category">{s_category|f_ucfirst}</span>
            <span class="read-time">{n_read_time|f_num} min de lecture</span>
        </div>

    </header>

    <figure class="article-figure">
        <img src="{h_img_url|}" alt="{s_img_alt}">
        <figcaption>{s_img_alt}</figcaption>
    </figure>

    <!-- ============================================================
         SYNTAXE COMPLÈTE — deux contenus alternatifs
         Si b_featured est true  : on affiche l'intro "À la une"
         Si b_featured est false : on affiche l'intro standard
         ============================================================ -->
    {b_featured{
        <p class="article-intro article-intro--featured">
            ⭐ Article sélectionné par la rédaction — bonne lecture !
        </p>
    }b_featured{
        <p class="article-intro">
            Bonne lecture !
        </p>
    }b_featured}

    <div class="article-content">
        {h_content|}
    </div>

</article>
```

---

#### Le résultat HTML

Avec `b_featured = true`, `b_draft = false` et `b_photo = true`, GABS produit :

```html
<article class="article">

    <header class="article-header">

        <h1>Découvrir La Photographie Argentique En 2026</h1>

        <!-- b_featured = true → badge affiché -->
        <span class="badge badge-featured">⭐ À la une</span>

        <!-- b_draft = false → "Publié" affiché (syntaxe FAUX) -->
        <span class="badge badge-published">✓ Publié</span>

        <!-- b_draft = false → avertissement brouillon NON affiché -->

        <div class="article-meta">
            <a href="/auteur/marie-dupont" class="author">
                <img src="/images/avatars/marie-dupont.jpg" alt="Marie Dupont">
                <span>Marie Dupont</span>
            </a>
            <time class="published">Publié le 15/01/2026</time>
            <time class="updated">Mis à jour le 03/02/2026 14:00</time>
        </div>

        <!-- b_photo = true → classe "photo" injectée -->
        <div class="article-tags article-tags--photo">
            <span class="category">Photographie</span>
            <span class="read-time">7 min de lecture</span>
        </div>

    </header>

    <figure class="article-figure">
        <img src="/images/articles/argentique-2026.jpg"
             alt="Appareils photo argentiques sur une table en bois">
        <figcaption>Appareils photo argentiques sur une table en bois</figcaption>
    </figure>

    <!-- b_featured = true → intro "À la une" affichée -->
    <p class="article-intro article-intro--featured">
        ⭐ Article sélectionné par la rédaction — bonne lecture !
    </p>

    <div class="article-content">
        <p>La photographie argentique connaît un regain d'intérêt…</p>
    </div>

</article>
```

> **Astuce :** Pour tester vos conditions, passez temporairement `b_draft => true` dans vos données et rechargez la page — vous verrez l'avertissement brouillon apparaître et le badge "Publié" disparaître. C'est la puissance du Logic-Less : on change les données, le gabarit s'adapte seul.

---

#### Ce qu'on a appris dans cette étape

| Concept | Ce qu'on a vu |
|---------|---------------|
| **Préfixe `b_`** | Les booléens déclenchent les conditions dans GABS |
| **Syntaxe complète** | `{b_var{ vrai }b_var{ faux }b_var}` — deux contenus alternatifs |
| **Syntaxe courte VRAI** | `{b_var{[ … }b_var}` — affiché uniquement si true — **sur une seule ligne** |
| **Syntaxe courte FAUX** | `{b_var{ … ]}b_var}` — affiché uniquement si false — **sur une seule ligne** |
| **Classe dynamique** | `class="tag--{b_var{[ photo }b_var}"` — injection dans un attribut |
| **Logic-Less** | Le gabarit ne décide rien — il affiche ce que PHP lui dit |

---

> 🎯 **Prêt pour l'étape suivante ?**
> À l'étape 3, on va afficher la **liste des commentaires** — et découvrir les boucles, les tableaux associatifs, et les infos de boucle pour afficher le nombre total de commentaires.

---

*— fin de l'Étape 2 —*

---

### Étape 3 — La liste des commentaires *(boucles)*

**Ce qu'on veut faire :**

Afficher sous l'article la liste des commentaires avec le nom de l'auteur, sa date et son message — puis le nombre total de commentaires en titre de section.

---

#### Ce qu'on ajoute dans les données *(`data.php`)*

Un tableau de tableaux : chaque commentaire est lui-même un tableau associatif.

```php
// --- Commentaires (Étape 3) ---
// a_ = tableau : GABS va boucler dessus automatiquement
'a_comments' => array(

    array(
        's_author'  => 'jean martin',
        'n_ts_date' => mktime(10, 15, 0, 1, 16, 2026),
        's_text'    => 'Très bel article, merci pour ce partage !',
        'b_author'  => false,  // false = lecteur ordinaire (pas l'auteur du blog)
    ),
    array(
        's_author'  => 'marie dupont',
        'n_ts_date' => mktime(11, 30, 0, 1, 16, 2026),
        's_text'    => 'Merci Jean, ravi que ça vous plaise !',
        'b_author'  => true,   // true = c'est l'auteur du blog qui répond
    ),
    array(
        's_author'  => 'sophie leclerc',
        'n_ts_date' => mktime(14, 0, 0, 1, 17, 2026),
        's_text'    => 'Je me suis remise à l\'argentique l\'année dernière, quelle révélation !',
        'b_author'  => false,
    ),

),
```

> **À l'intérieur d'une boucle**, GABS ne voit que les données du tableau courant — `s_author`, `n_ts_date`, etc. Les variables du niveau racine (`s_title`, `b_featured`…) ne sont pas accessibles directement. On verra comment y remédier à l'Étape 4 avec le suffixe `_g`.

---

#### Les boucles dans le gabarit *(`article.gabs`)*

On ajoute la section commentaires après le contenu de l'article :

```html
    <!-- ============================================================
         SECTION COMMENTAIRES
         {a_comments_1_t} = nombre TOTAL de commentaires dans le tableau
         Le "1" désigne la 1ère instance de cette boucle dans le gabarit
         ============================================================ -->
    <section class="comments">

        <h2>{a_comments_1_t} commentaire(s)</h2>

        <!-- ============================================================
             BOUCLE sur a_comments
             Tout ce qui est entre {a_comments{ et }a_comments}
             sera répété pour chaque commentaire du tableau
             ============================================================ -->
        {a_comments{

            <!-- b_author : classe CSS différente si c'est l'auteur du blog -->
            <!-- ⚠️ Syntaxe courte = toujours sur une seule ligne           -->
            <div class="comment {b_author{[ comment--author }b_author}">

                <div class="comment-header">

                    <!-- f_ucwords : "jean martin" → "Jean Martin" -->
                    <strong>{s_author|f_ucwords}</strong>

                    <!-- f_elapsed : affiche "il y a 2 jours", "il y a 1 h"… -->
                    <time>{n_ts_date|f_elapsed}</time>

                    <!-- Badge "Auteur" uniquement si b_author = true     -->
                    <!-- ⚠️ Syntaxe courte = toujours sur une seule ligne  -->
                    {b_author{[ <span class="badge-author">✍️ Auteur</span> }b_author}

                </div>

                <!-- s_text : texte échappé automatiquement (préfixe s_) -->
                <p class="comment-text">{s_text}</p>

            </div>

        }a_comments}

    </section>
```

---

#### Le résultat HTML

```html
<section class="comments">

    <h2>3 commentaire(s)</h2>

    <div class="comment">
        <div class="comment-header">
            <strong>Jean Martin</strong>
            <time>il y a 2 j</time>
        </div>
        <p class="comment-text">Très bel article, merci pour ce partage !</p>
    </div>

    <div class="comment comment--author">
        <div class="comment-header">
            <strong>Marie Dupont</strong>
            <time>il y a 2 j</time>
            <span class="badge-author">✍️ Auteur</span>
        </div>
        <p class="comment-text">Merci Jean, ravi que ça vous plaise !</p>
    </div>

    <div class="comment">
        <div class="comment-header">
            <strong>Sophie Leclerc</strong>
            <time>il y a 1 j</time>
        </div>
        <p class="comment-text">Je me suis remise à l'argentique l'année dernière, quelle révélation !</p>
    </div>

</section>
```

---

#### Ce qu'on a appris dans cette étape

| Concept | Ce qu'on a vu |
|---------|---------------|
| **Préfixe `a_`** | Tableau = boucle automatique dans GABS |
| **Boucle simple** | `{a_var{ … }a_var}` — répété pour chaque item |
| **Données dans la boucle** | Chaque item a ses propres clés (`s_`, `n_`, `b_`…) |
| **Info de boucle** | `{a_comments_1_t}` — nombre total d'éléments |
| **Conditions dans boucle** | `b_author` fonctionne exactement comme au niveau racine |
| **Filtres dans boucle** | `f_ucwords`, `f_elapsed` — identiques à l'Étape 1 |

---

> 🎯 **Prêt pour l'étape suivante ?**
> À l'Étape 4, on structure tout avec des **inclusions** — un `header.gabs` et un `footer.gabs` partagés, et on découvre le suffixe `_g` pour rendre des variables accessibles dans toutes les boucles.

---

*— fin de l'Étape 3 —*

---

### Étape 4 — Header, footer et inclusions *(modularité + variables globales)*

**Ce qu'on veut faire :**

Extraire le header et le footer dans des fichiers séparés réutilisables, et rendre le nom du blog accessible à l'intérieur des boucles grâce au suffixe `_g`.

---

#### La nouvelle structure des fichiers

```
mon-blog/
├── Gabs.php
├── funcs_gabs.php
├── index.php
├── data.php
├── article.gabs          ← gabarit principal (allégé)
└── includes/
    ├── header.gabs        ← nouveau : en-tête du site
    └── footer.gabs        ← nouveau : pied de page du site
```

---

#### Ce qu'on ajoute dans les données *(`data.php`)*

Deux nouvelles clés racine — dont une avec le suffixe `_g` :

```php
// --- Données du site (Étape 4) ---
's_site_name'   => 'Le Blog Argentique',  // nom du blog, niveau racine

// Le suffixe _g rend cette variable accessible à l'intérieur de toutes les boucles
// Sans _g, elle serait invisible depuis {a_comments{ … }a_comments}
's_site_name_g' => 'Le Blog Argentique',  // même valeur, disponible partout

'c_url_home'    => '/',                   // lien vers l'accueil
```

> **Pourquoi `_g` ?**
> Par défaut, les variables du niveau racine ne sont pas transmises à l'intérieur des boucles — c'est un choix de performance et de clarté. Le suffixe `_g` est le signal explicite : *"cette variable doit être accessible partout"*. Sans lui, `{s_site_name_g}` dans `{a_comments{ … }a_comments}` n't afficherait rien.

---

#### Le header *(`includes/header.gabs`)*

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- s_title vient des données de la page en cours -->
    <title>{s_title|f_title} — {s_site_name}</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header class="site-header">
    <a href="{c_url_home}" class="site-logo">
        <!-- f_upper : "Le Blog Argentique" → "LE BLOG ARGENTIQUE" -->
        {s_site_name|f_upper}
    </a>
    <nav>
        <a href="/">Accueil</a>
        <a href="/articles">Articles</a>
        <a href="/contact">Contact</a>
    </nav>
</header>

<main class="site-main">
```

---

#### Le footer *(`includes/footer.gabs`)*

```html
</main>

<footer class="site-footer">
    <!-- f_year : timestamp → année en cours -->
    <p>© {n_ts_published|f_year} {s_site_name} — Tous droits réservés</p>
</footer>

</body>
</html>
```

---

#### Le gabarit principal mis à jour *(`article.gabs`)*

Le gabarit s'allège considérablement : on retire tout ce qui va dans header et footer, et on ajoute les inclusions.

```html
<!-- ============================================================
     INCLUSION DU HEADER
     GABS remplace cette ligne par le contenu de header.gabs
     Le chemin est relatif au dossier de travail (ou à 'tpls')
     ============================================================ -->
{includes/header.gabs}

<article class="article">

    <header class="article-header">
        <h1>{s_title|f_title}</h1>
        {b_featured{[ <span class="badge badge-featured">⭐ À la une</span> }b_featured}
        {b_draft{ <span class="badge badge-published">✓ Publié</span> ]}b_draft}
        {b_draft{[ <div class="alert alert-draft">⚠️ Cet article est en brouillon.</div> }b_draft}

        <div class="article-meta">
            <a href="{c_url_author}" class="author">
                <img src="/images/avatars/{s_avatar}" alt="{s_author|f_ucwords}">
                <span>{s_author|f_ucwords}</span>
            </a>
            <time class="published">Publié le {n_ts_published|f_date}</time>
            <time class="updated">Mis à jour le {n_ts_updated|f_date_time}</time>
        </div>

        <div class="article-tags article-tags--{b_photo{[ photo }b_photo}">
            <span class="category">{s_category|f_ucfirst}</span>
            <span class="read-time">{n_read_time|f_num} min de lecture</span>
        </div>
    </header>

    <figure class="article-figure">
        <img src="{h_img_url|}" alt="{s_img_alt}">
        <figcaption>{s_img_alt}</figcaption>
    </figure>

    {b_featured{
        <p class="article-intro article-intro--featured">
            ⭐ Article sélectionné par la rédaction — bonne lecture !
        </p>
    }b_featured{
        <p class="article-intro">Bonne lecture !</p>
    }b_featured}

    <div class="article-content">
        {h_content|}
    </div>

</article>

<!-- ============================================================
     SECTION COMMENTAIRES
     s_site_name_g est accessible ici grâce au suffixe _g
     même si elle est définie au niveau racine des données
     ============================================================ -->
<section class="comments">

    <h2>{a_comments_1_t} commentaire(s) — {s_site_name_g}</h2>

    {a_comments{

        <div class="comment {b_author{[ comment--author }b_author}">
            <div class="comment-header">
                <strong>{s_author|f_ucwords}</strong>
                <time>{n_ts_date|f_elapsed}</time>
                {b_author{[ <span class="badge-author">✍️ Auteur</span> }b_author}
            </div>
            <p class="comment-text">{s_text}</p>
        </div>

    }a_comments}

</section>

<!-- ============================================================
     INCLUSION DU FOOTER
     ============================================================ -->
{includes/footer.gabs}
```

---

#### Le contrôleur final *(`index.php`)*

On ajoute la configuration du dossier de travail pour que GABS sache où chercher les gabarits :

```php
<?php

require_once 'Gabs.php';
require_once 'funcs_gabs.php';
require_once 'data.php';

$gabs = new Gabs();

$gabs->conf(array(
    'cach' => false,
    'dbug' => true,
    'tpls' => '',       // dossier racine des gabarits (vide = dossier courant)
));

echo $gabs->get('article.gabs', $data, $aFuncsGabs);
```

---

#### Ce qu'on a appris dans cette étape

| Concept | Ce qu'on a vu |
|---------|---------------|
| **Inclusions statiques** | `{includes/header.gabs}` — insérer un fichier dans un gabarit |
| **Modularité** | Header et footer partagés entre toutes les pages du site |
| **Suffixe `_g`** | Rend une variable racine accessible dans toutes les boucles |
| **`tpls`** | Option de configuration pour le dossier des gabarits |

---

#### Bilan du Cas 1 — Blog

En 4 étapes, on a construit une page d'article complète et on a couvert **tous les fondamentaux de GABS** :

| Étape | Concept |
|-------|---------|
| 1 | Variables, préfixes, filtres |
| 2 | Conditions binaires (complète et courte) |
| 3 | Boucles, infos de boucle, données imbriquées |
| 4 | Inclusions, modularité, variables globales `_g` |

Le projet est maintenant structuré, maintenable, et prêt à évoluer. C'est exactement l'esprit de GABS. 🎯

---

*— fin du Cas 1 — Blog —*

