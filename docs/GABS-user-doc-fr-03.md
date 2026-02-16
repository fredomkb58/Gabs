<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logique sans bruit && design sans echo }**

*Version v0120*

</div>

---

# Documentation Utilisateur - Niveau 3

**Cas avancé — code direct, peu d'explications. À vous de jouer.**

> Ce document suppose que vous maîtrisez les Niveaux 1 et 2. Les concepts de base ne sont pas rappelés.

---

## 📖 Table des matières

- [Cas 3 — Site multi-langue](#-cas-3--site-multi-langue)
  - [Structure du projet](#structure-du-projet)
  - [Les données](#les-données)
  - [Les gabarits de langue](#les-gabarits-de-langue)
  - [Les gabarits de mise en page](#les-gabarits-de-mise-en-page)
  - [Les gabarits de pages](#les-gabarits-de-pages)
  - [Le contrôleur](#le-contrôleur)
  - [Bilan](#bilan)

---

## 🌍 Cas 3 — Site multi-langue

Un site vitrine trilingue (FR / EN / ES) avec détection automatique de la langue, gabarits de traduction, navigation localisée et URLs propres.

---

### Structure du projet

```
mon-site/
├── Gabs.php
├── funcs_gabs.php
├── index.php                   ← contrôleur principal
├── data.php                    ← données communes
│
├── lang/
│   ├── fr.php                  ← traductions françaises
│   ├── en.php                  ← traductions anglaises
│   └── es.php                  ← traductions espagnoles
│
├── includes/
│   ├── header.gabs             ← en-tête commun
│   ├── footer.gabs             ← pied de page commun
│   ├── nav-fr.gabs             ← navigation en français
│   ├── nav-en.gabs             ← navigation en anglais
│   └── nav-es.gabs             ← navigation en espagnol
│
└── pages/
    ├── home.gabs               ← page d'accueil
    ├── about.gabs              ← page à propos
    └── contact.gabs            ← page contact
```

---

### Les données

#### Traductions *(`lang/fr.php`)*

```php
<?php

// Toutes les clés sont identiques dans les trois fichiers de langue
// Seules les valeurs changent
return array(

    // --- Navigation ---
    's_nav_home'    => 'Accueil',
    's_nav_about'   => 'À propos',
    's_nav_contact' => 'Contact',

    // --- Page d'accueil ---
    's_home_title'  => 'Bienvenue',
    's_home_intro'  => 'Découvrez notre savoir-faire.',
    's_home_cta'    => 'En savoir plus',

    // --- Page à propos ---
    's_about_title' => 'À propos de nous',
    's_about_lead'  => 'Notre histoire commence en 2010.',

    // --- Page contact ---
    's_contact_title'       => 'Nous contacter',
    's_contact_name'        => 'Votre nom',
    's_contact_email'       => 'Votre email',
    's_contact_message'     => 'Votre message',
    's_contact_send'        => 'Envoyer',

    // --- Messages généraux ---
    's_lang_label'  => 'Langue',
    's_copy'        => 'Tous droits réservés',

);
```

#### Traductions *(`lang/en.php`)*

```php
<?php

return array(

    // --- Navigation ---
    's_nav_home'    => 'Home',
    's_nav_about'   => 'About',
    's_nav_contact' => 'Contact',

    // --- Home page ---
    's_home_title'  => 'Welcome',
    's_home_intro'  => 'Discover our expertise.',
    's_home_cta'    => 'Learn more',

    // --- About page ---
    's_about_title' => 'About us',
    's_about_lead'  => 'Our story began in 2010.',

    // --- Contact page ---
    's_contact_title'       => 'Contact us',
    's_contact_name'        => 'Your name',
    's_contact_email'       => 'Your email',
    's_contact_message'     => 'Your message',
    's_contact_send'        => 'Send',

    // --- General ---
    's_lang_label'  => 'Language',
    's_copy'        => 'All rights reserved',

);
```

#### Traductions *(`lang/es.php`)*

```php
<?php

return array(

    // --- Navegación ---
    's_nav_home'    => 'Inicio',
    's_nav_about'   => 'Nosotros',
    's_nav_contact' => 'Contacto',

    // --- Página de inicio ---
    's_home_title'  => 'Bienvenido',
    's_home_intro'  => 'Descubra nuestro saber hacer.',
    's_home_cta'    => 'Saber más',

    // --- Página nosotros ---
    's_about_title' => 'Sobre nosotros',
    's_about_lead'  => 'Nuestra historia comenzó en 2010.',

    // --- Página contacto ---
    's_contact_title'       => 'Contáctenos',
    's_contact_name'        => 'Su nombre',
    's_contact_email'       => 'Su correo',
    's_contact_message'     => 'Su mensaje',
    's_contact_send'        => 'Enviar',

    // --- General ---
    's_lang_label'  => 'Idioma',
    's_copy'        => 'Todos los derechos reservados',

);
```

#### Données communes *(`data.php`)*

```php
<?php

// --- Langue active ---
$aLangs   = array('fr', 'en', 'es');
$sLang    = (isset($_GET['lang']) && in_array($_GET['lang'], $aLangs))
            ? $_GET['lang']
            : (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'fr', 0, 2));
$sLang    = in_array($sLang, $aLangs) ? $sLang : 'fr';

// --- Page active ---
$aPages   = array('home', 'about', 'contact');
$sPage    = (isset($_GET['page']) && in_array($_GET['page'], $aPages))
            ? $_GET['page']
            : 'home';

// --- Chargement des traductions ---
$aLang = require 'lang/'.$sLang.'.php';

// --- Données du site ---
$aSite = array(
    's_site_name'   => 'MonSite',
    's_site_name_g' => 'MonSite',   // accessible dans toutes les boucles
    'c_url_home'    => '/',
    's_lang'        => $sLang,
    's_lang_g'      => $sLang,      // accessible dans toutes les boucles
    's_page'        => $sPage,

    // Booléens de langue — pour classes actives dans la navigation
    'b_lang_fr'     => ($sLang === 'fr'),
    'b_lang_en'     => ($sLang === 'en'),
    'b_lang_es'     => ($sLang === 'es'),

    // Booléens de page — pour classes actives dans la navigation
    'b_page_home'    => ($sPage === 'home'),
    'b_page_about'   => ($sPage === 'about'),
    'b_page_contact' => ($sPage === 'contact'),

    // Exemples de données dynamiques (viendraient d'une BDD en production)
    'a_services' => array(
        array('s_icon' => '⚡', 's_key_g' => 'service1'),
        array('s_icon' => '🎯', 's_key_g' => 'service2'),
        array('s_icon' => '🔒', 's_key_g' => 'service3'),
    ),

    // Libellés des services — traduits, accessibles dans la boucle via _g
    's_service1_g'  => $aLang['s_home_cta'],
    's_service2_g'  => $aLang['s_nav_about'],
    's_service3_g'  => $aLang['s_nav_contact'],

);

// Fusion : données du site + traductions
$data = array_merge($aSite, $aLang);
```

---

### Les gabarits de langue

> Les navigations sont séparées par langue pour conserver des libellés et structures potentiellement différents — une bonne pratique sur les projets multi-langue réels.

#### *(`includes/nav-fr.gabs`)*

```html
<nav class="site-nav">
    <!-- Classes actives injectées dynamiquement — syntaxe courte, une seule ligne -->
    <a href="?lang=fr&page=home"    class="{b_page_home{[    active ]b_page_home}">    {s_nav_home}</a>
    <a href="?lang=fr&page=about"   class="{b_page_about{[   active ]b_page_about}">   {s_nav_about}</a>
    <a href="?lang=fr&page=contact" class="{b_page_contact{[ active ]b_page_contact}"> {s_nav_contact}</a>

    <!-- Sélecteur de langue — classe active sur la langue courante -->
    <div class="lang-switcher">
        <span>{s_lang_label} :</span>
        <a href="?lang=fr&page={s_page}" class="{b_lang_fr{[ active ]b_lang_fr}">FR</a>
        <a href="?lang=en&page={s_page}" class="{b_lang_en{[ active ]b_lang_en}">EN</a>
        <a href="?lang=es&page={s_page}" class="{b_lang_es{[ active ]b_lang_es}">ES</a>
    </div>
</nav>
```

#### *(`includes/nav-en.gabs`)*

```html
<nav class="site-nav">
    <a href="?lang=en&page=home"    class="{b_page_home{[    active ]b_page_home}">    {s_nav_home}</a>
    <a href="?lang=en&page=about"   class="{b_page_about{[   active ]b_page_about}">   {s_nav_about}</a>
    <a href="?lang=en&page=contact" class="{b_page_contact{[ active ]b_page_contact}"> {s_nav_contact}</a>

    <div class="lang-switcher">
        <span>{s_lang_label}:</span>
        <a href="?lang=fr&page={s_page}" class="{b_lang_fr{[ active ]b_lang_fr}">FR</a>
        <a href="?lang=en&page={s_page}" class="{b_lang_en{[ active ]b_lang_en}">EN</a>
        <a href="?lang=es&page={s_page}" class="{b_lang_es{[ active ]b_lang_es}">ES</a>
    </div>
</nav>
```

#### *(`includes/nav-es.gabs`)*

```html
<nav class="site-nav">
    <a href="?lang=es&page=home"    class="{b_page_home{[    active ]b_page_home}">    {s_nav_home}</a>
    <a href="?lang=es&page=about"   class="{b_page_about{[   active ]b_page_about}">   {s_nav_about}</a>
    <a href="?lang=es&page=contact" class="{b_page_contact{[ active ]b_page_contact}"> {s_nav_contact}</a>

    <div class="lang-switcher">
        <span>{s_lang_label}:</span>
        <a href="?lang=fr&page={s_page}" class="{b_lang_fr{[ active ]b_lang_fr}">FR</a>
        <a href="?lang=en&page={s_page}" class="{b_lang_en{[ active ]b_lang_en}">EN</a>
        <a href="?lang=es&page={s_page}" class="{b_lang_es{[ active ]b_lang_es}">ES</a>
    </div>
</nav>
```

---

### Les gabarits de mise en page

#### *(`includes/header.gabs`)*

```html
<!DOCTYPE html>
<html lang="{s_lang}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{s_site_name} — {s_home_title}</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="page-{s_page} lang-{s_lang}">

<header class="site-header">
    <a href="{c_url_home}" class="site-logo">{s_site_name}</a>
    <!-- Inclusion dynamique de la navigation selon la langue active -->
    {includes/nav-{s_lang}.gabs}
</header>

<main class="site-main">
```

#### *(`includes/footer.gabs`)*

```html
</main>

<footer class="site-footer">
    <p>© {s_site_name} — {s_copy}</p>
</footer>

</body>
</html>
```

---

### Les gabarits de pages

#### *(`pages/home.gabs`)*

```html
{includes/header.gabs}

<section class="hero">
    <h1>{s_home_title}</h1>
    <p class="lead">{s_home_intro}</p>
    <a href="?lang={s_lang}&page=about" class="btn-cta">{s_home_cta}</a>
</section>

<section class="services">

    <!-- s_key_g et s_lang_g sont accessibles dans la boucle grâce au suffixe _g -->
    {a_services{
        <div class="service-card">
            <span class="service-icon">{s_icon}</span>
            <!-- Injection dynamique de la clé de traduction via _g -->
            <p>{s_{s_key_g}_g}</p>
        </div>
    }a_services}

</section>

{includes/footer.gabs}
```

#### *(`pages/about.gabs`)*

```html
{includes/header.gabs}

<section class="about">
    <h1>{s_about_title}</h1>
    <p class="lead">{s_about_lead}</p>
</section>

{includes/footer.gabs}
```

#### *(`pages/contact.gabs`)*

```html
{includes/header.gabs}

<section class="contact">

    <h1>{s_contact_title}</h1>

    <form class="contact-form" method="post">

        <label>
            {s_contact_name}
            <input type="text" name="name" required>
        </label>

        <label>
            {s_contact_email}
            <input type="email" name="email" required>
        </label>

        <label>
            {s_contact_message}
            <textarea name="message" rows="6" required></textarea>
        </label>

        <button type="submit">{s_contact_send}</button>

    </form>

</section>

{includes/footer.gabs}
```

---

### Le contrôleur

#### *(`index.php`)*

```php
<?php

require_once 'Gabs.php';
require_once 'funcs_gabs.php';
require_once 'data.php';   // charge $data avec langue + traductions fusionnées

$gabs = new Gabs();
$gabs->conf(array(
    'cach' => true,
    'dbug' => false,
    'pure' => true,
    'tpls' => '',
));

// Routage : le gabarit est choisi selon la page active
echo $gabs->get('pages/'.$sPage.'.gabs', $data, $aFuncsGabs);
```

---

### Bilan

| Fonctionnalité | Mise en œuvre |
|----------------|--------------|
| **Détection langue** | `HTTP_ACCEPT_LANGUAGE` + fallback `$_GET['lang']` |
| **Traductions** | Fichiers `lang/*.php` — tableau de clés identiques |
| **Fusion données** | `array_merge($aSite, $aLang)` — traductions au même niveau que les données |
| **Navigation dynamique** | `{includes/nav-{s_lang}.gabs}` — inclusion selon langue active |
| **Classes actives** | Booléens `b_lang_*` et `b_page_*` — syntaxe courte dans les gabarits |
| **Variables `_g`** | `s_lang_g`, `s_site_name_g` — accessibles dans toutes les boucles |
| **Routage** | `pages/{s_page}.gabs` — un fichier par page, choix dans le contrôleur |
| **Cache production** | `cach => true, pure => true` |

---

*— fin du Cas 3 — Multi-langue —*

---

<div align="center">

**{ logique sans bruit && design sans echo }**

[GitHub](https://github.com/fredomkb58/Gabs) • *v0120*

**Made with ❤️ from France 🇫🇷 for World 🌎**

</div>
