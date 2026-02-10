<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logique sans bruit && design sans echo }**

**Moteur de gabarits PHP/HTML**

**simple • léger • rapide • sécurisé • logic-less**

*Version v0118*

[![PHP Version](https://img.shields.io/badge/PHP-5.6%2B-blue)](https://php.net)
[![Version](https://img.shields.io/badge/version-0118-teal)](https://github.com/fredomkb58/gabs)
[![License](https://img.shields.io/badge/license-MIT-orange)](LICENSE)

</div>

---

## 📖 Table des matières

- [Pourquoi GABS ?](#-pourquoi-gabs-)
- [Installation](#-installation)
- [Démarrage rapide](#-démarrage-rapide)
- [Fonctionnalités](#-fonctionnalités)  
- [Syntaxe](#-syntaxe)
- [Configuration](#%EF%B8%8F-configuration)
- [Performance](#-performance)
- [Sécurité](#-sécurité)
- [Exemples complets](#-exemples-complets)
- [Conventions](#-conventions-de-nommage)
- [Contribuer](#-contribuer)

---

## 🎯 Pourquoi GABS ?

### Le problème

Les moteurs de templates les plus connus aujourd'hui sont **puissants** mais souvent **trop complexes** pour des projets simples :
- ❌ Dépendances lourdes (Composer, frameworks)
- ❌ Courbe d'apprentissage importante
- ❌ Fonctionnalités souvent superflues pour 80% des projets
- ❌ Performance parfois décevante sur des cas simples

### La solution GABS

**GABS** est un moteur de templates qui revient à l'essentiel :
- ✅ **Un seul fichier** (zéro dépendance)
- ✅ **Syntaxe claire** (apprise en 15 minutes)
- ✅ **Ultra-rapide** (~10ms sans cache, ~2.5ms avec)
- ✅ **Sécurisé** (échappement auto, protection path-traversal)
- ✅ **Léger** (< 1200 lignes, ~30 Ko)
- ✅ **Logic-Less** (calculs = PHP ; design = HTML/GABS)

**GABS = La bonne solution pour les bons projets** 🎯

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

### 1. Template (`template.gabs`)

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

### 2. Données (PHP)

```php
<?php
require_once 'Gabs.php';
$gabs = new Gabs();

$data = array(
    's_title'   => 'Mon Site',
    's_name'    => 'Alice',
    'b_premium' => true,
    'a_hobbies' => array('Lecture', 'Voyage', 'Code')
);
```

### 3. Rendu

```php
echo $gabs->get('template.gabs', $data);
```

**Résultat :**

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

- 🎨 Templates HTML simples et lisibles
- 🔄 Variables (texte, nombres, HTML)
- ⚖️ Conditions binaires (vrai/faux)
- 🔁 Boucles sur tableaux
- 📎 Inclusions statiques et dynamiques
- 🔒 Échappement auto (protection XSS)
- ⚡ Cache intelligent (95% plus rapide)
- 🧹 Purification auto des caches

### Avancé

- 📊 **Infos de boucles** (pagination, tri, stats)
- 🎯 **Sélection d'items** (slicing, offset, limit)
- 🔀 **Tri inverse** des tableaux
- 🌍 **Variables globales** accessibles dans les boucles
- 🌐 **Inclusions dynamiques** (multi-langue, thèmes)
- 🎛️ **Configuration flexible** (granulaire)
- 🐛 **Mode debug** (inspection données)

---

## 📝 Syntaxe

### Variables

```html
<h1>{s_title}</h1>
<p>Prix : {n_price}€</p>
```

**Données :**
```php
$data = array(
    's_title' => 'Mon Produit',
    'n_price' => 29.99
);
```

#### Échappement

**Par défaut (sécurisé) :**
```php
's_name' => '<script>alert("XSS")</script>'
// Résultat : &lt;script&gt;alert("XSS")&lt;/script&gt; ✅
```

**HTML brut (si nécessaire) :**
```php
'h_content' => '<strong>Important</strong>'  // Préfixe h_
// Ou : {s_content|}  (pipe dans le template)
```

---

### Conditions

**Syntaxe complète :**
```html
{b_premium{
    <p>Contenu si VRAI</p>
}b_premium{
    <p>Contenu si FAUX</p>
}b_premium}
```

**Syntaxe courte** *(sur une seule ligne)* **:**
```html
<!-- Afficher uniquement si VRAI -->
{b_verified{[ <span>✓ Vérifié</span> }b_verified}

<!-- Afficher uniquement si FAUX -->
{b_error{ <span>❌ Erreur</span> ]}b_error}
```

**Classes dynamiques :**
```html
<button class="{b_active{[ active }b_active}">
    {b_active{ Actif }b_active{ Inactif }b_active}
</button>
```

---

### Boucles

#### Tableaux associatifs

```html
{a_users{
    <div class="user">
        <h3>{s_name}</h3>
        <p>{s_email}</p>
        <span>Âge : {n_age} ans</span>
    </div>
}a_users}
```

**Données :**
```php
'a_users' => array(
    array('s_name' => 'Alice', 's_email' => 'alice@example.com', 'n_age' => 28),
    array('s_name' => 'Bob',   's_email' => 'bob@example.com',   'n_age' => 35)
)
```

#### Tableaux indexés

**Balises spéciales :**
- `{v}` = Valeur
- `{k}` = Index
- `{c}` = Compteur (commence à 1)

```html
{a_colors{
    <li>#{c} - Index [{k}] : {v}</li>
}a_colors}
```

**Données :**
```php
'a_colors' => array('Rouge', 'Vert', 'Bleu')
```

**Résultat :**
```html
<li>#1 - Index [0] : Rouge</li>
<li>#2 - Index [1] : Vert</li>
<li>#3 - Index [2] : Bleu</li>
```

#### Sélection (Slicing)

**Les 5 premiers :**
```html
{a_products{[0[
    <div>{s_name}</div>
]5]}a_products}
```

**Les 5 derniers :**
```html
{a_products{[-5[
    <div>{s_name}</div>
]0]}a_products}
```

**Pagination (10 par page) :**
```php
$page   = 2;
$offset = ($page - 1) * 10;  // = 10
```
```html
{a_products{[<?= $offset ?>[
    <div>{s_name}</div>
]10]}a_products}
```

#### Tri inverse

```html
{a_numbers{!
    <span>{v}</span>
}a_numbers}
```

#### Infos de boucles ⭐

**Balises disponibles :**
- `{a_array_1_b}` = Begin (début)
- `{a_array_1_f}` = Finish (fin)
- `{a_array_1_n}` = Number (nombre affiché)
- `{a_array_1_t}` = Total
- `{a_array_1_p}` = Page
- `{a_array_1_s}` = Sort (↓ ou ↑)

**Exemple :**
```html
{a_products{[10[
    <div>{s_name} - {n_price}€</div>
]10]}a_products}

<p>
    Affichage : {a_products_1_b} à {a_products_1_f}
    sur {a_products_1_t} (Page {a_products_1_p})
</p>
```

**Résultat :**
```
Affichage : 11 à 20 sur 150 (Page 2)
```

#### Variables globales dans les boucles

Par défaut, les variables du niveau racine ne sont pas accessibles à l'intérieur des boucles. Pour les rendre disponibles dans tous les items, il suffit d'ajouter le suffixe **`_g`** :

```php
$data = array(
    's_devise_g'  => '€',             // ← suffixe _g = accessible partout
    's_boutique_g' => 'Ma Boutique',  // ← suffixe _g = accessible partout
    'a_products' => array(
        array('s_name' => 'Laptop', 'n_price' => 899),
        array('s_name' => 'Souris', 'n_price' => 29)
    )
);
```

```html
{a_products{
    <p>{s_name} — {n_price}{s_devise_g} · {s_boutique_g}</p>
}a_products}
```

**Résultat :**
```html
<p>Laptop — 899€ · Ma Boutique</p>
<p>Souris — 29€ · Ma Boutique</p>
```

> Le suffixe `_g` se combine naturellement avec les préfixes de type : `s_site_g`, `n_tva_g`, `u_cdn_g`, etc.
> Un chapitre dédié dans la documentation complète détaille toutes les options disponibles.

---

### Inclusions

#### Statiques

```html
{includes/header.gabs}
{includes/menu.gabs}
```

**Protection path-traversal :**
```html
{includes/../../etc/passwd}  <!-- ❌ Bloqué ! -->
```

#### Dynamiques ⭐

**Multi-langue :**
```php
's_lang' => 'fr'
```
```html
{includes/header_{s_lang}.gabs}
<!-- Devient : {includes/header_fr.gabs} -->
```

**Thèmes :**
```php
's_theme' => 'dark'
```
```html
{includes/styles/{s_theme}/main.gabs}
<!-- Devient : {includes/styles/dark/main.gabs} -->
```

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

| Opération | Temps | Autres solutions |
|-----------|-------|-----------------|
| Parse simple | ~3ms | ✅ Très rapide |
| Parse complexe | ~10ms | ✅ Performant |
| Avec cache | ~2.5ms | ✅ Gain important |

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

### Path traversal

**Bloqué automatiquement :**
```html
{includes/../../etc/passwd}  <!-- ❌ -->
{includes/../config.php}     <!-- ❌ -->
```

**Méthode :** `realpath()` + vérification stricte

---

## 💡 Exemples complets

### Blog

```html
<article>
    <h1>{s_title}</h1>

    <div class="meta">
        <span>Par {s_author}</span>
        <time>{s_date}</time>
        {b_featured{[ <span class="badge">⭐ À la une</span> }b_featured}
    </div>

    <div class="content">
        {h_content|}
    </div>

    <div class="tags">
        {a_tags{ <a href="/tag/{v}" class="tag">{v}</a> }a_tags}
    </div>
</article>

<section class="comments">
    <h2>{a_comments_1_t} commentaire(s)</h2>

    {a_comments{
        <div class="comment">
            <strong>{s_author}</strong>
            <time>{s_date}</time>
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
            <h3>{s_name}</h3>
            <p class="price">{n_price}€</p>
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

### Suffixe global `_g`

Ajouter `_g` en fin de clé rend une variable scalaire accessible dans toutes les boucles :

```php
's_currency_g' => '€'     // disponible dans {a_products{ ... }a_products}
'u_cdn_g'      => '...'   // disponible dans {a_images{ ... }a_images}
```

---

## 📄 Licence

**GABS est gratuit et open-source !**

**MIT License** - Copyright (c) 2026 FredoMkb

---

## 🙏 Crédits

**Auteur :** FredoMkb

**Réalisé avec l'aide de :**
- 🤖 Claude IA (Anthropic) — architecture, débogage, documentation
- 🤖 Divers assistants IA — recherche et brainstorming
- 🌐 StackOverflow, php.net, MDN, regex101 and the PHP community

---

<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logique sans bruit && design sans echo }**

**Moteur de gabarits PHP/HTML**

**simple • léger • rapide • sécurisé • logic-less**

[GitHub](https://github.com/fredomkb58/Gabs)

**Made with ❤️ from France 🇫🇷 for World 🌎**

</div>

---
