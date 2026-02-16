<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logic without noise && design without echo }**

*Version v0120*

</div>

---

# User Documentation - Level 2

**Advanced features — applied to a concrete use case.**

> This document assumes you have already worked through Level 1. Core concepts (variables, prefixes, basic filters, conditions, includes) are not re-explained here.

---

## 📖 Table of contents

- [Case 2 — Portfolio](#-case-2--portfolio)
  - [Step 1 — The works grid](#step-1--the-works-grid-loop--slicing--filters)
  - [Step 2 — Category filtering](#step-2--category-filtering)
  - [Step 3 — The detail page](#step-3--the-detail-page-loop-info--global-variables)
  - [Step 4 — Structure and dynamic includes](#step-4--structure-and-dynamic-includes)

---

## 🎨 Case 2 — Portfolio

An artist portfolio: homepage with a works grid, category filtering, detail page, and a modular structure with dynamic includes.

This case introduces GABS advanced features: **slicing**, **reverse sort**, **loop info**, **global variables** and **dynamic includes**.

---

### Step 1 — The works grid *(loop + slicing + filters)*

**Goal:** display the 6 most recent works in a grid, with title, category, year and a link to the detail page.

---

#### File structure

```
my-portfolio/
├── Gabs.php
├── funcs_gabs.php
├── index.php
├── data.php
└── home.gabs
```

---

#### The data *(`data.php`)*

```php
<?php

$data = array(

    // --- Artist ---
    's_artist_name'  => 'Sophie Renard',
    's_artist_bio'   => 'Photographer and illustrator based in Lyon, France.',
    's_artist_photo' => '/images/artist/sophie-renard.jpg',

    // --- Full works catalogue ---
    // Works are ordered from oldest to newest
    // We'll use reverse sort + slicing to display the 6 most recent
    'a_works' => array(

        array(
            's_title'     => 'morning mist',
            's_category'  => 'photography',
            's_slug'      => 'morning-mist',
            'h_thumb'     => '/images/works/morning-mist.jpg',
            'n_year'      => 2022,
            'n_ts_date'   => mktime(0, 0, 0, 3, 12, 2022),
            'b_featured'  => false,
            'b_sold'      => true,
        ),
        array(
            's_title'     => 'urbanity #3',
            's_category'  => 'illustration',
            's_slug'      => 'urbanity-3',
            'h_thumb'     => '/images/works/urbanity-3.jpg',
            'n_year'      => 2023,
            'n_ts_date'   => mktime(0, 0, 0, 6, 5, 2023),
            'b_featured'  => false,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'winter light',
            's_category'  => 'photography',
            's_slug'      => 'winter-light',
            'h_thumb'     => '/images/works/winter-light.jpg',
            'n_year'      => 2023,
            'n_ts_date'   => mktime(0, 0, 0, 11, 20, 2023),
            'b_featured'  => true,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'blue series #1',
            's_category'  => 'illustration',
            's_slug'      => 'blue-series-1',
            'h_thumb'     => '/images/works/blue-series-1.jpg',
            'n_year'      => 2024,
            'n_ts_date'   => mktime(0, 0, 0, 2, 8, 2024),
            'b_featured'  => false,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'blue series #2',
            's_category'  => 'illustration',
            's_slug'      => 'blue-series-2',
            'h_thumb'     => '/images/works/blue-series-2.jpg',
            'n_year'      => 2024,
            'n_ts_date'   => mktime(0, 0, 0, 4, 15, 2024),
            'b_featured'  => true,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'urban silence',
            's_category'  => 'photography',
            's_slug'      => 'urban-silence',
            'h_thumb'     => '/images/works/urban-silence.jpg',
            'n_year'      => 2024,
            'n_ts_date'   => mktime(0, 0, 0, 9, 3, 2024),
            'b_featured'  => false,
            'b_sold'      => false,
        ),
        array(
            's_title'     => 'january forest',
            's_category'  => 'photography',
            's_slug'      => 'january-forest',
            'h_thumb'     => '/images/works/january-forest.jpg',
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
            's_title'     => 'red and gold',
            's_category'  => 'illustration',
            's_slug'      => 'red-and-gold',
            'h_thumb'     => '/images/works/red-and-gold.jpg',
            'n_year'      => 2026,
            'n_ts_date'   => mktime(0, 0, 0, 1, 10, 2026),
            'b_featured'  => true,
            'b_sold'      => false,
        ),

    ),

);
```

---

#### The template *(`home.gabs`)*

```html
<section class="artist-intro">
    <img src="{s_artist_photo}" alt="{s_artist_name|f_title}">
    <div>
        <h1>{s_artist_name|f_title}</h1>
        <p>{s_artist_bio}</p>
    </div>
</section>

<section class="works-grid">

    <h2>Latest works</h2>

    <!-- Reverse sort: most recent first                          -->
    <!-- Slicing [-6[ … ]0]: the last 6 elements                 -->
    <!-- Combined: {a_works{[-6[!  = last 6, reverse order       -->
    <div class="grid">
    {a_works{[-6[!

        <article class="work-card {b_featured{[ work-card--featured }b_featured}">

            <a href="/work/{s_slug}">
                <img src="{h_thumb|}" alt="{s_title|f_title}">
            </a>

            <div class="work-card-info">
                <h3><a href="/work/{s_slug}">{s_title|f_title}</a></h3>
                <!-- f_ucfirst: "photography" → "Photography" -->
                <span class="category">{s_category|f_ucfirst}</span>
                <span class="year">{n_year}</span>
            </div>

            <!-- Badges — short syntax, always on a single line -->
            {b_featured{[ <span class="badge-featured">⭐ Featured</span> }b_featured}
            {b_sold{[ <span class="badge-sold">Sold</span> }b_sold}

        </article>

    ]0]}a_works}
    </div>

    <!-- Loop info: catalogue total and number displayed -->
    <p class="works-count">
        {a_works_1_n} works displayed out of {a_works_1_t} in the catalogue
    </p>

</section>
```

---

#### The controller *(`index.php`)*

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

#### HTML output *(excerpt)*

```html
<section class="works-grid">

    <h2>Latest works</h2>

    <div class="grid">

        <article class="work-card work-card--featured">
            <a href="/work/red-and-gold">
                <img src="/images/works/red-and-gold.jpg" alt="Red And Gold">
            </a>
            <div class="work-card-info">
                <h3><a href="/work/red-and-gold">Red And Gold</a></h3>
                <span class="category">Illustration</span>
                <span class="year">2026</span>
            </div>
            <span class="badge-featured">⭐ Featured</span>
        </article>

        <!-- ... 5 more works ... -->

    </div>

    <p class="works-count">6 works displayed out of 9 in the catalogue</p>

</section>
```

---

#### What we covered in this step

| Concept | Syntax |
|---------|--------|
| **Reverse sort** | `{a_works{!` — last to first |
| **Slicing from end** | `[-6[` — last 6 elements |
| **Sort + slicing combined** | `{a_works{[-6[!` — last 6, reverse order |
| **Loop info** | `{a_works_1_n}` displayed / `{a_works_1_t}` total |

---

> 🎯 **Next step:** category filtering with conditions and dynamic CSS classes.

---

*— end of Step 1 —*

---

### Step 2 — Category filtering

**Goal:** let the user filter works by category (photography / illustration / all), with a navigation menu and highlighting of the active category.

---

#### What we add to the data *(`data.php`)*

```php
// --- Active filter (typically from $_GET['cat']) ---
$cat = $_GET['cat'] ?? 'all';

$data = array_merge($data, array(

    // Active category
    's_cat_active'  => $cat,

    // Booleans for navigation — only one will be true at a time
    'b_cat_all'     => ($cat === 'all'),
    'b_cat_photo'   => ($cat === 'photography'),
    'b_cat_illus'   => ($cat === 'illustration'),

    // Works filtered by active category
    'a_works_photo' => array_values(array_filter($data['a_works'],
        function($w) { return $w['s_category'] === 'photography'; }
    )),
    'a_works_illus' => array_values(array_filter($data['a_works'],
        function($w) { return $w['s_category'] === 'illustration'; }
    )),

));
```

> Filtering is done in PHP — in keeping with the Logic-Less principle. The template receives three ready-to-use arrays and chooses which one to display based on the booleans.

---

#### Updated template *(`home.gabs`)*

Replace the `works-grid` section from Step 1 with this:

```html
<section class="works-section">

    <!-- Category navigation — "active" class injected dynamically -->
    <!-- ⚠️ Short syntax = always on a single line                 -->
    <nav class="cat-nav">
        <a href="?cat=all"          class="{b_cat_all{[   active ]b_cat_all}">All</a>
        <a href="?cat=photography"  class="{b_cat_photo{[ active ]b_cat_photo}">Photography</a>
        <a href="?cat=illustration" class="{b_cat_illus{[ active ]b_cat_illus}">Illustration</a>
    </nav>

    <!-- ============================================================
         CONDITIONAL DISPLAY based on active category
         Each loop is only rendered if its boolean is true
         ============================================================ -->

    <!-- All works (last 6, reverse order) -->
    {b_cat_all{
        <div class="grid">
        {a_works{[-6[!
            <article class="work-card {b_featured{[ work-card--featured }b_featured}">
                <a href="/work/{s_slug}">
                    <img src="{h_thumb|}" alt="{s_title|f_title}">
                </a>
                <div class="work-card-info">
                    <h3>{s_title|f_title}</h3>
                    <span class="category">{s_category|f_ucfirst}</span>
                    <span class="year">{n_year}</span>
                </div>
                {b_featured{[ <span class="badge-featured">⭐ Featured</span> }b_featured}
                {b_sold{[ <span class="badge-sold">Sold</span> }b_sold}
            </article>
        ]0]}a_works}
        </div>
        <p class="works-count">{a_works_1_n} works out of {a_works_1_t}</p>
    }b_cat_all}

    <!-- Photography only -->
    {b_cat_photo{
        <div class="grid">
        {a_works_photo{[-6[!
            <article class="work-card {b_featured{[ work-card--featured }b_featured}">
                <a href="/work/{s_slug}">
                    <img src="{h_thumb|}" alt="{s_title|f_title}">
                </a>
                <div class="work-card-info">
                    <h3>{s_title|f_title}</h3>
                    <span class="year">{n_year}</span>
                </div>
                {b_featured{[ <span class="badge-featured">⭐ Featured</span> }b_featured}
                {b_sold{[ <span class="badge-sold">Sold</span> }b_sold}
            </article>
        ]0]}a_works_photo}
        </div>
        <p class="works-count">{a_works_photo_1_n} photos out of {a_works_photo_1_t}</p>
    }b_cat_photo}

    <!-- Illustrations only -->
    {b_cat_illus{
        <div class="grid">
        {a_works_illus{[-6[!
            <article class="work-card {b_featured{[ work-card--featured }b_featured}">
                <a href="/work/{s_slug}">
                    <img src="{h_thumb|}" alt="{s_title|f_title}">
                </a>
                <div class="work-card-info">
                    <h3>{s_title|f_title}</h3>
                    <span class="year">{n_year}</span>
                </div>
                {b_featured{[ <span class="badge-featured">⭐ Featured</span> }b_featured}
                {b_sold{[ <span class="badge-sold">Sold</span> }b_sold}
            </article>
        ]0]}a_works_illus}
        </div>
        <p class="works-count">{a_works_illus_1_n} illustrations out of {a_works_illus_1_t}</p>
    }b_cat_illus}

</section>
```

---

#### What we covered in this step

| Concept | Syntax |
|---------|--------|
| **Logic-Less filtering** | Filters prepared in PHP, template chooses what to display |
| **Active navigation** | `class="{b_cat{[ active ]b_cat}"` — class injected if true |
| **Conditional loops** | `{b_cat_photo{ {a_works_photo{ … }a_works_photo} }b_cat_photo}` |
| **Per-array info** | `{a_works_photo_1_t}` — total specific to each loop |

---

> 🎯 **Next step:** work detail page with loop info and `_g` global variables.

---

*— end of Step 2 —*

---

### Step 3 — The detail page *(loop info + global variables)*

**Goal:** display a full work page — high-res image, metadata — with previous/next navigation, and the artist name accessible inside all loops via `_g`.

---

#### New file structure

```
my-portfolio/
├── Gabs.php
├── funcs_gabs.php
├── index.php
├── data.php
├── home.gabs
└── work.gabs          ← new: detail page
```

---

#### The data *(`data.php`)*

```php
// --- Slug of the requested work (typically from the URL) ---
$slug = $_GET['slug'] ?? '';

// Find the work in the catalogue
$work    = null;
$works   = $data['a_works'];
$nTotal  = count($works);

foreach ($works as $i => $w) {
    if ($w['s_slug'] === $slug) {
        $work = $w;
        $work['n_position'] = $i + 1;       // position in catalogue (1-based)
        $work['n_total']    = $nTotal;       // catalogue total
        $work['b_has_prev'] = ($i > 0);
        $work['b_has_next'] = ($i < $nTotal - 1);
        $work['s_prev_slug'] = ($i > 0)           ? $works[$i-1]['s_slug'] : '';
        $work['s_next_slug'] = ($i < $nTotal - 1) ? $works[$i+1]['s_slug'] : '';
        break;
    }
}

// Global variables (_g) accessible inside all loops
$data['s_artist_name_g'] = $data['s_artist_name'];
$data['s_artist_url_g']  = '/';

// Related works (same category, excluding current work)
$data['a_related'] = array_values(array_filter($data['a_works'],
    function($w) use ($work) {
        return $w['s_category'] === $work['s_category']
            && $w['s_slug']     !== $work['s_slug'];
    }
));

// Merge work data into $data
$data = array_merge($data, $work);
```

---

#### The template *(`work.gabs`)*

```html
<article class="work-detail">

    <header class="work-header">
        <h1>{s_title|f_title}</h1>

        <div class="work-meta">
            <!-- f_ucfirst on category -->
            <span class="category">{s_category|f_ucfirst}</span>
            <span class="year">{n_year}</span>
            <!-- f_date formats the creation timestamp -->
            <time>{n_ts_date|f_date_us}</time>
        </div>

        <!-- Status badges -->
        {b_featured{[ <span class="badge-featured">⭐ Featured</span> }b_featured}
        {b_sold{
            <span class="badge-sold">✗ Sold</span>
        }b_sold{
            <span class="badge-available">✓ Available</span>
        }b_sold}

        <!-- Position in catalogue -->
        <p class="work-position">Work {n_position} of {n_total}</p>
    </header>

    <!-- High-res image -->
    <figure class="work-figure">
        <img src="{h_thumb|}" alt="{s_title|f_title}">
    </figure>

    <!-- Previous / next navigation -->
    <nav class="work-nav">
        {b_has_prev{
            <a href="/work/{s_prev_slug}" class="btn-prev">← Previous</a>
        }b_has_prev{
            <span class="btn-prev btn--disabled">← Previous</span>
        }b_has_prev}

        <a href="/" class="btn-home">Back to portfolio</a>

        {b_has_next{
            <a href="/work/{s_next_slug}" class="btn-next">Next →</a>
        }b_has_next{
            <span class="btn-next btn--disabled">Next →</span>
        }b_has_next}
    </nav>

</article>

<!-- Related works (last 3 in the same category) -->
{b_has_related{
    <section class="related-works">

        <!-- s_artist_name_g accessible here thanks to the _g suffix -->
        <h2>More {s_category|f_ucfirst} by {s_artist_name_g}</h2>

        <div class="grid">
        {a_related{[-3[!
            <article class="work-card">
                <a href="/work/{s_slug}">
                    <img src="{h_thumb|}" alt="{s_title|f_title}">
                </a>
                <div class="work-card-info">
                    <h3>{s_title|f_title}</h3>
                    <!-- s_artist_name_g available inside the loop -->
                    <small>{s_artist_name_g}</small>
                </div>
            </article>
        ]0]}a_related}
        </div>

        <p class="related-count">
            {a_related_1_n} work(s) shown out of {a_related_1_t} in this category
        </p>

    </section>
}b_has_related}
```

> **Note:** `b_has_related` must be added to the PHP data: `'b_has_related' => !empty($data['a_related'])`.

---

#### What we covered in this step

| Concept | Syntax |
|---------|--------|
| **Previous/next navigation** | `b_has_prev` / `b_has_next` conditions on links |
| **Position in catalogue** | Computed in PHP, passed as `n_position` / `n_total` |
| **`_g` variables in loop** | `{s_artist_name_g}` accessible inside `{a_related{ … }a_related}` |
| **Slicing related works** | `{a_related{[-3[!` — last 3, reverse order |

---

> 🎯 **Next step:** full structure with dynamic includes.

---

*— end of Step 3 —*

---

### Step 4 — Structure and dynamic includes

**Goal:** finalise the portfolio structure with shared header/footer, and introduce dynamic includes to easily handle a light/dark theme.

---

#### Final file structure

```
my-portfolio/
├── Gabs.php
├── funcs_gabs.php
├── index.php
├── data.php
├── home.gabs
├── work.gabs
└── includes/
    ├── header.gabs            ← shared header
    ├── footer.gabs            ← shared footer
    ├── nav-light.gabs         ← light theme navigation
    └── nav-dark.gabs          ← dark theme navigation
```

---

#### What we add to the data *(`data.php`)*

```php
// Active theme (typically from a cookie or user preference)
$theme = $_COOKIE['theme'] ?? 'light';

$data = array_merge($data, array(
    's_theme'        => $theme,                   // 'light' or 'dark'
    's_site_name_g'  => 'Sophie Renard Portfolio',
    'c_url_home'     => '/',
    'b_theme_dark'   => ($theme === 'dark'),
));
```

---

#### The header *(`includes/header.gabs`)*

```html
<!DOCTYPE html>
<!-- Theme CSS class injected dynamically -->
<html lang="en" class="theme-{s_theme}">
<head>
    <meta charset="UTF-8">
    <title>{s_title|f_title} — {s_site_name_g}</title>
    <!-- Stylesheet based on active theme -->
    <link rel="stylesheet" href="/css/style-{s_theme}.css">
</head>
<body>

<!-- ============================================================
     DYNAMIC INCLUDE of navigation based on theme
     {includes/nav-{s_theme}.gabs} becomes:
     → {includes/nav-light.gabs} if s_theme = 'light'
     → {includes/nav-dark.gabs}  if s_theme = 'dark'
     ============================================================ -->
{includes/nav-{s_theme}.gabs}

<main class="site-main">
```

---

#### Light theme navigation *(`includes/nav-light.gabs`)*

```html
<header class="site-header site-header--light">
    <a href="{c_url_home}" class="site-logo">{s_site_name_g}</a>
    <nav>
        <a href="/">Portfolio</a>
        <a href="/contact">Contact</a>
        <a href="?theme=dark" class="btn-theme">🌙 Dark mode</a>
    </nav>
</header>
```

---

#### Dark theme navigation *(`includes/nav-dark.gabs`)*

```html
<header class="site-header site-header--dark">
    <a href="{c_url_home}" class="site-logo">{s_site_name_g}</a>
    <nav>
        <a href="/">Portfolio</a>
        <a href="/contact">Contact</a>
        <a href="?theme=light" class="btn-theme">☀️ Light mode</a>
    </nav>
</header>
```

---

#### The footer *(`includes/footer.gabs`)*

```html
</main>

<footer class="site-footer theme-{s_theme}">
    <p>© {n_ts_date|f_year} {s_site_name_g}</p>
    {b_theme_dark{
        <p class="footer-note">Dark mode enabled</p>
    }b_theme_dark{
        <p class="footer-note">Light mode enabled</p>
    }b_theme_dark}
</footer>

</body>
</html>
```

---

#### Updated templates

Simply add the includes at the top and bottom of both `home.gabs` and `work.gabs`:

```html
{includes/header.gabs}

<!-- ... page content ... -->

{includes/footer.gabs}
```

---

#### Final controller *(`index.php`)*

```php
<?php

require_once 'Gabs.php';
require_once 'funcs_gabs.php';
require_once 'data.php';

$gabs = new Gabs();
$gabs->conf(array(
    'cach' => true,
    'dbug' => false,
    'pure' => true,
    'tpls' => '',
));

// Simple routing: home or work detail
$slug = $_GET['slug'] ?? '';
$tpl  = (!empty($slug)) ? 'work.gabs' : 'home.gabs';

echo $gabs->get($tpl, $data, $aFuncsGabs);
```

---

#### What we covered in this step

| Concept | Syntax |
|---------|--------|
| **Dynamic include** | `{includes/nav-{s_theme}.gabs}` — variable path |
| **Dynamic theme** | `class="theme-{s_theme}"` / `href="/css/style-{s_theme}.css"` |
| **Production cache** | `'cach' => true, 'pure' => true` |
| **Minimal routing** | Template chosen based on `$_GET['slug']` in the controller |

---

#### Summary of Case 2 — Portfolio

| Step | Features |
|------|---------|
| 1 | Advanced loop, slicing, reverse sort, loop info |
| 2 | Logic-Less filtering, active navigation, conditional loops |
| 3 | Previous/next navigation, `_g` variables in loops, related works |
| 4 | Dynamic includes, CSS theme, minimal routing, production cache |

---

*— end of Case 2 — Portfolio —*
