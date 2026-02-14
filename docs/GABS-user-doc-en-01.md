<div align="center">

![GABS-logo](https://github.com/fredomkb58/Gabs/blob/main/medias/Gabs-Logo-Blanc-256.svg)

# GABS

> **{ logic without noise && design without echo }**

*Version v0120*

</div>

---

# User Documentation - Level 1

**Learn GABS by example — from your first template to a complete application.**

---

## 📖 Table of contents

- [Introduction](#-introduction)
- [Case 1 — Blog](#-case-1--blog)
  - [Step 1 — The article page](#step-1--the-article-page-variables--filters)
  - [Step 2 — The conditions](#step-2--adding-conditions)
  - [Step 3 — The comments](#step-3--the-comment-list)
  - [Step 4 — The structure](#step-4--header-footer-and-includes)
- [Case 2 — Portfolio](#-case-2--portfolio-coming-soon)
- [Case 3 — Multi-language](#-case-3--multi-language-coming-soon)

---

## 💡 Introduction

This documentation guides you through **concrete, progressive examples**.

No need to read everything before you start: each step builds on the previous one, explanations are there when they're useful, and the code speaks for itself.

**What you need:**
- PHP 5.6 or higher
- A local server (XAMPP, WAMP, Laragon, or `php -S localhost:8000`)
- `Gabs.php` — [download here](https://github.com/fredomkb58/Gabs)

---

## 📝 Case 1 — Blog

A blog is the perfect example to discover GABS: there's text, structured data, conditional display, lists and files to include. We'll build it step by step.

By the end of this case, you'll know how to use **the essentials of GABS**.

---

### Step 1 — The article page *(variables + filters)*

**What we want to display:**

A blog article page with the title, author, publication date, an image, the content and some metadata (category, reading time).

---

#### File structure

Here's how to organise your project for this step:

```
my-blog/
├── Gabs.php          ← the GABS engine
├── funcs_gabs.php    ← the GABS filter library
├── index.php         ← your PHP controller
├── data.php          ← your PHP data
└── article.gabs      ← your HTML template
```

> **Why separate `data.php` and `index.php`?**
>
> For the example, it's clearer. In practice, your data will come from a database or a file, but the logic is the same: prepare a PHP array, pass it to GABS.

---

#### PHP data *(`data.php`)*

We start by preparing the data array. Each key in the array will become a tag in the template.

```php
<?php

$data = array(

    // --- Title and author ---
    's_title'    => 'Discovering Film Photography in 2026',
    's_author'   => 'marie dupont',   // lowercase intentionally: a filter will handle it
    's_avatar'   => 'marie-dupont.jpg',

    // --- Dates (Unix timestamps) ---
    // mktime( hour, minute, second, month, day, year )
    'n_ts_published' => mktime(9, 30, 0, 1, 15, 2026),  // January 15, 2026
    'n_ts_updated'   => mktime(14, 0, 0, 2, 3, 2026),   // February 3, 2026

    // --- Main image ---
    'h_img_url'  => '/images/articles/film-photography-2026.jpg',  // h_ prefix = no escaping
    's_img_alt'  => 'Film cameras on a wooden table',

    // --- Content ---
    // The h_ prefix means this data contains HTML: it will not be escaped
    'h_content'  => '<p>Film photography is experiencing a renewed interest…</p>
                     <p>Between nostalgia and a search for authenticity…</p>',

    // --- Metadata ---
    's_category'  => 'photography',
    'n_read_time' => 7,   // reading time in minutes

    // --- Canonical URL (for links) ---
    'c_url_author' => '/author/marie-dupont',   // c_ prefix = code, escaped but not modified

);
```

> **What are prefixes?**
>
> GABS uses the first 2 characters of each key to know how to handle the data:
> - `s_` → **string**: text, automatically escaped (XSS protection)
> - `n_` → **number**: number, automatically escaped
> - `h_` → **html**: raw HTML content, displayed as-is *(use only with trusted data)*
> - `c_` → **code**: URL or HTML attribute, automatically escaped

---

#### The GABS template *(`article.gabs`)*

Now the template. We place the PHP array keys between curly braces `{ }` in the right place in the HTML.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{s_title|f_title}</title>  <!-- f_title: first letter uppercase, rest lowercase -->
</head>
<body>

<article class="article">

    <!-- ============================================================
         ARTICLE HEADER
         ============================================================ -->

    <header class="article-header">

        <!-- Title: we apply f_title to normalise the case -->
        <h1>{s_title|f_title}</h1>

        <div class="article-meta">

            <!-- Author: f_ucwords capitalises each word      -->
            <!-- e.g. "marie dupont" becomes "Marie Dupont"   -->
            <a href="{c_url_author}" class="author">
                <img src="/images/avatars/{s_avatar}" alt="{s_author|f_ucwords}">
                <span>{s_author|f_ucwords}</span>
            </a>

            <!-- Publication date: f_date formats the timestamp as "01/15/2026" -->
            <time class="published">
                Published on {n_ts_published|f_date_us}
            </time>

            <!-- Update date: f_date_time adds the time "02/03/2026 14:00" -->
            <time class="updated">
                Updated on {n_ts_updated|f_date_time}
            </time>

        </div>

        <!-- Metadata: category and reading time -->
        <div class="article-tags">

            <!-- f_ucfirst: first letter uppercase                   -->
            <!-- e.g. "photography" becomes "Photography"            -->
            <span class="category">{s_category|f_ucfirst}</span>

            <!-- f_num: formats the number                           -->
            <!-- Not very useful for 7, but a good habit for numbers -->
            <span class="read-time">{n_read_time|f_num} min read</span>

        </div>

    </header>

    <!-- ============================================================
         MAIN IMAGE
         ============================================================ -->

    <!-- h_img_url: h_ prefix so no escaping needed      -->
    <!-- The trailing '|}' on h_ is optional but explicit -->
    <figure class="article-figure">
        <img src="{h_img_url|}" alt="{s_img_alt}">
        <figcaption>{s_img_alt}</figcaption>
    </figure>

    <!-- ============================================================
         ARTICLE CONTENT
         ============================================================ -->

    <!-- h_content contains HTML: we use '|}' to display it as-is -->
    <div class="article-content">
        {h_content|}
    </div>

</article>

</body>
</html>
```

> **How do filters work?**
>
> Add `|f_filter_name` right after the key, inside the curly braces.
> You can chain several: `{s_name|f_trim|f_title}` — they apply left to right.
> The trailing `|}` (pipe without a filter) means "display the raw data, without escaping".

---

#### The PHP controller *(`index.php`)*

This is where everything comes together: we load GABS, the filters, the data, and launch the render.

```php
<?php

// --- 1. Load the GABS engine and the filter library ---
require_once 'Gabs.php';
require_once 'funcs_gabs.php';  // gives access to $aFuncsGabs

// --- 2. Load the data ---
require_once 'data.php';        // gives access to $data

// --- 3. Create a GABS instance ---
$gabs = new Gabs();

// --- 4. Configure GABS for development ---
// cach => false : cache disabled (practical to see changes live)
// dbug => true  : debug mode enabled (shows data if {_} is in the template)
$gabs->conf(array(
    'cach' => false,
    'dbug' => true,
));

// --- 5. Launch the render and display the result ---
echo $gabs->get('article.gabs', $data, $aFuncsGabs);
//                   ↑             ↑         ↑
//              template         data     filters
```

> **Why `dbug => true` in development?**
>
> This activates the special tag `{_|}`: if you add it to your template, GABS will display all your data in plain text — very useful for checking what your array contains.

---

#### The HTML output

Opening `index.php` in your browser, GABS will merge the data and the template to produce this HTML:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discovering Film Photography In 2026</title>
</head>
<body>

<article class="article">

    <header class="article-header">

        <h1>Discovering Film Photography In 2026</h1>

        <div class="article-meta">

            <a href="/author/marie-dupont" class="author">
                <img src="/images/avatars/marie-dupont.jpg" alt="Marie Dupont">
                <span>Marie Dupont</span>
            </a>

            <time class="published">
                Published on 01/15/2026
            </time>

            <time class="updated">
                Updated on 02/03/2026 14:00
            </time>

        </div>

        <div class="article-tags">
            <span class="category">Photography</span>
            <span class="read-time">7 min read</span>
        </div>

    </header>

    <figure class="article-figure">
        <img src="/images/articles/film-photography-2026.jpg"
             alt="Film cameras on a wooden table">
        <figcaption>Film cameras on a wooden table</figcaption>
    </figure>

    <div class="article-content">
        <p>Film photography is experiencing a renewed interest…</p>
        <p>Between nostalgia and a search for authenticity…</p>
    </div>

</article>

</body>
</html>
```

---

#### What we learned in this step

| Concept | What we covered |
|---------|----------------|
| **Prefixes** | `s_` `n_` `h_` `c_` — each prefix has a role and a protection level |
| **Variables** | `{s_title}` — the basic syntax |
| **Filters** | `{s_author\|f_ucwords}` — transform a value in the template |
| **Chaining** | `{s_name\|f_trim\|f_title}` — multiple filters in sequence |
| **Raw data** | `{h_content\|}` — display HTML without escaping |
| **Timestamps** | `{n_ts_published\|f_date_us}` — format a date from a timestamp |
| **Configuration** | `$gabs->conf()` — adapt GABS to your environment |

---

> 🎯 **Ready for the next step?**
>
> In Step 2, we'll enrich this page with **conditions**: display a "Featured" badge, manage the article status, add a dynamic CSS class based on the category.

---

*— end of Step 1 —*

---

### Step 2 — Adding conditions *(true/false)*

**What we want to do:**

Enrich the article page with elements that show or hide depending on the data: a "Featured" badge, a warning if the article is a draft, and a dynamic CSS class based on the category.

---

#### What we add to the data *(`data.php`)*

We extend the existing `$data` array with three new boolean keys — the `b_` prefix tells GABS this is a condition.

```php
<?php

$data = array(

    // --- (all data from Step 1 remains unchanged) ---
    's_title'        => 'Discovering Film Photography in 2026',
    's_author'       => 'marie dupont',
    's_avatar'       => 'marie-dupont.jpg',
    'n_ts_published' => mktime(9, 30, 0, 1, 15, 2026),
    'n_ts_updated'   => mktime(14, 0, 0, 2, 3, 2026),
    'h_img_url'      => '/images/articles/film-photography-2026.jpg',
    's_img_alt'      => 'Film cameras on a wooden table',
    'h_content'      => '<p>Film photography is experiencing a renewed interest…</p>',
    's_category'     => 'photography',
    'n_read_time'    => 7,
    'c_url_author'   => '/author/marie-dupont',

    // --- New boolean data (Step 2) ---
    // Booleans use the b_ prefix and only contain true or false
    'b_featured' => true,   // true  = "Featured" article
    'b_draft'    => false,  // false = published article (not a draft)
    'b_photo'    => true,   // true  = photo category (for the CSS class)

);
```

> **Why separate booleans?**
>
> In Logic-Less, it's PHP that decides whether something is true or false — not the template. The template simply displays based on that decision. This clear separation is what makes the code maintainable.

---

#### Conditions in the template *(`article.gabs`)*

GABS offers two syntaxes for conditions. We'll use both in this example.

**Full syntax** — when there's content to display in both cases (true AND false):

```
{b_variable{
    content if TRUE
}b_variable{
    content if FALSE
}b_variable}
```

**Short syntax** — when you only display something in one case:

```
{b_variable{[ content if TRUE only }b_variable}
{b_variable{ content if FALSE only ]}b_variable}
```

> ⚠️ **Technical constraint — remember this:**
>
> The short syntax **must always fit on a single line**, with no line break inside. If your content is long or multiline, you must use the full syntax instead.

Here is the updated template — we only show the modified or added parts:

```html
<article class="article">

    <header class="article-header">

        <h1>{s_title|f_title}</h1>

        <!-- ============================================================
             "FEATURED" BADGE — short syntax, display if TRUE
             {b_featured{[ … }b_featured} = "show if b_featured is true"
             ⚠️ SHORT SYNTAX = ALWAYS ON A SINGLE LINE
             ============================================================ -->
        {b_featured{[ <span class="badge badge-featured">⭐ Featured</span> }b_featured}

        <!-- ============================================================
             DRAFT WARNING — short syntax, display if FALSE
             {b_draft{ … ]}b_draft} = "show if b_draft is false"
             ⚠️ SHORT SYNTAX = ALWAYS ON A SINGLE LINE
             Here: if the article is NOT a draft, we show "Published"
             ============================================================ -->
        {b_draft{ <span class="badge badge-published">✓ Published</span> ]}b_draft}

        <!-- ============================================================
             IF b_draft is true (draft), show this warning
             ⚠️ SHORT SYNTAX = ALWAYS ON A SINGLE LINE
             ============================================================ -->
        {b_draft{[ <div class="alert alert-draft">⚠️ This article is a draft — not visible to the public.</div> }b_draft}

        <div class="article-meta">
            <a href="{c_url_author}" class="author">
                <img src="/images/avatars/{s_avatar}" alt="{s_author|f_ucwords}">
                <span>{s_author|f_ucwords}</span>
            </a>
            <time class="published">Published on {n_ts_published|f_date_us}</time>
            <time class="updated">Updated on {n_ts_updated|f_date_time}</time>
        </div>

        <!-- ============================================================
             DYNAMIC CSS CLASS based on category
             {b_photo{[ photo }b_photo} injects "photo" into the class attribute
             if b_photo is true — nothing is displayed if false
             ============================================================ -->
        <div class="article-tags article-tags--{b_photo{[ photo }b_photo}">
            <span class="category">{s_category|f_ucfirst}</span>
            <span class="read-time">{n_read_time|f_num} min read</span>
        </div>

    </header>

    <figure class="article-figure">
        <img src="{h_img_url|}" alt="{s_img_alt}">
        <figcaption>{s_img_alt}</figcaption>
    </figure>

    <!-- ============================================================
         FULL SYNTAX — two alternative contents
         If b_featured is true  : show the "Featured" intro
         If b_featured is false : show the standard intro
         ============================================================ -->
    {b_featured{
        <p class="article-intro article-intro--featured">
            ⭐ Article selected by the editorial team — enjoy reading!
        </p>
    }b_featured{
        <p class="article-intro">
            Enjoy reading!
        </p>
    }b_featured}

    <div class="article-content">
        {h_content|}
    </div>

</article>
```

---

#### The HTML output

With `b_featured = true`, `b_draft = false` and `b_photo = true`, GABS produces:

```html
<article class="article">

    <header class="article-header">

        <h1>Discovering Film Photography In 2026</h1>

        <!-- b_featured = true → badge displayed -->
        <span class="badge badge-featured">⭐ Featured</span>

        <!-- b_draft = false → "Published" displayed (FALSE syntax) -->
        <span class="badge badge-published">✓ Published</span>

        <!-- b_draft = false → draft warning NOT displayed -->

        <div class="article-meta">
            <a href="/author/marie-dupont" class="author">
                <img src="/images/avatars/marie-dupont.jpg" alt="Marie Dupont">
                <span>Marie Dupont</span>
            </a>
            <time class="published">Published on 01/15/2026</time>
            <time class="updated">Updated on 02/03/2026 14:00</time>
        </div>

        <!-- b_photo = true → "photo" class injected -->
        <div class="article-tags article-tags--photo">
            <span class="category">Photography</span>
            <span class="read-time">7 min read</span>
        </div>

    </header>

    <figure class="article-figure">
        <img src="/images/articles/film-photography-2026.jpg"
             alt="Film cameras on a wooden table">
        <figcaption>Film cameras on a wooden table</figcaption>
    </figure>

    <!-- b_featured = true → "Featured" intro displayed -->
    <p class="article-intro article-intro--featured">
        ⭐ Article selected by the editorial team — enjoy reading!
    </p>

    <div class="article-content">
        <p>Film photography is experiencing a renewed interest…</p>
    </div>

</article>
```

> **Tip:**
>
> To test your conditions, temporarily set `b_draft => true` in your data and reload the page — you'll see the draft warning appear and the "Published" badge disappear. That's the power of Logic-Less: change the data, the template adapts on its own.

---

#### What we learned in this step

| Concept | What we covered |
|---------|----------------|
| **`b_` prefix** | Booleans trigger conditions in GABS |
| **Full syntax** | `{b_var{ true }b_var{ false }b_var}` — two alternative contents |
| **Short TRUE syntax** | `{b_var{[ … }b_var}` — displayed only if true — **on a single line** |
| **Short FALSE syntax** | `{b_var{ … ]}b_var}` — displayed only if false — **on a single line** |
| **Dynamic class** | `class="tag--{b_var{[ photo }b_var}"` — injection into an attribute |
| **Logic-Less** | The template decides nothing — it displays what PHP tells it |

---

> 🎯 **Ready for the next step?**
>
> In Step 3, we'll display the **comment list** — and discover loops, associative arrays, and loop info to display the total number of comments.

---

*— end of Step 2 —*

---

### Step 3 — The comment list *(loops)*

**What we want to do:**

Display the list of comments below the article with the author's name, date and message — then show the total number of comments as the section title.

---

#### What we add to the data *(`data.php`)*

An array of arrays: each comment is itself an associative array.

```php
// --- Comments (Step 3) ---
// a_ = array: GABS will loop over it automatically
'a_comments' => array(

    array(
        's_author'  => 'jean martin',
        'n_ts_date' => mktime(10, 15, 0, 1, 16, 2026),
        's_text'    => 'Great article, thanks for sharing!',
        'b_author'  => false,  // false = regular reader (not the blog author)
    ),
    array(
        's_author'  => 'marie dupont',
        'n_ts_date' => mktime(11, 30, 0, 1, 16, 2026),
        's_text'    => 'Thank you Jean, glad you enjoyed it!',
        'b_author'  => true,   // true = the blog author is replying
    ),
    array(
        's_author'  => 'sophie leclerc',
        'n_ts_date' => mktime(14, 0, 0, 1, 17, 2026),
        's_text'    => 'I got back into film photography last year — what a revelation!',
        'b_author'  => false,
    ),

),
```

> **Inside a loop**, GABS only sees the data of the current array item — `s_author`, `n_ts_date`, etc. Root-level variables (`s_title`, `b_featured`…) are not directly accessible. We'll see how to fix that in Step 4 with the `_g` suffix.

---

#### Loops in the template *(`article.gabs`)*

We add the comments section after the article content:

```html
    <!-- ============================================================
         COMMENTS SECTION
         {a_comments_1_t} = TOTAL number of comments in the array
         The "1" refers to the 1st instance of this loop in the template
         ============================================================ -->
    <section class="comments">

        <h2>{a_comments_1_t} comment(s)</h2>

        <!-- ============================================================
             LOOP over a_comments
             Everything between {a_comments{ and }a_comments}
             will be repeated for each comment in the array
             ============================================================ -->
        {a_comments{

            <!-- b_author: different CSS class if this is the blog author -->
            <!-- ⚠️ Short syntax = always on a single line                -->
            <div class="comment {b_author{[ comment--author }b_author}">

                <div class="comment-header">

                    <!-- f_ucwords: "jean martin" → "Jean Martin" -->
                    <strong>{s_author|f_ucwords}</strong>

                    <!-- f_elapsed: displays "2 days ago", "1 h ago"… -->
                    <time>{n_ts_date|f_elapsed}</time>

                    <!-- "Author" badge only if b_author = true        -->
                    <!-- ⚠️ Short syntax = always on a single line     -->
                    {b_author{[ <span class="badge-author">✍️ Author</span> }b_author}

                </div>

                <!-- s_text: automatically escaped text (s_ prefix) -->
                <p class="comment-text">{s_text}</p>

            </div>

        }a_comments}

    </section>
```

---

#### The HTML output

```html
<section class="comments">

    <h2>3 comment(s)</h2>

    <div class="comment">
        <div class="comment-header">
            <strong>Jean Martin</strong>
            <time>2 d ago</time>
        </div>
        <p class="comment-text">Great article, thanks for sharing!</p>
    </div>

    <div class="comment comment--author">
        <div class="comment-header">
            <strong>Marie Dupont</strong>
            <time>2 d ago</time>
            <span class="badge-author">✍️ Author</span>
        </div>
        <p class="comment-text">Thank you Jean, glad you enjoyed it!</p>
    </div>

    <div class="comment">
        <div class="comment-header">
            <strong>Sophie Leclerc</strong>
            <time>1 d ago</time>
        </div>
        <p class="comment-text">I got back into film photography last year — what a revelation!</p>
    </div>

</section>
```

---

#### What we learned in this step

| Concept | What we covered |
|---------|----------------|
| **`a_` prefix** | Array = automatic loop in GABS |
| **Simple loop** | `{a_var{ … }a_var}` — repeated for each item |
| **Data inside the loop** | Each item has its own keys (`s_`, `n_`, `b_`…) |
| **Loop info** | `{a_comments_1_t}` — total number of elements |
| **Conditions in loop** | `b_author` works exactly like at root level |
| **Filters in loop** | `f_ucwords`, `f_elapsed` — identical to Step 1 |

---

> 🎯 **Ready for the next step?**
>
> In Step 4, we'll structure everything with **includes** — a shared `header.gabs` and `footer.gabs`, and we'll discover the `_g` suffix to make variables accessible inside all loops.

---

*— end of Step 3 —*

---

### Step 4 — Header, footer and includes *(modularity + global variables)*

**What we want to do:**

Extract the header and footer into separate reusable files, and make the blog name accessible inside loops using the `_g` suffix.

---

#### The new file structure

```
my-blog/
├── Gabs.php
├── funcs_gabs.php
├── index.php
├── data.php
├── article.gabs          ← main template (lighter)
└── includes/
    ├── header.gabs        ← new: site header
    └── footer.gabs        ← new: site footer
```

---

#### What we add to the data *(`data.php`)*

Two new root-level keys — one with the `_g` suffix:

```php
// --- Site data (Step 4) ---
's_site_name'   => 'The Film Blog',  // blog name, root level

// The _g suffix makes this variable accessible inside all loops
// Without _g, it would be invisible from within {a_comments{ … }a_comments}
's_site_name_g' => 'The Film Blog',  // same value, available everywhere

'c_url_home'    => '/',              // link to homepage
```

> **Why the `_g` suffix?**
>
> By default (with the initial configuration), root-level variables are not passed inside loops — this is a deliberate choice for performance and clarity. Adding `_g` to a key name is the explicit signal: *"this variable must be accessible everywhere"*. Without it, `{s_site_name}` inside `{a_comments{ … }a_comments}` would display nothing.

---

#### The header *(`includes/header.gabs`)*

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- s_title comes from the current page data -->
    <title>{s_title|f_title} — {s_site_name}</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<header class="site-header">
    <a href="{c_url_home}" class="site-logo">
        <!-- f_upper: "The Film Blog" → "THE FILM BLOG" -->
        {s_site_name|f_upper}
    </a>
    <nav>
        <a href="/">Home</a>
        <a href="/articles">Articles</a>
        <a href="/contact">Contact</a>
    </nav>
</header>

<main class="site-main">
```

---

#### The footer *(`includes/footer.gabs`)*

```html
</main>

<footer class="site-footer">
    <!-- f_year: timestamp → current year -->
    <p>© {n_ts_published|f_year} {s_site_name} — All rights reserved</p>
</footer>

</body>
</html>
```

---

#### The updated main template *(`article.gabs`)*

The template becomes much lighter: we remove everything that goes into header and footer, and add the includes.

```html
<!-- ============================================================
     INCLUDE HEADER
     GABS replaces this line with the content of header.gabs
     The path is relative to the working folder (or to 'tpls')
     ============================================================ -->
{includes/header.gabs}

<article class="article">

    <header class="article-header">
        <h1>{s_title|f_title}</h1>
        {b_featured{[ <span class="badge badge-featured">⭐ Featured</span> }b_featured}
        {b_draft{ <span class="badge badge-published">✓ Published</span> ]}b_draft}
        {b_draft{[ <div class="alert alert-draft">⚠️ This article is a draft.</div> }b_draft}

        <div class="article-meta">
            <a href="{c_url_author}" class="author">
                <img src="/images/avatars/{s_avatar}" alt="{s_author|f_ucwords}">
                <span>{s_author|f_ucwords}</span>
            </a>
            <time class="published">Published on {n_ts_published|f_date_us}</time>
            <time class="updated">Updated on {n_ts_updated|f_date_time}</time>
        </div>

        <div class="article-tags article-tags--{b_photo{[ photo }b_photo}">
            <span class="category">{s_category|f_ucfirst}</span>
            <span class="read-time">{n_read_time|f_num} min read</span>
        </div>
    </header>

    <figure class="article-figure">
        <img src="{h_img_url|}" alt="{s_img_alt}">
        <figcaption>{s_img_alt}</figcaption>
    </figure>

    {b_featured{
        <p class="article-intro article-intro--featured">
            ⭐ Article selected by the editorial team — enjoy reading!
        </p>
    }b_featured{
        <p class="article-intro">Enjoy reading!</p>
    }b_featured}

    <div class="article-content">
        {h_content|}
    </div>

</article>

<!-- ============================================================
     COMMENTS SECTION
     s_site_name_g is accessible here thanks to the _g suffix
     even though it is defined at the root level of the data
     ============================================================ -->
<section class="comments">

    <h2>{a_comments_1_t} comment(s) — {s_site_name_g}</h2>

    {a_comments{

        <div class="comment {b_author{[ comment--author }b_author}">
            <div class="comment-header">
                <strong>{s_author|f_ucwords}</strong>
                <time>{n_ts_date|f_elapsed}</time>
                {b_author{[ <span class="badge-author">✍️ Author</span> }b_author}
            </div>
            <p class="comment-text">{s_text}</p>
        </div>

    }a_comments}

</section>

<!-- ============================================================
     INCLUDE FOOTER
     ============================================================ -->
{includes/footer.gabs}
```

---

#### The final controller *(`index.php`)*

We add the working folder configuration so GABS knows where to find the templates:

```php
<?php

require_once 'Gabs.php';
require_once 'funcs_gabs.php';
require_once 'data.php';

$gabs = new Gabs();

$gabs->conf(array(
    'cach' => false,
    'dbug' => true,
    'tpls' => '',       // root templates folder (empty = current folder)
));

echo $gabs->get('article.gabs', $data, $aFuncsGabs);
```

---

#### What we learned in this step

| Concept | What we covered |
|---------|----------------|
| **Static includes** | `{includes/header.gabs}` — insert a file into a template |
| **Modularity** | Header and footer shared across all site pages |
| **`_g` suffix** | Makes a root variable accessible inside all loops |
| **`tpls`** | Configuration option for the templates folder |

---

#### Summary of Case 1 — Blog

In 4 steps, we built a complete article page and covered **all the fundamentals of GABS**:

| Step | Concept |
|------|---------|
| 1 | Variables, prefixes, filters |
| 2 | Binary conditions (full and short) |
| 3 | Loops, loop info, nested data |
| 4 | Includes, modularity, global variables `_g` |

The project is now structured, maintainable, and ready to grow. That's exactly the spirit of GABS. 🎯

---

*— end of Case 1 — Blog —*
