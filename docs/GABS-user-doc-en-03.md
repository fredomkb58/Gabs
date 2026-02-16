<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logic without noise && design without echo }**

*Version v0120*

</div>

---

# User Documentation - Level 3

**Advanced case — direct code, minimal explanations. Over to you.**

> This document assumes you are comfortable with Levels 1 and 2. Core concepts are not repeated here.

---

## 📖 Table of contents

- [Case 3 — Multi-language site](#-case-3--multi-language-site)
  - [Project structure](#project-structure)
  - [The data](#the-data)
  - [Language templates](#language-templates)
  - [Layout templates](#layout-templates)
  - [Page templates](#page-templates)
  - [The controller](#the-controller)
  - [Summary](#summary)

---

## 🌍 Case 3 — Multi-language site

A trilingual showcase site (FR / EN / ES) with automatic language detection, translation templates, localised navigation and clean URLs.

---

### Project structure

```
my-site/
├── Gabs.php
├── funcs_gabs.php
├── index.php                   ← main controller
├── data.php                    ← shared data
│
├── lang/
│   ├── fr.php                  ← French translations
│   ├── en.php                  ← English translations
│   └── es.php                  ← Spanish translations
│
├── includes/
│   ├── header.gabs             ← shared header
│   ├── footer.gabs             ← shared footer
│   ├── nav-fr.gabs             ← French navigation
│   ├── nav-en.gabs             ← English navigation
│   └── nav-es.gabs             ← Spanish navigation
│
└── pages/
    ├── home.gabs               ← home page
    ├── about.gabs              ← about page
    └── contact.gabs            ← contact page
```

---

### The data

#### Translations *(`lang/fr.php`)*

```php
<?php

// All keys are identical across the three language files
// Only the values change
return array(

    // --- Navigation ---
    's_nav_home'    => 'Accueil',
    's_nav_about'   => 'À propos',
    's_nav_contact' => 'Contact',

    // --- Home page ---
    's_home_title'  => 'Bienvenue',
    's_home_intro'  => 'Découvrez notre savoir-faire.',
    's_home_cta'    => 'En savoir plus',

    // --- About page ---
    's_about_title' => 'À propos de nous',
    's_about_lead'  => 'Notre histoire commence en 2010.',

    // --- Contact page ---
    's_contact_title'   => 'Nous contacter',
    's_contact_name'    => 'Votre nom',
    's_contact_email'   => 'Votre email',
    's_contact_message' => 'Votre message',
    's_contact_send'    => 'Envoyer',

    // --- General ---
    's_lang_label'  => 'Langue',
    's_copy'        => 'Tous droits réservés',

);
```

#### Translations *(`lang/en.php`)*

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
    's_contact_title'   => 'Contact us',
    's_contact_name'    => 'Your name',
    's_contact_email'   => 'Your email',
    's_contact_message' => 'Your message',
    's_contact_send'    => 'Send',

    // --- General ---
    's_lang_label'  => 'Language',
    's_copy'        => 'All rights reserved',

);
```

#### Translations *(`lang/es.php`)*

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
    's_contact_title'   => 'Contáctenos',
    's_contact_name'    => 'Su nombre',
    's_contact_email'   => 'Su correo',
    's_contact_message' => 'Su mensaje',
    's_contact_send'    => 'Enviar',

    // --- General ---
    's_lang_label'  => 'Idioma',
    's_copy'        => 'Todos los derechos reservados',

);
```

#### Shared data *(`data.php`)*

```php
<?php

// --- Active language ---
$aLangs   = array('fr', 'en', 'es');
$sLang    = (isset($_GET['lang']) && in_array($_GET['lang'], $aLangs))
            ? $_GET['lang']
            : (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2));
$sLang    = in_array($sLang, $aLangs) ? $sLang : 'en';

// --- Active page ---
$aPages   = array('home', 'about', 'contact');
$sPage    = (isset($_GET['page']) && in_array($_GET['page'], $aPages))
            ? $_GET['page']
            : 'home';

// --- Load translations ---
$aLang = require 'lang/'.$sLang.'.php';

// --- Site data ---
$aSite = array(
    's_site_name'   => 'MySite',
    's_site_name_g' => 'MySite',    // accessible inside all loops
    'c_url_home'    => '/',
    's_lang'        => $sLang,
    's_lang_g'      => $sLang,      // accessible inside all loops
    's_page'        => $sPage,

    // Language booleans — for active classes in navigation
    'b_lang_fr'     => ($sLang === 'fr'),
    'b_lang_en'     => ($sLang === 'en'),
    'b_lang_es'     => ($sLang === 'es'),

    // Page booleans — for active classes in navigation
    'b_page_home'    => ($sPage === 'home'),
    'b_page_about'   => ($sPage === 'about'),
    'b_page_contact' => ($sPage === 'contact'),

    // Sample dynamic data (would come from a database in production)
    'a_services' => array(
        array('s_icon' => '⚡', 's_key_g' => 'service1'),
        array('s_icon' => '🎯', 's_key_g' => 'service2'),
        array('s_icon' => '🔒', 's_key_g' => 'service3'),
    ),

    // Service labels — translated, accessible inside the loop via _g
    's_service1_g'  => $aLang['s_home_cta'],
    's_service2_g'  => $aLang['s_nav_about'],
    's_service3_g'  => $aLang['s_nav_contact'],

);

// Merge: site data + translations
$data = array_merge($aSite, $aLang);
```

---

### Language templates

> Navigation templates are separated by language to allow for potentially different labels and structures — a good practice on real multi-language projects.

#### *(`includes/nav-fr.gabs`)*

```html
<nav class="site-nav">
    <!-- Active classes injected dynamically — short syntax, single line -->
    <a href="?lang=fr&page=home"    class="{b_page_home{[    active ]b_page_home}">    {s_nav_home}</a>
    <a href="?lang=fr&page=about"   class="{b_page_about{[   active ]b_page_about}">   {s_nav_about}</a>
    <a href="?lang=fr&page=contact" class="{b_page_contact{[ active ]b_page_contact}"> {s_nav_contact}</a>

    <!-- Language switcher — active class on current language -->
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

### Layout templates

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
    <!-- Dynamic include of navigation based on active language -->
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

### Page templates

#### *(`pages/home.gabs`)*

```html
{includes/header.gabs}

<section class="hero">
    <h1>{s_home_title}</h1>
    <p class="lead">{s_home_intro}</p>
    <a href="?lang={s_lang}&page=about" class="btn-cta">{s_home_cta}</a>
</section>

<section class="services">

    <!-- s_key_g and s_lang_g are accessible in the loop thanks to the _g suffix -->
    {a_services{
        <div class="service-card">
            <span class="service-icon">{s_icon}</span>
            <!-- Dynamic key injection via _g — the variable name is itself dynamic -->
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

### The controller

#### *(`index.php`)*

```php
<?php

require_once 'Gabs.php';
require_once 'funcs_gabs.php';
require_once 'data.php';   // loads $data with language + merged translations

$gabs = new Gabs();
$gabs->conf(array(
    'cach' => true,
    'dbug' => false,
    'pure' => true,
    'tpls' => '',
));

// Routing: template chosen based on active page
echo $gabs->get('pages/'.$sPage.'.gabs', $data, $aFuncsGabs);
```

---

### Summary

| Feature | Implementation |
|---------|---------------|
| **Language detection** | `HTTP_ACCEPT_LANGUAGE` + `$_GET['lang']` fallback |
| **Translations** | `lang/*.php` files — identical keys across all languages |
| **Data merge** | `array_merge($aSite, $aLang)` — translations at root level |
| **Dynamic navigation** | `{includes/nav-{s_lang}.gabs}` — included based on active language |
| **Active classes** | `b_lang_*` and `b_page_*` booleans — short syntax in templates |
| **`_g` variables** | `s_lang_g`, `s_site_name_g` — accessible inside all loops |
| **Routing** | `pages/{s_page}.gabs` — one file per page, chosen in controller |
| **Production cache** | `cach => true, pure => true` |

---

*— end of Case 3 — Multi-language —*

---

<div align="center">

**{ logic without noise && design without echo }**

[GitHub](https://github.com/fredomkb58/Gabs) • *v0120*

**Made with ❤️ from France 🇫🇷 for World 🌎**

</div>
