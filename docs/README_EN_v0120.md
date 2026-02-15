<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logic without noise && design without echo }**

**PHP/HTML Template Engine**

**simple • lightweight • fast • secure • logic-less**

*Version v0120*

[![PHP Version](https://img.shields.io/badge/PHP-5.6%2B-red)](https://php.net)
[![Version](https://img.shields.io/badge/version-0120-blue)](https://github.com/fredomkb58/Gabs)
[![License](https://img.shields.io/badge/license-MIT-orange)](LICENSE)

</div>

---

## 📖 Table of contents

- [Why GABS?](#-why-gabs)
- [Installation](#-installation)
- [Quick start](#-quick-start)
- [Features](#-features)
- [Syntax](#-syntax)
- [Filters](#-filters-)
- [Configuration](#%EF%B8%8F-configuration)
- [Performance](#-performance)
- [Security](#-security)
- [Full examples](#-full-examples)
- [Conventions](#-naming-conventions)
- [Contributing](#-contributing)

---

## 🎯 Why GABS?

### The problem

Every **PHP and HTML developer** has inevitably faced the challenge of untangling **"spaghetti code"** that has become impossible to maintain over time — because **PHP logic** is so heavily "polluted" by **HTML presentation code**… and on the design side, making the slightest adjustment **without risking breaking all the PHP logic** becomes extremely difficult… not to mention the **real security risks** that this kind of heavily mixed code ultimately creates.

That is why, very early on, **the need to separate logic from presentation** gave birth to solutions that allow developing the PHP logic layer "without noise" and building HTML designs "without echo" = **template engines**.

**GABS** *(a contraction of the French word "gabarits" = "templates" in English)* fully embraces this idea of separating responsibilities, simplifying tasks and clarifying roles!

Among the various concepts explored in this pursuit of simplification, **the "logic-less" approach** is the one that best describes the choice made by **GABS**: logic remains the **exclusive responsibility of PHP** and presentation (or design) is **the reserved domain of HTML**.

**And GABS then?** … it takes on the role of being **the bridge between these two strong commitments**: between PHP and HTML.

But the question remains: **why GABS?** … when plenty of other solutions exist and have proven themselves.

Indeed, the most well-known **template engines** today are **powerful** and cover virtually all needs, but they are often also **too complex** for simple projects:

- ❌ Heavy dependencies (Composer, frameworks)
- ❌ Steep learning curve
- ❌ Features often overkill for 80% of projects
- ❌ Disappointing performance on simple use cases

### The GABS solution

**GABS** is a template engine that tries to get back to basics:

- ✅ **Single file** (zero dependencies)
- ✅ **Clear syntax** (learned in 15 minutes)
- ✅ **Ultra-fast** (~10ms without cache, ~2.5ms with)
- ✅ **Secure** (auto-escaping, path-traversal protection)
- ✅ **Lightweight** (~1300 lines ~65 KB; ~360 lines ~16 KB minified)
- ✅ **Logic-Less** (logic = PHP ; design = HTML/GABS)
- ✅ **Filters** (format variables directly in templates)

If you are looking for a template engine for a project that does not need **heavy artillery**, but rather a lightweight, fast, easy-to-install and easy-to-use alternative that still offers the main necessary features: **GABS is the right solution for the right projects!**

---

## 📦 Installation

### Single file (recommended)

```bash
# Download Gabs.php
wget https://raw.githubusercontent.com/fredomkb58/Gabs/main/Gabs.php
```

```php
<?php
require_once 'Gabs.php';
$gabs = new Gabs();
```

**That's it! 🎉**

---

## 🚀 Quick start

**3 simple steps:**

### 1. PHP data *(`data.php`)*

Prepare a simple **PHP associative array**, where **each key** will become a tag in **GABS**.

```php
<?php
$data = array(
    's_title'   => 'My Site',
    's_name'    => 'Alice',
    'b_premium' => true,
    'a_hobbies' => array('Reading', 'Travel', 'Code')
);
```

### 2. GABS template *(`template.gabs`)*

In the **GABS** template, place **the PHP array keys** in the right place with the correct syntax (see the following chapters).

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- variable -->
    <title>{s_title}</title>
</head>
<body>
    <!-- variable -->
    <h1>Hello {s_name}!</h1>

    <!-- conditional binary block -->
    {b_premium{
        <p class="premium">⭐ Premium Member</p>
    }b_premium{
        <p>Standard Member</p>
    }b_premium}

    <!-- loop block -->
    <ul>
    {a_hobbies{
        <li>{v}</li>
    }a_hobbies}
    </ul>
</body>
</html>
```

### 3. PHP render *(`index.php`)*

In your **PHP controller** (*`index.php`* for example), include **GABS** and the **PHP data**, create **an instance of GABS** and launch the **final HTML render** with the public method *`get()`*… and that's it!

```php
<?php
require_once 'Gabs.php'; // include the GABS engine
require_once 'data.php'; // include the data

$gabs = new Gabs(); // create a GABS instance
echo $gabs->get('template.gabs', $data); // launch the HTML render
```

**HTML output:**

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Site</title>
</head>
<body>
    <h1>Hello Alice!</h1>

    <p class="premium">⭐ Premium Member</p>

    <ul>
        <li>Reading</li>
        <li>Travel</li>
        <li>Code</li>
    </ul>
</body>
</html>
```

---

## ✨ Features

### Core

- 🎨 **Templates** simple and readable HTML
- 🔄 **Variables** (text, numbers, HTML)
- ⚖️ **Conditions** binary (true/false)
- 🔁 **Loops** on arrays
- 📎 **Includes** static, modularity
- 🔒 **Escaping** auto (XSS protection)
- ⚡ **Cache** smart (95% faster)
- 🧹 **Purification** auto cache cleanup

### Advanced

- 🔧 **Filters** format variables in the template
- 🔀 **Reverse sort** of arrays
- 🎯 **Item selection** (slicing, offset, limit)
- 📊 **Loop info** (pagination, sort, stats)
- 🌍 **Global variables** accessible in loops
- 🌐 **Dynamic includes** (multi-language, themes)
- 🎛️ **Flexible configuration** (granular)
- 🐛 **Debug mode** (data inspection)

---

## 📝 Syntax

### Variables *`{s_var}`*

**PHP data:**
```php
$data = array(
    's_title' => 'My Product',
    'n_price' => 29.99
);
```

**GABS template:**
```html
<h1>{s_title}</h1>
<p>Price: {n_price}</p>
```

**HTML output:**
```html
<h1>My Product</h1>
<p>Price: 29.99</p>
```

#### Escaping

**Data secured by default:**
```php
// PHP data
's_name' => '<script>alert("XSS")</script>',
```
```html
<!-- GABS template -->
<p>{s_name}</p>
```
```html
<!-- HTML output -->
<p>&lt;script&gt;alert("XSS")&lt;/script&gt;</p>
```

**Raw HTML with *`h_`* prefix when needed:**
```php
// PHP data
'h_content' => '<strong>Important</strong>',  // 'h_' prefix
```
```html
<!-- GABS template -->
<p>{h_content} to do…</p>
```
```html
<!-- HTML output -->
<p><strong>Important</strong> to do…</p>
```

**Raw data forced in the template *`|}`*:**
```php
// PHP data
's_html' => '<em>95&nbsp;%</em>', // protected by default, unless '|}' tag in template
```
```html
<!-- GABS template -->
<p>Performance at {s_html|}</p>  <!-- trailing '|}' = raw data -->
```
```html
<!-- HTML output -->
<p>Performance at <em>95&nbsp;%</em></p>
```

---

### Conditions *`{b_{ … }b_{ … }b_}`*

**PHP data:**
```php
'b_premium'  => true,
'b_verified' => true,
'b_error'    => false,
'b_active'   => true,
```

**Full GABS syntax:**
```html
{b_premium{
    <p>Content if TRUE</p>
}b_premium{
    <p>Content if FALSE</p>
}b_premium}
```

**HTML output:**
```html
<p>Content if TRUE</p> <!-- b_premium = true -->
```

**Short GABS syntax** *(always on a single line)* **:**
```html
<!-- Show only if TRUE '{b_{[' -->
{b_verified{[ <span>✓ Verified</span> }b_verified}

<!-- Show only if FALSE ']}b_}' -->
{b_error{ <span>❌ Error</span> ]}b_error}
```

**HTML output:**
```html
<span>✓ Verified</span> <!-- b_verified = true -->
<span>❌ Error</span>   <!-- b_error = false -->
```

**Dynamic classes example:**
```html
<button class="{b_active{[ active }b_active}">
    {b_active{ Active }b_active{ Inactive }b_active}
</button>
```

**HTML output:**
```html
<button class=" active "> <!-- b_active = true -->
    Active
</button>
```

---

### Loops *`{a_{ … }a_}`*

#### Associative arrays

**PHP data:**
```php
'a_users' => array(
    array('s_name' => 'Alice', 's_email' => 'alice@example.com', 'n_age' => 28),
    array('s_name' => 'Bob',   's_email' => 'bob@example.com',   'n_age' => 35)
)
```

**GABS template:**
```html
{a_users{
    <div class="user">
        <h3>{s_name}</h3>
        <p>{s_email}</p>
        <span>Age: {n_age}</span>
    </div>
}a_users}
```

**HTML output:**
```html
<div class="user">
    <h3>Alice</h3>
    <p>alice@example.com</p>
    <span>Age: 28</span>
</div>
<div class="user">
    <h3>Bob</h3>
    <p>bob@example.com</p>
    <span>Age: 35</span>
</div>
```

#### Indexed arrays

**Available special tags:**
- `{v}` = Value (scalar data)
- `{k}` = Index (starts at 0)
- `{c}` = Counter (starts at 1)

**PHP data:**
```php
'a_colors' => array('Red', 'Green', 'Blue')
```

**GABS template:**
```html
{a_colors{
    <li>#{c} - Index [{k}]: {v}</li>
}a_colors}
```

**HTML output:**
```html
<li>#1 - Index [0]: Red</li>
<li>#2 - Index [1]: Green</li>
<li>#3 - Index [2]: Blue</li>
```

#### Selection (Slicing) *`[start[ … ]length]`*

**First 5 items:**
```html
<!-- GABS template -->
{a_products{[0[
    <div>{s_name}</div>
]5]}a_products}
```

**Last 5 items:**
```html
<!-- GABS template -->
{a_products{[-5[
    <div>{s_name}</div>
]0]}a_products}
```

**Pagination (10 per page):**
```php
// PHP data
$page   = 2;
$offset = ($page - 1) * 10;  // = 10
```
```html
<!-- GABS template -->
{a_products{[<?= $offset ?>[
    <div>{s_name}</div>
]10]}a_products}
```

#### Reverse sort with *`!`*

```html
<!-- GABS template -->
{a_numbers{!
    <span>{v}</span>
}a_numbers}
```
```html
<!-- GABS template -->
{a_products{[-5[!
    <div>{s_name}</div>
]0]}a_products}
```

#### Loop info (metadata)

**Tag syntax:**
`{a_loopName_instanceNumber_infoCode}` **=** `{a_array_1_n}` **or** `{a_list_1_t}`

**6 available info tags** depending on the suffix used:
- `_b` = **begin**: the start number of the displayed array
- `_f` = **finish**: the end number of the displayed array
- `_n` = **number**: the number of items displayed (taking selection into account)
- `_t` = **total**: the total number of items in the array
- `_p` = **page**: the page number corresponding to the displayed selection
- `_s` = **sort**: sort indicator, ascending `/\` (normal) or descending `\/` (reverse)

**GABS template example:**
```html
{a_products{[10[
    <div>{s_name} - ${n_price}</div>
]10]}a_products}

<p>
    Showing: {a_products_1_b} to {a_products_1_f}
    of {a_products_1_t} (Page {a_products_1_p})
</p>
```

**HTML output:**
```html
Showing: 11 to 20 of 150 (Page 2)
```

#### Global variables in loops *`_g`*

**GABS** allows direct access to root-level variables within loops. To achieve this, it offers two different methods, managed in the configuration of the `glob` key, as follows:

- **1. Strict method** (default) = `'glob'=>true;`: This requires clearly specifying which variables should be made global to be accessible within loops, simply by adding the suffix *`_g`* to the key name in question (for example: *`s_global_variable_g`*);
- **2. General method** = `'glob'=>false`: This configuration forces **GABS** to make the **scope global** for all variables present at the first level of the main data table.

With the **strict method**, which is **highly recommended**, the *`_g`* suffix becomes active information in **GABS**, producing several beneficial effects:

- ✅ **limits processing =** faster and more efficient results
- ✅ **avoids collisions =** gives full control over data display
- ✅ **self-documenting =** greatly eases working on templates

**PHP data:**
```php
$data = array(
    's_currency_g' => '$',          // ← _g suffix = accessible everywhere
    's_shop_g'     => 'My Shop',    // ← _g suffix = accessible everywhere
    'a_products'   => array(
        array('s_name' => 'Laptop', 'n_price' => 899),
        array('s_name' => 'Mouse',  'n_price' => 29)
    )
);
```

**GABS template:**
```html
{a_products{
    <p>{s_name} — {s_currency_g}{n_price} · {s_shop_g}</p>
}a_products}
```

**HTML output:**
```html
<p>Laptop — $899 · My Shop</p>
<p>Mouse — $29 · My Shop</p>
```

> The `_g` suffix combines naturally with type prefixes: `s_site_g`, `n_tax_g`, `h_cdn_g`, etc.
> A dedicated chapter in the full documentation covers all available options.

---

### Includes

#### Static

**GABS template:**
```html
{includes/header.gabs}
{includes/menu.gabs}
```

**Path-traversal protection:**
```html
{includes/../../etc/passwd}  <!-- ❌ Blocked! -->
```

#### Dynamic ⭐

**Multi-language**
```php
// PHP data
's_lang' => 'en'
```
```html
<!-- GABS template -->
{includes/header_{s_lang}.gabs}
```
```html
<!-- GABS result -->
{includes/header_en.gabs}
```

**Themes**
```php
// PHP data
's_theme' => 'dark'
```
```html
<!-- GABS template -->
{includes/styles/{s_theme}/main.gabs}
```
```html
<!-- GABS result -->
{includes/styles/dark/main.gabs}
```

---

## 🔧 Filters ⭐

Filters allow you to **transform a variable directly in the template**, without touching the PHP data. This is the natural complement to the **"Logic-Less" philosophy**: PHP prepares the raw data, filters handle the presentation.

### GABS syntax

```html
<!-- Single filter -->
{s_name|f_upper}

<!-- Chained filters (applied left to right) -->
{s_name|f_trim|f_title}

<!-- Filter + raw output (trailing pipe = no escaping) -->
{h_bio|f_nl2br|}

<!-- No filter, raw output (unchanged behavior) -->
{h_content|}
```

### Setup

**Recommended structure:**

```
libs/
├── Gabs.php           ← engine (do not modify)
├── funcs_gabs.php     ← standard GABS filters (do not modify)
└── funcs_user.php     ← your custom filters
```

**Your business filters** *(`funcs_user.php`)* **:**

```php
<?php
$aFuncsUser = array();

// Examples to customize
$aFuncsUser['f_price']   = function($v) { return '$'.number_format((float)$v, 2); };
$aFuncsUser['f_excerpt'] = function($v) { return mb_substr(strip_tags($v), 0, 150).'…'; };
$aFuncsUser['f_ref']     = function($v) { return strtoupper(str_replace(' ', '-', trim($v))); };
```

**In your PHP controller** *(`index.php` for example)* **:**

```php
require_once 'libs/Gabs.php';        // load the GABS engine
require_once 'libs/funcs_gabs.php';  // standard GABS filter library
require_once 'libs/funcs_user.php';  // your filters (override standards if same name)

$aFuncs = array_merge($aFuncsGabs, $aFuncsUser); // merge both libraries

$gabs = new Gabs(); // create a GABS instance
echo $gabs->get('template.gabs', $data, $aFuncs); // launch the HTML render
```

> Filters are **entirely optional** — if `$aFuncs` is not provided, **GABS** works exactly as before. An unknown filter is **silently ignored** in production (visible in the HTML source during development in debug mode).

### Available filters *(`funcs_gabs.php`)*

Here is **a selection of the main filters available** in the *`funcs_gabs.php`* library (80+ filters).

**Strings**

| Filter | Description | Example |
|--------|-------------|---------|
| `f_upper` | Uppercase | `hello` → `HELLO` |
| `f_lower` | Lowercase | `HELLO` → `hello` |
| `f_ucfirst` | First letter uppercase | `alice` → `Alice` |
| `f_ucwords` | Each word capitalized | `alice smith` → `Alice Smith` |
| `f_trim` | Strip leading/trailing spaces | `  hello  ` → `hello` |
| `f_title` | Ucfirst + lowercase + trim | `  ALICE  ` → `Alice` |
| `f_name` | Uppercase + trim | `  alice  ` → `ALICE` |
| `f_slug` | Convert to URL slug | `My Title!` → `my-title` |
| `f_extract` | First 200 chars, no HTML | `<p>Long text…</p>` → `Long text…` |
| `f_strip` | Strip HTML tags | `<b>Text</b>` → `Text` |
| `f_trunc_50` | Truncate to 50 chars | — |
| `f_trunc_100` | Truncate to 100 chars | — |

**Numbers**

| Filter | Description | Example |
|--------|-------------|---------|
| `f_round_0` | Round to integer | `3.7` → `4` |
| `f_round_1` | Round to 1 decimal | `3.75` → `3.8` |
| `f_round_2` | Round to 2 decimals | `3.756` → `3.76` |
| `f_ceil` | Round up | `3.1` → `4` |
| `f_floor` | Round down | `3.9` → `3` |
| `f_abs` | Absolute value | `-5` → `5` |
| `f_num_2` | FR format, 2 decimals | `1234.5` → `1 234,50` |
| `f_num_dot_2` | US format, 2 decimals | `1234.5` → `1,234.50` |
| `f_eur` | Euro amount | `1234.5` → `1 234,50 €` |
| `f_usd` | Dollar amount | `1234.5` → `$1,234.50` |
| `f_pct` | Percentage | `12.5` → `12,5 %` |
| `f_pct_int` | Integer percentage | `12.5` → `13 %` |

**Dates** *(from a Unix timestamp)*

| Filter | Description | Example |
|--------|-------------|---------|
| `f_date` | FR format | `→ 31/12/2026` |
| `f_date_time` | FR format with time | `→ 31/12/2026 23:59` |
| `f_date_us` | US format | `→ 12/31/2026` |
| `f_time` | Time only | `→ 23:59` |
| `f_year` | Year only | `→ 2026` |
| `f_age` | Age in years | `→ 35 years` |

**Miscellaneous**

| Filter | Description | Example |
|--------|-------------|---------|
| `f_bool_yn_fr` | Boolean in French | `1` → `Oui` / `0` → `Non` |
| `f_bool_yn_en` | Boolean in English | `1` → `Yes` / `0` → `No` |
| `f_bool_ico` | Boolean as icon | `1` → `✅` / `0` → `❌` |
| `f_mask_email` | Mask email address | `alice@ex.com` → `al***@ex.com` |
| `f_mask_phone` | Mask phone number | `→ 06 ** ** ** 78` |
| `f_initials` | Initials | `Alice Smith` → `A.S.` |

### Concrete example

```php
// PHP data
$data = array(
    's_name'    => '  alice smith  ',
    's_bio'     => '<p>Passionate developer for 10 years.</p>',
    'n_price'   => 1234.5,
    'n_ts_born' => mktime(0, 0, 0, 6, 15, 1990),
    's_email'   => 'alice.smith@example.com',
);
```

```html
<!-- GABS template -->
<h1>{s_name|f_trim|f_title}</h1>
<p>{s_bio|f_strip|f_extract}</p>
<p>Price: {n_price|f_usd|}</p>
<p>Age: {n_ts_born|f_age}</p>
<p>Contact: {s_email|f_mask_email}</p>
```

```html
<!-- HTML output -->
<h1>Alice Smith</h1>
<p>Passionate developer for 10 years.</p>
<p>Price: $1,234.50</p>
<p>Age: 35 years</p>
<p>Contact: al***@example.com</p>
```

### Filter naming convention

It is **strongly recommended** to prefix your filters with *`f_`*:

```php
// ✅ Recommended — consistent with GABS conventions
$aFuncsUser['f_my_filter'] = function($v) { return strtoupper($v); };

// ⚠️ Works, but not recommended
$aFuncsUser['my_filter'] = function($v) { return strtoupper($v); };
```

> Unknown filters are **silently ignored**: the value is displayed as-is (during development, in "debug" mode, failed filters are visible in the HTML source).

---

## ⚙️ Configuration

### Development mode

```php
$gabs->conf(array(
    'cach' => false,    // Cache disabled
    'dbug' => true,     // Debug enabled
    'tpls' => 'views'   // Templates folder
));
```

### Production mode

```php
$gabs->conf(array(
    'cach' => true,     // Cache enabled
    'dbug' => false,    // Debug disabled
    'pure' => true,     // Auto cache purification
    'fold' => 'cache',  // Cache folder
    'tpls' => 'views'   // Templates folder
));
```

> The full list of configuration options is detailed in the documentation.

---

## ⚡ Performance

### Benchmarks

| Operation | Time | Notes |
|-----------|------|-------|
| Simple parse | ~3ms | ✅ Very fast |
| Complex parse | ~10ms | ✅ Performant |
| With ~12 filters | ~6–18ms | ✅ Reasonable |
| With cache | ~2.5ms | ✅ Major gain |

> Filters are applied only on the **first render** — with cache enabled, their cost becomes negligible.

### Smart cache

- **Automatic**: md5 of template + md5 of data
- **Significant gain**: parse → cache = up to 80% faster
- **Auto purification**: keeps the most recent files

---

## 🔒 Security

### XSS escaping

**Auto by default:**
```php
's_input' => '<script>alert("XSS")</script>'
// → &lt;script&gt;... ✅
```

**Filters and escaping:**

Filters are applied **before** automatic escaping. To display a filtered value **without escaping** (HTML content), use the trailing pipe *`|}`*:

```html
{h_bio|f_nl2br|}   <!-- nl2br filter applied, HTML preserved -->
```

### Path traversal

**Blocked automatically:**
```html
{includes/../../etc/passwd}  <!-- ❌ -->
{includes/../config.php}     <!-- ❌ -->
```

**Method:** `realpath()` + strict verification

### Final HTML cleanup

**GABS** performs a cleanup of the generated HTML **before the final output**: it looks for any remaining **orphan tags** and wraps them in HTML comments, so they can be easily spotted during development (this is a configurable feature).

---

## 💡 Full examples

### Blog

```html
<article>
    <h1>{s_title|f_title}</h1>

    <div class="meta">
        <span>By {s_author|f_ucwords}</span>
        <time>{n_ts_date|f_date}</time>
        {b_featured{[ <span class="badge">⭐ Featured</span> }b_featured}
    </div>

    <div class="content">
        {h_content|}
    </div>

    <div class="tags">
        {a_tags{ <a href="/tag/{v|f_slug}" class="tag">{v}</a> }a_tags}
    </div>
</article>

<section class="comments">
    <h2>{a_comments_1_t} comment(s)</h2>

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
            <p class="price">{n_price|f_usd|}</p>
            {b_stock{
                <button>Add to cart</button>
            }b_stock{
                <span class="out">Out of stock</span>
            }b_stock}
            {b_promo{[ <span class="badge">Sale!</span> }b_promo}
        </div>
    ]10]}a_products}
</div>

<div class="pagination">
    <p>Products {a_products_1_b} to {a_products_1_f} of {a_products_1_t}</p>
</div>
```

### Multi-language

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

**Data:**
```php
$lang = $_GET['lang'] ?? 'en';

$i18n = array(
    'en' => array('s_welcome' => 'Welcome!'),
    'fr' => array('s_welcome' => 'Bienvenue !')
);

$data = array_merge(
    array('s_lang' => $lang),
    $i18n[$lang]
);
```

---

## 🏷️ Naming conventions

### Recommended prefixes and security

| Prefix | Type | Example | Auto-Protection |
|--------|------|---------|-----------------|
| `s_` | String | `s_name` | ✅ escaping |
| `c_` | Code | `c_href` | ✅ escaping |
| `n_` | Number | `n_price` | ✅ escaping |
| `b_` | Boolean | `b_active` | ❌ raw data |
| `h_` | HTML | `h_content` | ❌ raw data |
| `a_` | Array | `a_users` | ✅ recursion |

**Benefits:**
- ✅ Quick read of the type and associated security
- ✅ Self-documenting data array
- ✅ Avoids type confusion

### Global suffix *`_g`*

Adding *`_g`* at the end of a key (strict method), tells **GABS** that the variable becomes global and must be accessible inside all loops:

```php
's_currency_g' => '$'    // available in {a_products{ ... }a_products}
'h_cdn_g'      => '...'  // available in {a_images{ ... }a_images}
```

---

## 🤝 Contributing

**GABS is open-source!**

**You can:**
- 🐛 Report bugs
- 💡 Suggest features
- 📝 Improve the docs
- ⭐ Star on GitHub!

---

## 📄 License

**GABS is free and open-source!**

**MIT License** - Copyright (c) 2026 FredoMkb

---

## 🙏 Credits

**Author:** FredoMkb

[**The Story of GABS**](https://github.com/fredomkb58/Gabs/blob/4b74cb66043f5692ca0c5c1c07b25a33eaa9ae6d/docs/GABS-story-en.md)

**Built with the help of:**
- 🤖 Claude-AI (Anthropic) — architecture, debugging, documentation
- 🤖 Gemini-AI (Google) — analysis, suggestions, examples
- 🤖 Various AI assistants — information, research, brainstorming
- 🌐 StackOverflow, php.net, MDN, regex101 and the PHP community

---

<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logic without noise && design without echo }**

**PHP/HTML Template Engine**

**simple • lightweight • fast • secure • logic-less**

[GitHub](https://github.com/fredomkb58/Gabs)

**Made with ❤️ from France 🇫🇷 for World 🌎**

</div>

---
