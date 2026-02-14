<?php 
/**
 * GABS - funcs_gabs - Filtres par défaut - default filters
 * @version     0120 - PHP 5.6+
 * @copyright   FredoMkb © 2026 
 * @github      https://github.com/fredomkb58/Gabs.git
 * @credits     Claude-AI (Anthropic) 
 * ---
 * Ne modifiez pas ce fichier — utilisez "funcs_user.php" pour vos filtres personnalisés.
 * Do not modify this file — use "funcs_user.php" for your custom filters.
 */

$aFuncsGabs = array();

/*----------------------------------------------------------------------*/
//  STRINGS — Chaînes de caractères
/*----------------------------------------------------------------------*/

// Casse — Case
$aFuncsGabs['f_upper']      = function($v) { return strtoupper($v); };
$aFuncsGabs['f_lower']      = function($v) { return strtolower($v); };
$aFuncsGabs['f_ucfirst']    = function($v) { return ucfirst($v); };
$aFuncsGabs['f_ucwords']    = function($v) { return ucwords($v); };

// Nettoyage — Cleanup
$aFuncsGabs['f_trim']       = function($v) { return trim($v); };
$aFuncsGabs['f_ltrim']      = function($v) { return ltrim($v); };
$aFuncsGabs['f_rtrim']      = function($v) { return rtrim($v); };

// Formatage HTML — HTML formatting
$aFuncsGabs['f_nl2br']      = function($v) { return nl2br($v); };
$aFuncsGabs['f_strip']      = function($v) { return strip_tags($v); };

// Longueur — Length
$aFuncsGabs['f_len']        = function($v) { return (string)mb_strlen($v); };
$aFuncsGabs['f_wcount']     = function($v) { return (string)str_word_count($v); };

// Combinaisons courantes — Common combinations (semantic filters)
$aFuncsGabs['f_clean']      = function($v) { return trim($v); };
$aFuncsGabs['f_title']      = function($v) { return ucfirst(strtolower(trim($v))); };
$aFuncsGabs['f_name']       = function($v) { return strtoupper(trim($v)); };
$aFuncsGabs['f_slug']       = function($v) {
    $v = strtolower(trim($v));
    $v = preg_replace('/[^a-z0-9]+/', '-', $v);
    return trim($v, '-');
};
$aFuncsGabs['f_extract']    = function($v) { return mb_substr(strip_tags(trim($v)), 0, 200).'…'; };

// Troncature — Truncation
$aFuncsGabs['f_trunc_50']   = function($v) { return mb_substr($v, 0, 50); };
$aFuncsGabs['f_trunc_100']  = function($v) { return mb_substr($v, 0, 100); };
$aFuncsGabs['f_trunc_150']  = function($v) { return mb_substr($v, 0, 150); };
$aFuncsGabs['f_trunc_200']  = function($v) { return mb_substr($v, 0, 200); };
$aFuncsGabs['f_trunc_250']  = function($v) { return mb_substr($v, 0, 250); };

// Répétition & remplissage — Repeat & padding
$aFuncsGabs['f_repeat_2']   = function($v) { return str_repeat($v, 2); };
$aFuncsGabs['f_repeat_3']   = function($v) { return str_repeat($v, 3); };
$aFuncsGabs['f_pad_l10']    = function($v) { return str_pad($v, 10, ' ', STR_PAD_LEFT); };
$aFuncsGabs['f_pad_r10']    = function($v) { return str_pad($v, 10, ' ', STR_PAD_RIGHT); };
$aFuncsGabs['f_pad_0_5']    = function($v) { return str_pad($v, 5, '0', STR_PAD_LEFT); };

// Encodage — Encoding
$aFuncsGabs['f_base64']     = function($v) { return base64_encode($v); };
$aFuncsGabs['f_urlencode']  = function($v) { return urlencode($v); };
$aFuncsGabs['f_urldecode']  = function($v) { return urldecode($v); };
$aFuncsGabs['f_rot13']      = function($v) { return str_rot13($v); };
$aFuncsGabs['f_md5']        = function($v) { return md5($v); };

// Inspection — Inspection
$aFuncsGabs['f_reverse']    = function($v) { return strrev($v); };
$aFuncsGabs['f_wordwrap']   = function($v) { return wordwrap($v, 75, "\n", true); };

/*----------------------------------------------------------------------*/
//  NUMBERS — Nombres
/*----------------------------------------------------------------------*/

