<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logique sans bruit && design sans echo }**

**Moteur de gabarits PHP/HTML**

**simple • léger • rapide • sécurisé • logic-less**

*Version v0120*

[![PHP Version](https://img.shields.io/badge/PHP-5.6%2B-red)](https://php.net)
[![Version](https://img.shields.io/badge/version-0118-blue)](https://github.com/fredomkb58/gabs)
[![License](https://img.shields.io/badge/license-MIT-orange)](LICENSE)

</div>

---

## 📖 Table des matières

- [Pourquoi GABS ?](#-pourquoi-gabs-)
- [Installation](#-installation)
- [Démarrage rapide](#-démarrage-rapide)
- [Fonctionnalités](#-fonctionnalités)
- [Syntaxe](#-syntaxe)
- [Filtres](#-filtres-)
- [Configuration](#%EF%B8%8F-configuration)
- [Performance](#-performance)
- [Sécurité](#-sécurité)
- [Exemples complets](#-exemples-complets)
- [Conventions](#-conventions-de-nommage)
- [Contribuer](#-contribuer)

---

## 🎯 Pourquoi GABS ?

### Le problème

Tous les **développeurs en PHP et HTML** se sont forcément retrouvés un jour à devoir démêler un **"code-spaghetti"**, qui est devenu impossible à maintenir avec le temps, tellement la **logique PHP** est "polluée" par les **codes HTML de présentation**… et, côté design, cela devient extrêmement difficile lorsqu'on souaite faire le moindre ajustement **sans risquer de casser toute la logique PHP**… sans parler des **vrais risques de sécurité**, que ce type de codes mélangés à l'extrême finissent par poser.

C'est la raison pour laquelle, très tôt, **le besoin de séparer la logique de la présentation**, a donné naissance à des solutions permettant de développer la couche logique PHP "sans bruit" inutile et de réaliser les desings HTML "sans echo" encombrant = **les moteurs de gabarits (template-engines)**.

**GABS** *(contraction du mot français "gabarits" = "templates" en anglas)* s'inscrit pleinement dans cette idée de séparation des rôles, de simplification des taches et de clarification des responsabilités !

Parmi les différents concepts expérimentés dans cette optique de simplification,**l'approche "logic-less"** est celle qui décrit le mieux le choix fait par **GABS** : la logique reste la **responsabilité exclusive du PHP** et la présentation (ou design) est **le domaine réservé du HTML**.

**Et GABS alors ?** … il prend en charge d'être **le pont entre ces deux engagements forts** : entre PHP et HTML. 

Mais la question demeure : **pourquoi GABS ?** … alors que plein d'autres solutions existent et qui ont largement fait leurs preuves.

En effet, les **moteurs de gabarits** les plus connus aujourd'hui sont **puissants** et couvrent pratiquement tous les besoins, mais, souvent, ils sont aussi **trop complexes** pour des projets simples :

- ❌ Dépendances lourdes (Composer, frameworks)
- ❌ Courbe d'apprentissage importante
- ❌ Fonctionnalités souvent superflues pour 80% des projets
- ❌ Performance parfois décevante sur des cas simples

### La solution GABS

**GABS** est un moteur de gabarits qui tente de revenir à l'essentiel :

- ✅ **Un seul fichier** (zéro dépendance)
- ✅ **Syntaxe claire** (apprise en 15 minutes)
- ✅ **Ultra-rapide** (~10ms sans cache, ~2.5ms avec)
- ✅ **Sécurisé** (échappement auto, protection path-traversal)
- ✅ **Léger** (~1300 lignes ~65 Ko ; ~360 lignes ~16 Ko minifié)
- ✅ **Logic-Less** (calculs = PHP ; design = HTML/GABS)
- ✅ **Filtres** (formatage des variables dans les gabarits)

Si vous cherchez un moteur de gabarits pour un projet qui n'a pas besoin d'une **artillerie lourde**, mais plutôt d'une alternative légère, rapide, simple à installer et à utiliser, tout en offrant les principales fonctionalités nécessaires : **GABS est la bonne solution pour les bons projets !** 

---

## 📦 Installation

### Fichier unique (recommandé)

```bash
# Télécharger Gabs.php
wget https://raw.githubusercontent.com/fredomkb58/Gabs/main/Gabs.php
```

```php
<?php
require_once 'Gabs.php';
$gabs = new Gabs();
```

**C'est tout ! 🎉**

---

## 🚀 Démarrage rapide

**3 étapes simples :**

### 1. Données PHP *(`data.php`)*

Préparez un simple **tableau associatif PHP**, où **chaque clé** deviendra une balise dans **GABS**.

```php
<?php
$data = array(
    's_title'   => 'Mon Site',
    's_name'    => 'Alice',
    'b_premium' => true,
    'a_hobbies' => array('Lecture', 'Voyage', 'Code')
);
```

### 2. Gabarit GABS *(`template.gabs`)*

Dans le gabarit **GABS**, placez **les clés du tableau PHP** au bon endroit avec la bonne syntaxe (voir dans les chapitres suivants).

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>{s_title}</title>
</head>
<body>
    <h1>Bonjour {s_name} !</h1>

    {b_premium{
        <p class="premium">⭐ Membre Premium</p>
    }b_premium{
        <p>Membre Standard</p>
    }b_premium}

    <ul>
    {a_hobbies{
        <li>{v}</li>
    }a_hobbies}
    </ul>
</body>
</html>
```

### 3. Rendu PHP *(`index.php`)*

Dans votre **contrôleur PHP** (*`index.php`* par exemple), faites les inclusions de **GABS** et des **données PHP**, il suffit de créer **une instance de GABS** et de lancer le **rendu final HTML** avec la méthode publique *`get()`*… et c'est tout !

```php
<?php
require_once 'Gabs.php'; // inclusion du moteur GABS
require_once 'data.php'; // inclusion des données 

$gabs = new Gabs(); // création de l'instance de GABS
echo $gabs->get('template.gabs', $data); // lancement du rendu HTML 
```

**Résultat HTML :**

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Mon Site</title>
</head>
<body>
    <h1>Bonjour Alice !</h1>

    <p class="premium">⭐ Membre Premium</p>

    <ul>
        <li>Lecture</li>
        <li>Voyage</li>
        <li>Code</li>
    </ul>
</body>
</html>
```

---

## ✨ Fonctionnalités

### Core

- 🎨 **Gabarits** HTML simples et lisibles
- 🔄 **Variables** (texte, nombres, HTML)
- ⚖️ **Conditions** binaires (vrai/faux)
- 🔁 **Boucles** sur tableaux
- 📎 **Inclusions** statiques, modularité 
- 🔒 **Échappement** auto (protection XSS)
- ⚡ **Cache** intelligent (95% plus rapide)
- 🧹 **Purification** auto des caches

### Avancé

- 🔧 **Filtres** formatage des variables dans le gabarit
- 🔀 **Tri inverse** des tableaux
- 🎯 **Sélection d'items** (slicing, offset, limit)
- 📊 **Infos de boucles** (pagination, tri, stats)
- 🌍 **Variables globales** accessibles dans les boucles
- 🌐 **Inclusions dynamiques** (multi-langue, thèmes)
- 🎛️ **Configuration flexible** (granulaire)
- 🐛 **Mode debug** (inspection données)

---

## 📝 Syntaxe

### Variables *`{s_var}`*

**Données PHP :**
```php
$data = array(
    's_title' => 'Mon Produit',
    'n_price' => 29.99
);
```

**Gabarit GABS :**
```html
<h1>{s_title}</h1>
<p>Prix : {n_price} €</p>
```

**Résultat HTML :**
```html
<h1>Mon Produit</h1>
<p>Prix : 29.99 €</p>
```

#### Échappement

**Données sécurisées par défaut :**
```php
// Données PHP
's_name' => '<script>alert("XSS")</script>',
```
```html
<!-- Gabarit GABS -->
<p>{s_name}</p> 
```
```html
<!-- Résultat HTML -->
<p>&lt;script&gt;alert("XSS")&lt;/script&gt;</p> 
```

**HTML brut avec préfixe *`h_`* si nécessaire :**
```php
// Données PHP
'h_content' => '<strong>Important</strong>',  // Préfixe 'h_'
```
```html
<!-- Gabarit GABS -->
<p>{h_content} à réaliser…</p> 
```
```html
<!-- Résultat HTML -->
<p><strong>Important</strong> à réaliser…</p> 
```

**Données brutes forcées dans le gabarit *`|}`* :**
```php
// Données PHP
's_html' => '<em>95&nbsp;%</em>', // donnée protégée par défaut, sauf si balise '|}' dans le gabarit 
```
```html
<!-- Gabarit GABS -->
<p>Performance à {s_html|}</p>  <!-- fermeture balise '|}' final dans le template = donnée brute -->
```
```html
<!-- Résultat HTML -->
<p>Performance à <em>95&nbsp;%</em></p> 
```

---

### Conditions *`{b_{ … }b_{ … }b_}`*

**Données PHP :**
```php
'b_premium' => true,
'b_verified' => true,
'b_error' => false,
'b_active' => true,
```

**Syntaxe complète GABS :**
```html
{b_premium{
    <p>Contenu si VRAI</p>
}b_premium{
    <p>Contenu si FAUX</p>
}b_premium}
```

**Résultat HTML :**
```html
<p>Contenu si VRAI</p> <!-- b_premium = true -->
```

**Syntaxe courte GABS** *(toujours sur une seule ligne)* **:**
```html
<!-- Afficher uniquement si VRAI '{b_{[' -->
{b_verified{[ <span>✓ Vérifié</span> }b_verified}

<!-- Afficher uniquement si FAUX ']}b_}' -->
{b_error{ <span>❌ Erreur</span> ]}b_error}
```

**Résultat HTML :**
```html
<span>✓ Vérifié</span> <!-- b_verified = true -->
<span>❌ Erreur</span> <!-- b_error = false -->
```

**Exemple classes dynamiques :**
```html
<button class="{b_active{[ active }b_active}">
    {b_active{ Actif }b_active{ Inactif }b_active}
</button>
```

**Résultat HTML :**
```html
<button class=" active "> <!-- b_active = true -->
    Actif 
</button>
```

---

### Boucles *`{a_{ … }a_}`*

#### Tableaux associatifs

**Données PHP :**
```php
'a_users' => array(
    array('s_name' => 'Alice', 's_email' => 'alice@example.com', 'n_age' => 28),
    array('s_name' => 'Bob',   's_email' => 'bob@example.com',   'n_age' => 35)
)
```

**Gabarit GABS :**
```html
{a_users{
    <div class="user">
        <h3>{s_name}</h3>
        <p>{s_email}</p>
        <span>Âge : {n_age} ans</span>
    </div>
}a_users}
```

**Résultat HTML :**
```html
<div class="user">
    <h3>Alice</h3>
    <p>alice@example.com</p>
    <span>Âge : 28 ans</span>
</div>
<div class="user">
    <h3>Bob</h3>
    <p>bob@example.com</p>
    <span>Âge : 35 ans</span>
</div>
```

#### Tableaux indexés

**Balises spéciales disponibles :**
- `{v}` = Valeur (données scalaires)
- `{k}` = Index (commence à 0)
- `{c}` = Compteur (commence à 1)

**Données PHP :**
```php
'a_colors' => array('Rouge', 'Vert', 'Bleu')
```

**Gabarit GABS :**
```html
{a_colors{
    <li>#{c} - Index [{k}] : {v}</li>
}a_colors}
```

**Résultat HTML :**
```html
<li>#1 - Index [0] : Rouge</li>
<li>#2 - Index [1] : Vert</li>
<li>#3 - Index [2] : Bleu</li>
```

#### Sélection (Slicing) *`[début[ … ]nombre]`* 

**Les 5 premiers :**
```html 
<!-- Gabarit GABS -->
{a_products{[0[
    <div>{s_name}</div>
]5]}a_products}
```

**Les 5 derniers :**
```html 
<!-- Gabarit GABS -->
{a_products{[-5[
    <div>{s_name}</div>
]0]}a_products}
```

**Pagination (10 par page) :**
```php 
// Données PHP 
$page   = 2;
$offset = ($page - 1) * 10;  // = 10
```
```html 
<!-- Gabarit GABS  -->
{a_products{[<?= $offset ?>[
    <div>{s_name}</div>
]10]}a_products}
```

#### Tri inverse *`!`*

```html 
<!-- Gabarit GABS -->
{a_numbers{!
    <span>{v}</span>
}a_numbers}
```
```html 
<!-- Gabarit GABS -->
{a_products{[-5[!
    <div>{s_name}</div>
]0]}a_products}
```

#### Infos de boucles (métadonnées)

**Syntaxe des balises :**
`{a_nomBoucle_numeroInstance_codeInfo}` **=** `{a_array_1_n}` **ou** `{a_tableau_1_t}` 

**Il y a 6 informations** à afficher selon le suffixe utilisé :
- `_b` = **begin** (debut) : le numéro de début de l'affichage du tableau
- `_f` = **finish** (fin) : le numéro de fin de l'affichage du tableau
- `_n` = **number** (nombre) : le nombre d'éléments affichés (tenant compte des sélections)
- `_t` = **total** : le nombre total d'éléments que contient le tableau
- `_p` = **page** : le numéro de page correspondant à la sélection affichée 
- `_s` = **sort** (tri) : indicateur de tri, ascendant `/\` (normal) ou descendant `\/` (inverse)

**Exemple gabarit GABS :**
```html
{a_products{[10[
    <div>{s_name} - {n_price}€</div>
]10]}a_products}

<p>
    Affichage : {a_products_1_b} à {a_products_1_f}
    sur {a_products_1_t} (Page {a_products_1_p})
</p>
```

**Résultat HTML :**
```html
Affichage : 11 à 20 sur 150 (Page 2)
```

#### Variables globales dans les boucles *`_g`*

Par défaut, toutes les variables du niveau racine sont accessibles à l'intérieur des boucles. Ceci étant, il est **fortement recommandé** d'adopter une convention simple et très utile : **ajouter le suffixe *`_g`* aux clés**, dans le tableau de données principal (par exemple : *`s_variable_globale_g`*). 

Cette convention du suffixe *`_g`* est une information active dans **GABS**, elle produit plusieurs effets bénéfiques : 
- ✅ **limite les traitements =** résultats plus rapides et efficaces 
- ✅ **évite les collusions =** permet la maîtrise de l'affichage des données 
- ✅ **auto-documentation =** facilite grandement le travail sur les gabarits

**Données PHP :**
```php
$data = array(
    's_devise_g'   => '€',            // ← suffixe _g = accessible partout
    's_boutique_g' => 'Ma Boutique',  // ← suffixe _g = accessible partout
    'a_products'   => array(
        array('s_name' => 'Laptop', 'n_price' => 899),
        array('s_name' => 'Souris', 'n_price' => 29)
    )
);
```

**Gabarit GABS :**
```html
{a_products{
    <p>{s_name} — {n_price} {s_devise_g} · {s_boutique_g}</p>
}a_products}
```

**Résultat HTML :**
```html
<p>Laptop — 899 € · Ma Boutique</p>
<p>Souris — 29 € · Ma Boutique</p>
```

> Le suffixe `_g` se combine naturellement avec les préfixes de type : `s_site_g`, `n_tva_g`, `h_cdn_g`, etc.
> Un chapitre dédié dans la documentation complète détaille toutes les options disponibles.

---

### Inclusions

#### Statiques

**Gabarit GABS :**
```html
{includes/header.gabs}
{includes/menu.gabs}
```

**Protection path-traversal :**
```html
{includes/../../etc/passwd}  <!-- ❌ Bloqué ! -->
```

#### Dynamiques ⭐

**Multi-langue**
```php
// Données PHP
's_lang' => 'fr'
```
```html 
<!-- Gabarit GABS -->
{includes/header_{s_lang}.gabs}
```
```html 
<!-- Résultat GABS -->
{includes/header_fr.gabs}
```
**Thèmes**
```php 
// Données PHP 
's_theme' => 'dark'
```
```html 
<!-- Gabarit GABS -->
{includes/styles/{s_theme}/main.gabs}
```
```html
<!-- Résultat GABS -->
{includes/styles/dark/main.gabs}
```

---

## 🔧 Filtres ⭐

Les filtres permettent de **transformer une variable directement dans le gabarit**, sans toucher aux données PHP. C'est le complément naturel de la **philosophie "Logic-Less"** : le PHP prépare les données brutes, les filtres gèrent leur présentation.

### Syntaxe GABS

```html
<!-- Filtre simple -->
{s_name|f_upper}

<!-- Filtres enchaînés (appliqués de gauche à droite) -->
{s_name|f_trim|f_title}

<!-- Filtre + donnée brute (pipe final = pas d'échappement) -->
{h_bio|f_nl2br|}

<!-- Sans filtre, donnée brute (comportement inchangé) -->
{h_content|}
```

### Mise en place

**Structure recommandée :**

```
libs/
├── Gabs.php           ← moteur (ne pas modifier)
├── funcs_gabs.php     ← filtres standards GABS (ne pas modifier)
└── funcs_user.php     ← vos filtres personnalisés
```

**Vos filtres métier** *(`funcs_user.php`)* **:**

```php
<?php
$aFuncsUser = array();

// Exemples à personnaliser
$aFuncsUser['f_prix']    = function($v) { return number_format((float)$v, 2, ',', ' ').' €'; };
$aFuncsUser['f_extrait'] = function($v) { return mb_substr(strip_tags($v), 0, 150).'…'; };
$aFuncsUser['f_ref']     = function($v) { return strtoupper(str_replace(' ', '-', trim($v))); };
```

**Dans votre contrôleur PHP** *(`index.php` par exemple)* **:**

```php
require_once 'libs/Gabs.php'; // on charge le moteur de GABS
require_once 'libs/funcs_gabs.php';  // librairie de filtres GABS standard 
require_once 'libs/funcs_user.php';  // vos filtres (écrasent les standards si même nom)

$aFuncs = array_merge($aFuncsGabs, $aFuncsUser); // on fusionne les deux librairies 

$gabs = new Gabs(); // on crée une instance de GABS 
echo $gabs->get('template.gabs', $data, $aFuncs); // on lance le rendu HTML 
```

> Les filtres sont **entièrement optionnels** — si `$aFuncs` n'est pas fourni, **GABS** fonctionne exactement comme avant. Un filtre inconnu est **ignoré silencieusement** en phase de production (visible dans le cede source HTML en phase de développement).

### Filtres disponibles *(`funcs_gabs.php`)*

Voici **un échantillon des principaux filtres disponibles** dans la librairie *`funcs_gabs.php`* (plus de 80 filtres).

**Chaînes de caractères**

| Filtre | Description | Exemple |
|--------|-------------|---------|
| `f_upper` | Tout en majuscules | `hello` → `HELLO` |
| `f_lower` | Tout en minuscules | `HELLO` → `hello` |
| `f_ucfirst` | Première lettre en majuscule | `alice` → `Alice` |
| `f_ucwords` | Chaque mot en majuscule | `alice dupont` → `Alice Dupont` |
| `f_trim` | Supprime espaces début/fin | `  hello  ` → `hello` |
| `f_title` | Ucfirst + minuscules + trim | `  ALICE  ` → `Alice` |
| `f_name` | Majuscules + trim | `  alice  ` → `ALICE` |
| `f_slug` | Convertit en slug URL | `Mon Titre !` → `mon-titre` |
| `f_extract` | 200 premiers caractères sans HTML | `<p>Long texte…</p>` → `Long texte…` |
| `f_strip` | Supprime les balises HTML | `<b>Texte</b>` → `Texte` |
| `f_trunc_50` | Tronque à 50 caractères | — |
| `f_trunc_100` | Tronque à 100 caractères | — |

**Nombres**

| Filtre | Description | Exemple |
|--------|-------------|---------|
| `f_round_0` | Arrondi à l'entier | `3.7` → `4` |
| `f_round_1` | Arrondi à 1 décimale | `3.75` → `3.8` |
| `f_round_2` | Arrondi à 2 décimales | `3.756` → `3.76` |
| `f_ceil` | Arrondi supérieur | `3.1` → `4` |
| `f_floor` | Arrondi inférieur | `3.9` → `3` |
| `f_abs` | Valeur absolue | `-5` → `5` |
| `f_num_2` | Format FR 2 décimales | `1234.5` → `1 234,50` |
| `f_num_dot_2` | Format US 2 décimales | `1234.5` → `1,234.50` |
| `f_eur` | Montant en euros | `1234.5` → `1 234,50 €` |
| `f_usd` | Montant en dollars | `1234.5` → `$1,234.50` |
| `f_pct` | Pourcentage | `12.5` → `12,5 %` |
| `f_pct_int` | Pourcentage entier | `12.5` → `13 %` |

**Dates** *(depuis un timestamp Unix)*

| Filtre | Description | Exemple |
|--------|-------------|---------|
| `f_date` | Format FR | `→ 31/12/2026` |
| `f_date_time` | Format FR avec heure | `→ 31/12/2026 23:59` |
| `f_date_us` | Format US | `→ 12/31/2026` |
| `f_time` | Heure seule | `→ 23:59` |
| `f_year` | Année seule | `→ 2026` |
| `f_age` | Âge en années | `→ 35 ans` |

**Divers**

| Filtre | Description | Exemple |
|--------|-------------|---------|
| `f_bool_yn_fr` | Booléen en français | `1` → `Oui` / `0` → `Non` |
| `f_bool_yn_en` | Booléen en anglais | `1` → `Yes` / `0` → `No` |
| `f_bool_ico` | Booléen en icône | `1` → `✅` / `0` → `❌` |
| `f_mask_email` | Masque l'email | `alice@ex.com` → `al***@ex.com` |
| `f_mask_phone` | Masque le téléphone | `→ 06 ** ** ** 78` |
| `f_initials` | Initiales | `Alice Dupont` → `A.D.` |

### Exemple concret

```php 
// Données PHP 
$data = array(
    's_name'    => '  alice dupont  ',
    's_bio'     => '<p>Développeuse passionnée depuis 10 ans.</p>',
    'n_price'   => 1234.5,
    'n_ts_born' => mktime(0, 0, 0, 6, 15, 1990),
    's_email'   => 'alice.dupont@example.com',
);
```

```html 
<!-- Gabarit GABS  -->
<h1>{s_name|f_trim|f_title}</h1>
<p>{s_bio|f_strip|f_extract}</p>
<p>Prix : {n_price|f_eur|}</p>
<p>Âge : {n_ts_born|f_age}</p>
<p>Contact : {s_email|f_mask_email}</p>
```

```html 
<!-- Résultat HTML  -->
<h1>Alice Dupont</h1>
<p>Développeuse passionnée depuis 10 ans.</p>
<p>Prix : 1 234,50 €</p>
<p>Âge : 35 ans</p>
<p>ontact : al***@example.com</p>
```

### Convention de nommage des filtres

Il est **fortement recommandé** de préfixer vos filtres avec *`f_`* :

```php
// ✅ Recommandé — cohérent avec les conventions GABS
$aFuncsUser['f_mon_filtre'] = function($v) { return strtoupper($v); };

// ⚠️ Fonctionne, mais déconseillé
$aFuncsUser['mon_filtre'] = function($v) { return strtoupper($v); };
```

> Les filtres inconnus sont **ignorés silencieusement** : la valeur s'affiche telle quelle (en phase de développement, en mode "débug", les filtres qui ont échoué sont visibles dans le code source HTML).

---

## ⚙️ Configuration

### Mode développement

```php
$gabs->conf(array(
    'cach' => false,    // Cache désactivé
    'dbug' => true,     // Debug activé
    'tpls' => 'views'   // Dossier templates
));
```

### Mode production

```php
$gabs->conf(array(
    'cach' => true,     // Cache activé
    'dbug' => false,    // Debug désactivé
    'pure' => true,     // Purification auto du cache
    'fold' => 'cache',  // Dossier cache
    'tpls' => 'views'   // Dossier templates
));
```

> La liste complète des options de configuration est détaillée dans la documentation.

---

## ⚡ Performance

### Benchmarks

| Opération | Temps | Notes |
|-----------|-------|-------|
| Parse simple | ~3ms | ✅ Très rapide |
| Parse complexe | ~10ms | ✅ Performant |
| Avec ~12 filtres | ~6–18ms | ✅ Raisonnable |
| Avec cache | ~2.5ms | ✅ Gain important |

> Les filtres sont appliqués uniquement à la **première génération** — avec le cache actif, leur coût devient négligeable.

### Cache intelligent

- **Automatique** : md5 du template + md5 des données
- **Gain significatif** : parse → cache = jusqu'à 80%
- **Purification auto** : garde les fichiers les plus récents

---

## 🔒 Sécurité

### Échappement XSS

**Auto par défaut :**
```php
's_input' => '<script>alert("XSS")</script>'
// → &lt;script&gt;... ✅
```

**Filtres et échappement :**

Les filtres sont appliqués **avant** l'échappement automatique. Pour afficher une valeur filtrée **sans échappement** (contenu HTML), utiliser le pipe final *`|}`* :

```html
{h_bio|f_nl2br|}   <!-- filtre nl2br appliqué, HTML préservé -->
```

### Path traversal

**Bloqué automatiquement :**
```html
{includes/../../etc/passwd}  <!-- ❌ -->
{includes/../config.php}     <!-- ❌ -->
```

**Méthode :** `realpath()` + vérification stricte

### Nettoyage du code HTML final 

**GABS** effectue un nettoyage du code HTML généré **avant l'affichage final** : il cherche toutes les éventuelles **balises orphélines** encore présentes pour les commenter en HTML, afin qu'elles puissent être répérées en phase de développement (il s'agit d'une fonctionnalité configurable). 

---

## 💡 Exemples complets

### Blog

```html
<article>
    <h1>{s_title|f_title}</h1>

    <div class="meta">
        <span>Par {s_author|f_ucwords}</span>
        <time>{n_ts_date|f_date}</time>
        {b_featured{[ <span class="badge">⭐ À la une</span> }b_featured}
    </div>

    <div class="content">
        {h_content|}
    </div>

    <div class="tags">
        {a_tags{ <a href="/tag/{v|f_slug}" class="tag">{v}</a> }a_tags}
    </div>
</article>

<section class="comments">
    <h2>{a_comments_1_t} commentaire(s)</h2>

    {a_comments{
        <div class="comment">
            <strong>{s_author|f_ucwords}</strong>
            <time>{n_ts_date|f_elapsed}</time>
            <p>{s_text}</p>
        </div>
    }a_comments}
</section>
```

### E-commerce (Pagination)

```html
<div class="products">
    {a_products{[<?= ($page-1)*10 ?>[
        <div class="product {b_promo{[ highlight }b_promo}">
            <h3>{s_name|f_title}</h3>
            <p class="price">{n_price|f_eur|}</p>
            {b_stock{
                <button>Ajouter</button>
            }b_stock{
                <span class="out">Rupture</span>
            }b_stock}
            {b_promo{[ <span class="badge">Promo !</span> }b_promo}
        </div>
    ]10]}a_products}
</div>

<div class="pagination">
    <p>Produits {a_products_1_b} à {a_products_1_f} sur {a_products_1_t}</p>
</div>
```

### Multi-langue

```html
<!DOCTYPE html>
<html lang="{s_lang}">
<head>
    {includes/head_{s_lang}.gabs}
</head>
<body>
    {includes/menu_{s_lang}.gabs}

    <h1>{s_welcome}</h1>

    {includes/footer_{s_lang}.gabs}
</body>
</html>
```

**Données :**
```php
$lang = $_GET['lang'] ?? 'fr';

$i18n = array(
    'fr' => array('s_welcome' => 'Bienvenue !'),
    'en' => array('s_welcome' => 'Welcome !')
);

$data = array_merge(
    array('s_lang' => $lang),
    $i18n[$lang]
);
```

---

## 🏷️ Conventions de nommage

### Préfixes recommandés et sécurité

| Préfixe | Type | Exemple | Auto-Protection |
|---------|------|---------|-----------------|
| `s_` | String | `s_name` | ✅ échappement |
| `c_` | Code | `c_href` | ✅ échappement |
| `n_` | Number | `n_price` | ✅ échappement |
| `b_` | Boolean | `b_active` | ❌ donnée brute |
| `h_` | HTML | `h_content` | ❌ donnée brute |
| `a_` | Array | `a_users` | ✅ récursivité |

**Avantages :**
- ✅ Lecture rapide du type et de la sécurité associée
- ✅ Auto-documentation du tableau de données
- ✅ Évite les confusions entre types

### Suffixe global *`_g`*

Ajouter *`_g`* en fin de clé, cela indique à **GABS** que la variable devient globale et qu'elle doit être accessible à l'intérieur de toutes les boucles :

```php
's_currency_g' => '€'    // disponible dans {a_products{ ... }a_products}
'h_cdn_g'      => '...'  // disponible dans {a_images{ ... }a_images}
```

---

## 🤝 Contribuer

**GABS est open-source !**

**Vous pouvez :**
- 🐛 Reporter des bugs
- 💡 Proposer des features
- 📝 Améliorer la doc
- ⭐ Star sur GitHub !

---

## 📄 Licence

**GABS est gratuit et open-source !**

**MIT License** - Copyright (c) 2026 FredoMkb

---

## 🙏 Crédits

**Auteur :** FredoMkb

**Réalisé avec l'aide de :**
- 🤖 Claude-IA (Anthropic) — architecture, débogage, documentation 
- 🤖 Gemini-IA (Google) — analyses, suggestions, exemples 
- 🤖 Divers assistants IA — informations, recherches, brainstorming 
- 🌐 StackOverflow, php.net, MDN, regex101 et la communauté PHP 

---

<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logique sans bruit && design sans echo }**

**Moteur de gabarits PHP/HTML**

**simple • léger • rapide • sécurisé • logic-less**

[GitHub](https://github.com/fredomkb58/Gabs)

**Made with ❤️ from France 🇫🇷 for World 🌎**

</div>

---
