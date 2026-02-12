<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logic without noise && design without echo }**

**PHP/HTML template engine**

**simple • lightweight • fast • secure • logic-less**

*Version v0118*

[![PHP Version](https://img.shields.io/badge/PHP-5.6%2B-red)](https://php.net)
[![Version](https://img.shields.io/badge/version-0118-blue)](https://github.com/fredomkb58/gabs)
[![License](https://img.shields.io/badge/license-MIT-orange)](LICENSE)

</div>

---

## 📖 Table of contents

- [Why GABS?](#-why-gabs)
- [Installation](#-installation)
- [Quick start](#-quick-start)
- [Features](#-features)
- [Syntax](#-syntax)
- [Configuration](#%EF%B8%8F-configuration)
- [Performance](#-performance)
- [Security](#-security)
- [Full examples](#-full-examples)
- [Naming conventions](#%EF%B8%8F-naming-conventions)
- [Contributing](#-contributing)

---

## 🎯 Why GABS?

### The problem

The most popular template engines today are **powerful** but often **too complex** for simple projects:
- ❌ Heavy dependencies (Composer, frameworks)
- ❌ Steep learning curve
- ❌ Way too much for 80% of real-world projects
- ❌ Sometimes disappointing performance on simple use cases

### The GABS solution

**GABS** is a template engine that gets back to basics:
- ✅ **Single file** (zero dependencies)
- ✅ **Clear syntax** (learned in 15 minutes)
- ✅ **Ultra-fast** (~10ms without cache, ~2.5ms with)
- ✅ **Secure** (auto-escaping, path-traversal protection)
- ✅ **Lightweight** (< 1200 lines, ~30 KB)
- ✅ **Logic-Less** (logic = PHP ; design = HTML/GABS)

**GABS = The right tool for the right project** 🎯

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

### 1. Template (`template.gabs`)

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <title>{s_title}</title>
</head>
<body>
    <h1>Hello {s_name}!</h1>

    {b_premium{
        <p class="premium">⭐ Premium Member</p>
    }b_premium{
        <p>Standard Member</p>
    }b_premium}

    <ul>
    {a_hobbies{
        <li>{v}</li>
    }a_hobbies}
    </ul>
</body>
</html>
```

### 2. Data (PHP)

```php
<?php
require_once 'Gabs.php';
$gabs = new Gabs();

$data = array(
    's_title'   => 'My Website',
    's_name'    => 'Alice',
    'b_premium' => true,
    'a_hobbies' => array('Reading', 'Travel', 'Coding')
);
```

### 3. Render

```php
echo $gabs->get('template.gabs', $data);
```

**Output:**

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Website</title>
</head>
<body>
    <h1>Hello Alice!</h1>

    <p class="premium">⭐ Premium Member</p>

    <ul>
        <li>Reading</li>
        <li>Travel</li>
        <li>Coding</li>
    </ul>
</body>
</html>
```

---

## ✨ Features

### Core

- 🎨 Simple, readable HTML templates
- 🔄 Variables (strings, numbers, HTML)
- ⚖️ Binary conditions (true/false)
- 🔁 Array loops
- 📎 Static and dynamic includes
- 🔒 Auto-escaping (XSS protection)
- ⚡ Smart cache (up to 80% faster)
- 🧹 Automatic cache purification

### Advanced

- 📊 **Loop info tags** (pagination, sort, stats)
- 🎯 **Item selection** (slicing, offset, limit)
- 🔀 **Reverse sort** of arrays
- 🌍 **Global variables** accessible inside loops
- 🌐 **Dynamic includes** (multi-language, themes)
- 🎛️ **Flexible configuration** (granular)
- 🐛 **Debug mode** (data inspection)

---

## 📝 Syntax

### Variables

```html GABS template
<h1>{s_title}</h1>
<p>Price: {n_price}$</p>
```

**Data:**
```php PHP datas
$data = array(
    's_title' => 'My Product',
    'n_price' => 29.99
);
```

#### Escaping

**Default (secure):**
```php
's_name' => '<script>alert("XSS")</script>'
// Output: &lt;script&gt;alert("XSS")&lt;/script&gt; ✅
```

**Raw HTML (when needed):**
```php
'h_content' => '<strong>Important</strong>'  // h_ prefix
// Or: {s_content|}  (pipe in the template)
```

---

### Conditions

**Full syntax:**
```html
{b_premium{
    <p>Content if TRUE</p>
}b_premium{
    <p>Content if FALSE</p>
}b_premium}
```

**Short syntax** *(single line)***:**
```html
<!-- Show only if TRUE -->
{b_verified{[ <span>✓ Verified</span> }b_verified}

<!-- Show only if FALSE -->
{b_error{ <span>❌ Error</span> ]}b_error}
```

**Dynamic classes:**
```html
<button class="{b_active{[ active }b_active}">
    {b_active{ Active }b_active{ Inactive }b_active}
</button>
```

---

### Loops

#### Associative arrays

```html
{a_users{
    <div class="user">
        <h3>{s_name}</h3>
        <p>{s_email}</p>
        <span>Age: {n_age}</span>
    </div>
}a_users}
```

**Data:**
```php
'a_users' => array(
    array('s_name' => 'Alice', 's_email' => 'alice@example.com', 'n_age' => 28),
    array('s_name' => 'Bob',   's_email' => 'bob@example.com',   'n_age' => 35)
)
```

#### Indexed arrays

**Special tags:**
- `{v}` = Value
- `{k}` = Index
- `{c}` = Counter (starts at 1)

```html
{a_colors{
    <li>#{c} - Index [{k}]: {v}</li>
}a_colors}
```

**Data:**
```php
'a_colors' => array('Red', 'Green', 'Blue')
```

**Output:**
```html
<li>#1 - Index [0]: Red</li>
<li>#2 - Index [1]: Green</li>
<li>#3 - Index [2]: Blue</li>
```

#### Slicing

**First 5 items:**
```html
{a_products{[0[
    <div>{s_name}</div>
]5]}a_products}
```

**Last 5 items:**
```html
{a_products{[-5[
    <div>{s_name}</div>
]0]}a_products}
```

**Pagination (10 per page):**
```php
$page   = 2;
$offset = ($page - 1) * 10;  // = 10
```
```html
{a_products{[<?= $offset ?>[
    <div>{s_name}</div>
]10]}a_products}
```

#### Reverse sort

```html
{a_numbers{!
    <span>{v}</span>
}a_numbers}
```

#### Loop info tags ⭐

**Available tags:**
- `{a_array_1_b}` = Begin
- `{a_array_1_f}` = Finish
- `{a_array_1_n}` = Number (displayed count)
- `{a_array_1_t}` = Total
- `{a_array_1_p}` = Page
- `{a_array_1_s}` = Sort indicator (↓ or ↑)

**Example:**
```html
{a_products{[10[
    <div>{s_name} - {n_price}$</div>
]10]}a_products}

<p>
    Showing: {a_products_1_b} to {a_products_1_f}
    of {a_products_1_t} (Page {a_products_1_p})
</p>
```

**Output:**
```
Showing: 11 to 20 of 150 (Page 2)
```

#### Global variables in loops

By default, root-level variables are not accessible inside loops. To make them available across all loop items, simply add the **`_g`** suffix:

```php
$data = array(
    's_currency_g' => '$',          // ← _g suffix = available everywhere
    's_shop_g'     => 'My Shop',    // ← _g suffix = available everywhere
    'a_products'   => array(
        array('s_name' => 'Laptop', 'n_price' => 899),
        array('s_name' => 'Mouse',  'n_price' => 29)
    )
);
```

```html
{a_products{
    <p>{s_name} — {n_price}{s_currency_g} · {s_shop_g}</p>
}a_products}
```

**Output:**
```html
<p>Laptop — 899$ · My Shop</p>
<p>Mouse — 29$ · My Shop</p>
```

> The `_g` suffix combines naturally with type prefixes: `s_site_g`, `n_vat_g`, `u_cdn_g`, etc.
> A dedicated chapter in the full documentation covers all available options.

---

### Includes

#### Static

```html
{includes/header.gabs}
{includes/menu.gabs}
```

**Path-traversal protection:**
```html
{includes/../../etc/passwd}  <!-- ❌ Blocked! -->
```

#### Dynamic ⭐

**Multi-language:**
```php
's_lang' => 'en'
```
```html
{includes/header_{s_lang}.gabs}
<!-- Resolves to: {includes/header_en.gabs} -->
```

**Themes:**
```php
's_theme' => 'dark'
```
```html
{includes/styles/{s_theme}/main.gabs}
<!-- Resolves to: {includes/styles/dark/main.gabs} -->
```

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

> The full list of configuration options is covered in the documentation.

---

## ⚡ Performance

### Benchmarks

| Operation | Time | Other solutions |
|-----------|------|----------------|
| Simple parse | ~3ms | ✅ Very fast |
| Complex parse | ~10ms | ✅ Performant |
| With cache | ~2.5ms | ✅ Significant gain |

> Benchmarks measured on shared hosting with PHP 5.6 — results will be noticeably better on modern stacks (PHP 8.x, dedicated server, SSD).

### Smart cache

- **Automatic**: md5(template) + md5(data)
- **Significant gain**: parse → cache = up to 80% faster
- **Auto-purification**: keeps the most recent files

---

## 🔒 Security

### XSS escaping

**On by default:**
```php
's_input' => '<script>alert("XSS")</script>'
// → &lt;script&gt;... ✅
```

### Path traversal

**Automatically blocked:**
```html
{includes/../../etc/passwd}  <!-- ❌ -->
{includes/../config.php}     <!-- ❌ -->
```

**Method:** `realpath()` + strict path verification

---

## 💡 Full examples

### Blog

```html
<article>
    <h1>{s_title}</h1>

    <div class="meta">
        <span>By {s_author}</span>
        <time>{s_date}</time>
        {b_featured{[ <span class="badge">⭐ Featured</span> }b_featured}
    </div>

    <div class="content">
        {h_content|}
    </div>

    <div class="tags">
        {a_tags{ <a href="/tag/{v}" class="tag">{v}</a> }a_tags}
    </div>
</article>

<section class="comments">
    <h2>{a_comments_1_t} comment(s)</h2>

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
            <p class="price">{n_price}$</p>
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

| Prefix | Type | Example | Auto-protection |
|--------|------|---------|-----------------|
| `s_` | String | `s_name` | ✅ escaped |
| `c_` | Code | `c_href` | ✅ escaped |
| `n_` | Number | `n_price` | ✅ escaped |
| `b_` | Boolean | `b_active` | ❌ raw value |
| `h_` | HTML | `h_content` | ❌ raw value |
| `a_` | Array | `a_users` | ✅ recursive |

**Benefits:**
- ✅ Instantly readable type and security level
- ✅ Self-documenting data arrays
- ✅ No type confusion

### Global suffix `_g`

Adding `_g` at the end of a key makes a scalar variable accessible inside all loops:

```php
's_currency_g' => '$'    // available inside {a_products{ ... }a_products}
'u_cdn_g'      => '...'  // available inside {a_images{ ... }a_images}
```
---

## 📄 License

**GABS is free and open source!**

**MIT License** - Copyright (c) 2026 FredoMkb

---

## 🙏 Credits

**Author :** FredoMkb

**Built with the help of :**
- 🤖 Claude AI (Anthropic) — architecture, debugging, documentation
- 🤖 Various AI assistants — research and brainstorming
- 🌐 StackOverflow, php.net, MDN, regex101 and the PHP community

---

<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logic without noise && design without echo }**

**PHP/HTML template engine**

**simple • lightweight • fast • secure • logic-less**

[GitHub](https://github.com/fredomkb58/Gabs)

**Made with ❤️ from France 🇫🇷 for World 🌎**

</div>