// Arrondis — Rounding
$aFuncsGabs['f_round_0']      = function($v) { return (string)round((float)$v); };
$aFuncsGabs['f_round_1']    = function($v) { return (string)round((float)$v, 1); };
$aFuncsGabs['f_round_2']    = function($v) { return (string)round((float)$v, 2); };
$aFuncsGabs['f_ceil']       = function($v) { return (string)ceil((float)$v); };
$aFuncsGabs['f_floor']      = function($v) { return (string)floor((float)$v); };
$aFuncsGabs['f_abs']        = function($v) { return (string)abs((float)$v); };

// Formatage — Formatting
$aFuncsGabs['f_num_0']        = function($v) { return number_format((float)$v, 0, ',', ' '); };
$aFuncsGabs['f_num_2']      = function($v) { return number_format((float)$v, 2, ',', ' '); };
$aFuncsGabs['f_num_dot_0']    = function($v) { return number_format((float)$v, 0, '.', ','); };
$aFuncsGabs['f_num_dot_2']  = function($v) { return number_format((float)$v, 2, '.', ','); };

// Monnaie — Currency (formatage seul, sans symbole — symbol to add in template)
$aFuncsGabs['f_eur']        = function($v) { return number_format((float)$v, 2, ',', ' ').' €'; };
$aFuncsGabs['f_usd']        = function($v) { return '$'.number_format((float)$v, 2, '.', ','); };
$aFuncsGabs['f_gbp']        = function($v) { return '£'.number_format((float)$v, 2, '.', ','); };

// Pourcentage — Percentage
$aFuncsGabs['f_pct']        = function($v) { return number_format((float)$v, 1, ',', ' ').' %'; };
$aFuncsGabs['f_pct_int']    = function($v) { return (string)round((float)$v).' %'; };

// Conversion — Conversion
$aFuncsGabs['f_km_mi']      = function($v) { return (string)round((float)$v * 0.621371, 2); };
$aFuncsGabs['f_mi_km']      = function($v) { return (string)round((float)$v * 1.60934, 2); };
$aFuncsGabs['f_kg_lb']      = function($v) { return (string)round((float)$v * 2.20462, 2); };
$aFuncsGabs['f_lb_kg']      = function($v) { return (string)round((float)$v * 0.453592, 2); };
$aFuncsGabs['f_cel_fahr']   = function($v) { return (string)round((float)$v * 9/5 + 32, 1); };
$aFuncsGabs['f_fahr_cel']   = function($v) { return (string)round(((float)$v - 32) * 5/9, 1); };

// Affichage conditionnel — Conditional display
$aFuncsGabs['f_zero']       = function($v) { return ((float)$v == 0) ? '—' : (string)$v; };
$aFuncsGabs['f_pos']        = function($v) { return ((float)$v > 0) ? '+'.(string)$v : (string)$v; };

/*----------------------------------------------------------------------*/
//  DATES — Dates et heures
/*----------------------------------------------------------------------*/

// Formatage depuis timestamp Unix — Formatting from Unix timestamp
$aFuncsGabs['f_date']       = function($v) { return date('d/m/Y', (int)$v); };
$aFuncsGabs['f_date_time']  = function($v) { return date('d/m/Y H:i', (int)$v); };
$aFuncsGabs['f_date_full']  = function($v) { return date('d/m/Y H:i:s', (int)$v); };
$aFuncsGabs['f_date_iso']   = function($v) { return date('Y-m-d', (int)$v); };
$aFuncsGabs['f_date_us']    = function($v) { return date('m/d/Y', (int)$v); };
$aFuncsGabs['f_time']       = function($v) { return date('H:i', (int)$v); };
$aFuncsGabs['f_time_full']  = function($v) { return date('H:i:s', (int)$v); };
$aFuncsGabs['f_year']       = function($v) { return date('Y', (int)$v); };
$aFuncsGabs['f_month']      = function($v) { return date('m', (int)$v); };
$aFuncsGabs['f_day']        = function($v) { return date('d', (int)$v); };

