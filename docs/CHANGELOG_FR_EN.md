<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logique sans bruit && design sans echo }**

*Version v0120*

</div>

---


# Changelog 

> **{ logique sans bruit && design sans echo }**
> **{ logic without noise && design without echo }**

Toutes les modifications notables de ce projet sont documentées dans ce fichier.
All notable changes to this project are documented in this file.

---

## [v0.120] — 2026-02 ⭐

### Ajouté / Added
- 🔧 **Système de filtres** — transformation des variables directement dans les gabarits
- 🔧 **Filter system** — transform variables directly in templates
- 📄 **`funcs_gabs.php`** — librairie de 80+ filtres standards (chaînes, nombres, dates, divers)
- 📄 **`funcs_gabs.php`** — library of 80+ standard filters (strings, numbers, dates, misc)
- 📄 **`funcs_user.php`** — espace dédié aux filtres personnalisés utilisateur
- 📄 **`funcs_user.php`** — dedicated space for user custom filters
- ✅ Enchaînement de filtres : `{s_name|f_trim|f_title}`
- ✅ Filter chaining: `{s_name|f_trim|f_title}`
- ✅ Compatibilité filtre + donnée brute : `{h_bio|f_nl2br|}`
- ✅ Filter + raw output compatibility: `{h_bio|f_nl2br|}`

### Corrigé / Fixed
- 🐛 Bug `return` dans la boucle `foreach` de traitement des filtres (v0.119)
- 🐛 Bug `return` inside `foreach` loop in filter processing (v0.119)
- 🐛 Gestion silencieuse des filtres inconnus (ignorés sans erreur)
- 🐛 Silent handling of unknown filters (ignored without error)

### Modifié / Changed
- 📝 Masque `hide` mis à jour pour inclure la syntaxe des filtres
- 📝 `hide` mask updated to include filter syntax
- 📝 README_FR.md et README.md mis à jour avec documentation complète des filtres
- 📝 README_FR.md and README.md updated with complete filter documentation

---

## [v0.118] — 2026-02 🚀 *(première version publique / first public release)*

### Ajouté / Added
- 🌐 Publication open-source sur GitHub
- 🌐 Open-source publication on GitHub
- 📄 `README.md` (EN) et `README_FR.md` (FR)
- 📄 `Gabs.min.php` — version minifiée (~360 lignes, ~16 Ko)
- 📄 `Gabs.min.php` — minified version (~360 lines, ~16 KB)
- ✅ Version stable et testée, compatible PHP 5.6+
- ✅ Stable and tested version, compatible with PHP 5.6+

---

## [v0.116] — 2026-02

### Ajouté / Added
- 🌍 **Variables globales dans les boucles** via suffixe `_g`
- 🌍 **Global variables in loops** via `_g` suffix
- ✅ Limitation du scope aux variables marquées `_g` (performance + clarté)
- ✅ Scope limited to `_g`-marked variables (performance + clarity)

### Modifié / Changed
- ⚡ Optimisation du tri des données (`uksort` : arrays → booleans → scalaires)
- ⚡ Data sort optimization (`uksort`: arrays → booleans → scalars)

---

## [v0.114] — 2026-01

### Ajouté / Added
- 📊 **Infos de boucles** (métadonnées) : `_b`, `_f`, `_n`, `_t`, `_p`, `_s`
- 📊 **Loop info** (metadata): `_b`, `_f`, `_n`, `_t`, `_p`, `_s`
- ✅ Support multi-instances : `{a_products_1_t}`, `{a_products_2_t}`…
- ✅ Multi-instance support: `{a_products_1_t}`, `{a_products_2_t}`…

---

## [v0.112] — 2026-01

### Ajouté / Added
- 🔀 **Tri inverse des tableaux** avec opérateur `!`
- 🔀 **Reverse sort of arrays** with `!` operator
- 🎯 **Sélection (slicing)** : `{a_array{[offset[ … ]count]}`
- 🎯 **Selection (slicing)**: `{a_array{[offset[ … ]count]}`
- ✅ Combinaison tri + sélection : `{a_products{[-5[! … ]0]}`
- ✅ Sort + selection combination: `{a_products{[-5[! … ]0]}`

---

## [v0.110] — 2025-12

### Ajouté / Added
- 🌐 **Inclusions dynamiques** (variables dans les chemins d'inclusion)
- 🌐 **Dynamic includes** (variables in include paths)
- ✅ Multi-langue : `{includes/header_{s_lang}.gabs}`
- ✅ Multi-language: `{includes/header_{s_lang}.gabs}`
- ✅ Thèmes : `{includes/styles/{s_theme}/main.gabs}`
- ✅ Themes: `{includes/styles/{s_theme}/main.gabs}`

---

## [v0.100] — 2025-12 *(refonte majeure / major rewrite)*

### Ajouté / Added
- 🏗️ Réécriture complète de l'architecture interne
- 🏗️ Complete rewrite of internal architecture
- ⚙️ **Méthode `conf()`** — configuration granulaire de toutes les fonctionnalités
- ⚙️ **`conf()` method** — granular configuration of all features
- 🐛 **Mode debug** `{_}` et `{_|}` — inspection des données et du gabarit
- 🐛 **Debug mode** `{_}` and `{_|}` — data and template inspection
- 🧹 **Purification automatique** du cache
- 🧹 **Automatic cache purification**
- 🔒 **Nettoyage des balises orphelines** (commentaires HTML)
- 🔒 **Orphan tag cleanup** (HTML comments)

---

## [v0.08x] — 2025-11

### Ajouté / Added
- ⚡ **Système de cache** intelligent (md5 template + md5 données)
- ⚡ **Smart cache system** (md5 template + md5 data)
- 🔒 **Protection path-traversal** (`realpath()` + vérification stricte)
- 🔒 **Path-traversal protection** (`realpath()` + strict verification)
- 📎 Inclusions statiques de fichiers `.gabs`
- 📎 Static `.gabs` file includes

---

## [v0.06x] — 2025-10

### Ajouté / Added
- 🔁 **Boucles** sur tableaux associatifs et indexés
- 🔁 **Loops** on associative and indexed arrays
- ✅ Balises spéciales dans les boucles : `{v}`, `{k}`, `{c}`
- ✅ Special tags in loops: `{v}`, `{k}`, `{c}`

---

## [v0.04x] — 2025-09

### Ajouté / Added
- ⚖️ **Conditions binaires** complètes et courtes
- ⚖️ **Binary conditions** full and short syntax
- ✅ Syntaxe courte VRAI `{b_{[` et FAUX `]}b_}`
- ✅ Short TRUE `{b_{[` and FALSE `]}b_}` syntax

---

## [v0.02x] — 2025-08 *(proof of concept)*

### Ajouté / Added
- 🔄 **Variables** avec préfixes typés (`s_`, `n_`, `c_`, `h_`)
- 🔄 **Variables** with typed prefixes (`s_`, `n_`, `c_`, `h_`)
- 🔒 **Échappement automatique** XSS (`htmlspecialchars`)
- 🔒 **Automatic XSS escaping** (`htmlspecialchars`)
- ✅ Données brutes avec préfixe `h_` ou pipe final `|}`
- ✅ Raw data with `h_` prefix or trailing pipe `|}`

---

<div align="center">

**{ logique sans bruit && design sans echo }**
**{ logic without noise && design without echo }**

[GitHub](https://github.com/fredomkb58/Gabs)

**Made with ❤️ from France 🇫🇷 for World 🌎**

</div>
