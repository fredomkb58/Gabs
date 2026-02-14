<?php 
/**
 * GABS - funcs_user - Filtres utilisateur - User filters
 * @version     0120 - PHP 5.6+
 * @copyright   FredoMkb © 2026 
 * ---
 * Ajoutez ici tous vos filtres personnalisés
 * Add all your custom filters here
  */

$aFuncsUser = array();

/*----------------------------------------------------------------------*/
// 	Exemples (à personnaliser ou à supprimer) 
//	Examples (to be customized or deleted)
/*----------------------------------------------------------------------*/

// Filtre métier : nom de produit
$aFuncsUser['f_product_name'] = function($v) {
    return strtoupper(trim($v));
};

// Filtre métier : extrait d'article
$aFuncsUser['f_extract_200'] = function($v) {
    return mb_substr(strip_tags(trim($v)), 0, 200).'...';
};

// Filtre métier : slug URL
$aFuncsUser['f_slug'] = function($v) {
    $v = strtolower(trim($v));
    $v = preg_replace('/[^a-z0-9]+/', '-', $v);
    return trim($v, '-');
};

/*----------------------------------------------------------------------*/
// 	Vos filtres - Your filters  
/*----------------------------------------------------------------------*/