// Formatage depuis chaîne date — Formatting from date string (ex: "2026-02-11")
$aFuncsGabs['f_strdate']        = function($v) { return date('d/m/Y', strtotime($v)); };
$aFuncsGabs['f_strdate_time']   = function($v) { return date('d/m/Y H:i', strtotime($v)); };
$aFuncsGabs['f_strdate_full']   = function($v) { return date('d/m/Y H:i:s', strtotime($v)); };
$aFuncsGabs['f_strdate_iso']    = function($v) { return date('Y-m-d', strtotime($v)); };
$aFuncsGabs['f_strdate_us']     = function($v) { return date('m/d/Y', strtotime($v)); };

// Ancienneté — Age / elapsed time
$aFuncsGabs['f_age_fr']        = function($v) {
    $diff = (new DateTime())->diff(new DateTime(date('Y-m-d', (int)$v)));
    return $diff->y.' an'.($diff->y > 1 ? 's' : '');
};
$aFuncsGabs['f_age_en']        = function($v) {
    $diff = (new DateTime())->diff(new DateTime(date('Y-m-d', (int)$v)));
    return $diff->y.' year'.($diff->y > 1 ? 's' : '').' old';
};
$aFuncsGabs['f_elapsed_fr']    = function($v) {
    $diff = time() - (int)$v;
    if ($diff < 60)     { return 'à l\'instant'; }
    if ($diff < 3600)   { return floor($diff/60).' min'; }
    if ($diff < 86400)  { return floor($diff/3600).' h'; }
    if ($diff < 604800) { return floor($diff/86400).' j'; }
    return date('d/m/Y', (int)$v);
};
$aFuncsGabs['f_elapsed_en']    = function($v) {
    $diff = time() - (int)$v;
    if ($diff < 60)     { return 'at the moment'; }
    if ($diff < 3600)   { return floor($diff/60).' min'; }
    if ($diff < 86400)  { return floor($diff/3600).' h'; }
    if ($diff < 604800) { return floor($diff/86400).' d'; }
    return date('d/m/Y', (int)$v);
};
$aFuncsGabs['f_elapsed_us']    = function($v) {
    $diff = time() - (int)$v;
    if ($diff < 60)     { return 'at the moment'; }
    if ($diff < 3600)   { return floor($diff/60).' min'; }
    if ($diff < 86400)  { return floor($diff/3600).' h'; }
    if ($diff < 604800) { return floor($diff/86400).' d'; }
    return date('m/d/Y', (int)$v);
};

/*----------------------------------------------------------------------*/
//  ARRAYS — Tableaux (valeurs scalaires issues de tableaux)
/*----------------------------------------------------------------------*/

// Comptage — Counting (si la valeur est une chaîne sérialisée ou liste CSV)
$aFuncsGabs['f_count_csv']  = function($v) { return (string)count(explode(',', $v)); };
$aFuncsGabs['f_count_pipe'] = function($v) { return (string)count(explode('|', $v)); };

/*----------------------------------------------------------------------*/
//  MISCELLANEOUS — Divers 
/*----------------------------------------------------------------------*/

// Booléens affichés — Boolean display
$aFuncsGabs['f_bool_yn_fr']    = function($v) { return $v ? 'Oui' : 'Non'; };
$aFuncsGabs['f_bool_yn_en'] = function($v) { return $v ? 'Yes' : 'No'; };
$aFuncsGabs['f_bool_01']    = function($v) { return $v ? '1' : '0'; };
$aFuncsGabs['f_bool_ico']   = function($v) { return $v ? '✅' : '❌'; };

// Masquage — Masking (données sensibles)
$aFuncsGabs['f_mask_email'] = function($v) {
    $parts = explode('@', $v);
    if (count($parts) !== 2) { return $v; }
    return mb_substr($parts[0], 0, 2).'***@'.$parts[1];
};
$aFuncsGabs['f_mask_phone'] = function($v) {
    return preg_replace('/(\d{2})(\d+)(\d{2})/', '$1 ** ** ** $3', preg_replace('/\D/', '', $v));
};
$aFuncsGabs['f_mask_card']  = function($v) {
    $v = preg_replace('/\D/', '', $v);
    return '****  ****  ****  '.mb_substr($v, -4);
};

// Divers utiles — Useful misc
$aFuncsGabs['f_default']    = function($v) { return empty(trim($v)) ? '—' : $v; };
$aFuncsGabs['f_initials']   = function($v) {
    $words = explode(' ', trim($v));
    $init  = '';
    foreach ($words as $w) { if (!empty($w)) { $init .= strtoupper($w[0]).'.'; } }
    return $init;
};

/*----------------------------------------------------------------------*/
