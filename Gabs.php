<?php
/**
 * Gabs
 * @version		0120 - PHP 5.6+ 
 * @role		Moteur de gabarits Php-Html - Php-Html Template Engine 
 * @slogan		{ logique sans bruit && design sans echo } 
 * 				{ logic without noise && design without echo } 
 * @licence		Projet libre et open-source - Free and open-source project 
 * @copyright	FredoMkb © 2026 
 * ----------
 * [fr] Fonctionnalités :
 * 	- Moteur de gabarits HTML léger et très rapide 
 * 	- Syntaxe très simple et claire de balises dans le code HTML 
 * 	- Gabarits HTML au format texte avec des fichiers comme : '.gabs', '.html', '.txt', '.tpl', etc. 
 * 	- Données au format tableau (array) associatif (clé => valeur) 
 * 	- Valeurs supportées : 
 * 		- Textes alphanumériques (strings) : {variable} 
 * 		- Valeurs booléennes binaires, blocs conditionnels simples : {bool{ …si vrai… }bool{ …si faux… }bool}, 
 * 		- Listes et tableaux (boucles, array = loop) : {tableau{ …contenu… }tableau} 
 * 		- Inclusions statiques et dynamiques d'autres gabarits ou codes : {inclusions/gabarit.gabs} 
 * 	- Variables globales accessibles dans les boucles, deux modes (configurable) : stricte (suffixe '_g') et générale 
 * 	- Les variables correspondent aux clés (key) du tableau de données fourni 
 *  - Les variables peuvent être modifiées via un système de fonctions-filtres personnalisés : {s_var|f_filtre}  
 *  - Protection automatique des données sensibles (échappement par défaut avec 'htmlspecialchars()') 
 *  - Personnalisation de la configuration très simple et granulaire (fonctions, traitements, chemins, etc.) 
 *  - Informations pour les boucles (début, fin, nombre, total, page et indicateur de tri)  
 * 	- Système de cache basé sur chaque gabarit traité et selon les données fournies (95 % plus rapide) 
 * 	- Système de purification (suppression) automatique des caches obsolètes  
 * 	- Masquage automatique (commentaire HTML) des balises orphélines dans le gabarit  
 *  - Convention de nommage pratique recommandée pour le nommage des clés du tableau de données 
 *  - IMPORTANT : les données doivent être fournies déjà formatées (dates, nombres, URLs, chemins, conversions, encodages, etc.).
 * ----------
 * [en] Features:
 * 	- Lightweight and very fast HTML template engine 
 * 	- Very simple and clear syntax of tags in HTML code 
 * 	- HTML templates in text format with files like: '.gabs', '.html', '.txt', '.tpl', etc. 
 * 	- Data in associative array format (key => value) 
 * 	- Supported values:
 * 		- Alphanumeric text (strings) : {variable} 
 * 		- Boolean binary values, simple conditional blocks : {bool{ …if true… }bool{ …if false… }bool}, 
 * 		- Lists and arrays (loops, array = loop) : {array{ …content… }array} 
 * 		- Static and dynamic inclusions of other templates or codes: {inclusions/gabarit.gabs} 
 * 	- Global variables accessible in loops, two modes (configurable): strict (suffix '_g') and general 
 * 	- The variables correspond to the keys in the provided array data 
 * 	- The variables can be modified via a system of custom filter functions: {s_var|f_filtre}  
 *  - Automatic protection of sensitive data (default escape by'htmlspecialchars()') 
 *  - Very simple and granular configuration customization (functions, processes, paths, etc.) 
 *  - Information for loops (begin, finish, number, total, page and sort indicator) 
 * 	- Caching system based on each processed template and the provided data (95% faster) 
 * 	- Automatic system for purifying (deleting) obsolete caches  
 * 	- Automatic hiding (HTML comment) of orphaned tags in the template  
 *  - Recommended practical naming convention for naming data table keys 
 *  - IMPORTANT: Data must be provided already formatted (dates, numbers, URLs, paths, conversions, encodings, etc.). 
 * ----------
 * Crédits : Claude-AI, Gemini-AI, Mistral-AI, ChatGPT-AI, Perplexity-AI, StackOverflow, php.net, w3schools, Mdn_, CodePen, GitHub, GitLab, developpez.com, regex101.com, onlinephp.io, Wikipedia, etc…
 * ----------
**/

class Gabs
{
	/**
	 * @aTools	array		Balises de délimitation et masque de recherche - Delimiter tags and search mask
	 **/
	private $aTools = array();

	/**
	 * @aConfs	array		Paramètres pour le choix des traitements - Parameters for choice of treatments
	 **/
	private $aConfs = array(
        'cach' => true, 					// cache activé - is cache active 
        'dbug' => true, 					// dump debug des données - is debug mode 
        'escp' => true, 					// échappement auto des données - is auto-escape datas 
        'pure' => true, 					// purifier (supprimer) les caches - purify (delete) caches
        'hide' => true,						// masquer les balises orphélines - hide the orphan tags  
        'incs' => true, 					// inclusions activées - is inclusions 
        'bool' => true, 					// conditions booléennes - is booleans 
        'arrs' => true, 					// boucles tableaux - is arrays 
        'info' => true,						// infos des boucles - loop information 
        'glob' => true,						// globales des boucles, suffixe '_g' - globals in loops, suffix '_g'  
        'sort' => '&#47;&#92;|&#92;&#47;',	// indicateurs de tri '/\|\/' (asc|desc) - sort indicators '/\|\/' (asc|desc)   
        'fold' => 'cache', 					// dossier cache - cache folder 
        'tpls' => '', 						// dossier des gabarits - templates folder 
    ); 	

	/**
	 * @aTemps	array		Données globales temporaires de travail - Temporary global labor data 
	 **/
	private $aTemps = array();

	/**
	 * @aFuncs	array		Les fonctions personnalisées - Custom functions  
	 **/
	private $aFuncs = array();

	/*----------------------------------------------------------------------*/

	/**
	 * Constructeur
	 *
	 * @version		0120
	 * @param		sL		string		Délimiteur 'gauche' (left) des balises des variables - Delimiter 'left' vars tags
	 * @param		sR		string		Délimiteur 'droite' (right) des balises des variables - Delimiter 'right' vars tags
	 * @param		sG		string		Délimiteur 'gauche' des balises des items - Delimiter 'left' of items tags
	 * @param		sD		string		Délimiteur 'droite' des balises des items - Delimiter 'right' of items tags
	 * @return				void
	 * 
	 * @description		Les délimiteurs - The boundary markers :
	 * 						- Les délimiteurs par accolades "{" et "}" (brce) sont utilisés pour les variables, 
	 * 							les condition booléennes binaires et les tableaux de données.
	 * 						- Les délimiteurs par crochets "[" et "]" (brck) sont utilisés pour définir les éléments 
	 * 							des blocs booléens courts et la sélection d'items des tableaux de données à afficher 
	 * 							(voir les descriptions de la fonction "_getBool_s" et de la fonction "_getArrs_s").
	 * 
	 * 					Conventions de nommage des clés du tableau général des données à fournir :
	 * 						Les clés des données transmises à GABS, peuvent être nommées avec un préfixe 
	 * 						indiquant le type de donnée qu'elles contiennent, en deux groupes :
	 * 							- Les types de données à protéger (échapper) : 's_' = string ; 'c_' = code ; 'n_' = number
	 * 							- Les types de données brutes à ne pas protéger : 'h_' = html ; 'b_' = boolean ; 'a_' = array
	 * 						IMPORTANT : le type préfixe 'c_' = code, n'ajoute pas de balise HTML "<pre>" ou "<code>"
	 * 
	 * 					Résumé des délimiteurs - Summary of delimiters : 
	 * 						- $sL = left vars tag = '{' ; $sR = right vars tag = '}' 
	 * 							= variables, inclusions, conditions, boucles, débogae
	 * 						- $sG = left selection tag = '[' (G = gauche) ; $sD = right selection tag = ']' (D = droite) 
	 * 							= sélection des blocs conditionnels courts, sélection des items dans les boucles 
	 * 						- $sM = chr(96) = '`' = délimiteur des masques RegEx - RegEx Mask delimiters
	 * 
	 * 					Résumé des changeurs - Summary of changers :
	 * 						- $sE = chr(124) = '|' = {vars|} = pour utiliser une valeur brute - to use a raw value
	 * 						- $sS = chr(33) = '!' = {array{! content }array} ; {array{[0[! content ]5]}array}
	 * 							= pour faire un tri inverse d'un tableau ou de la séléction d'items 
	 * 							= to perform a reverse sort of an array or a selection of items 
	 * 
	 * 					Balises spéciales - Special tags :
	 * 						- $sK = 'k' = {k} = la clé d'un item de tableau - the key to an array item
	 * 						- $sV = 'v' = {v} = la valeur d'un item de tableau - the value of an array item
	 * 						- $sC = 'c' = {c} = le compteur d'un item de tableau - the counter of an array item
	 * 						- $sB = chr(95) = '_' = {_} = contenus de débogage - debug contents 
	 * 
	 * 					Balises d'infos pour les boucles - Information tags for loops :
	 * 						- 'b' = begin (début) 
	 * 						- 'f' = finish (fin) 
	 * 						- 'n' = number (nombre) 
	 * 						- 't' = total (total) 
	 * 						- 'p' = page (page) 
	 * 						- 's' = sort (tri) 
	 **/
	public function __construct($sL = '{', $sR = '}', $sG = '[', $sD = ']')
	{
        list($sM, $sE, $sS) = array('`', '|', '!');
		list($sK, $sV, $sC, $sB) = array('k','v','c','_');
		list($pL, $pR, $pG, $pD, $pE) = array(preg_quote($sL,$sM), preg_quote($sR,$sM), preg_quote($sG,$sM), preg_quote($sD,$sM), preg_quote($sE,$sM));
		$aTypes = array('s_','c_','n_','h_','b_','a_');
        $this->aTools = array(
            'tags' => array('brce'=>array($sL, $sR), 'brck'=>array($sG, $sD)),
            'html' => array('tags'=>array($sG, $sD, $sL, $sR), 'html'=>array('&#91;', '&#93;', '&#123;', '&#125;')),
            'incs' => $sM.$pL.'([a-zA-Z0-9_\-\/]+?\.[\w]{2,6})'.$pR.$sM.'sS',
            'bool' => array($sM.$pL, $pL.'(.+?)?'.$pR, $pL.'(.+?)?'.$pR, $pR.$sM.'sS'),
            'vrai' => array($sM.$pL, $pL.$pG.'(.+?)?'.$pR, $pR.$sM.'S'),
            'faux' => array($sM.$pL, $pL.'(.+?)?'.$pD.$pR, $pR.$sM.'S'),
            'arrs' => array($sM.$pL, $pL.'(.+?)'.$pR, $pR.$sM.'sS'),
            'itms' => $sM.$pG.'(-?\d+?)'.$pG.'(.+?)'.$pD.'(-?\d+?)'.$pD.$sM.'sS',
            'list' => array(array($sL.$sK.$sR, $sL.$sV.$sR, $sL.$sC.$sR), array($sL.$sK.$sR, $sL.$sC.$sR)),
            'loop' => array($sK, $sV, $sC),
            'info' => array('b','f','n','t','p','s'),
            'vars' => array($sM.$pL, $pE.'?([\w\-'.$pE.']+)?'.$pE.'?'.$pR.$sM.'S'),
            'chng' => array('raws'=>$sE, 'sort'=>$sS, 'loop'=>$sL.$sV.$sE.$sR, 'vars'=>$sE.$sR,),
            'type' => array('escp'=>array_slice($aTypes, 0, 3), 'raws'=>array_slice($aTypes, 3)),
            'glob' => '_g',
            'hide' => array(
            	$sM.$pL.'[\w\-\/\.]+?'.$pE.'?([\w\-'.$pE.']+)?'.$pE.'?'.$pR.$sM.'S', 
            	$sM.$pL.'[\w\-]+?'.$pL.'!?('.$pG.')?((-?\d+?)'.$pG.')?'.$sM.'S', 
            	$sM.'('.$pD.'(-?\d+?))?('.$pD.')?'.$pR.'[\w\-]+?'.$pR.$sM.'S', 
            	$sM.$pR.'[\w\-]+?'.$pL.$sM.'S', 
            ),
            'dbug' => array('escp'=>$sB, 'raws'=>$sB.$sE, 'test'=>array($sL.$sB.$sR, $sL.$sB.$sE.$sR)),
        );
        $this->aTemps = array();
        $this->aFuncs = array();
	} 

	/*----------------------------------------------------------------------*/

	/**
	 * get - fonction publique principale
	 *
	 * @version		0120
	 * @param		sTemp		string		Chemin ou contenu du template - File path or contents code of template
	 * @param		aData		array		Tableau de données à insérer dans le template - Array datas to insert into the template
	 * @return		sTemp		string		Gabarit final à retourner après traitement - The result template to return after parsing
	 * 
	 * @descriptions	C'est la fonction publique principale qui se charge de construire le gabarit, 
	 * 					en y insérant les données fournies pour retourner le résultat final 
	 * 					au script appelant qui se chargera de l'afficher. 
	 * 
	 * 					Structure sur 5 étapes principales : 
	 * 						1. Construction du gabarit brut de base, avec les inclusions statiques si actif 
	 * 						2. Consultation de l'existance du cache : si oui = on retourne le cache et on arrête tout 
	 * 						3. S'il n'y pas de cache, on lance les remplacements des balises par les données transmises 
	 * 						4. On lance les inclusions dynamiques, puis on mémorise le résultat final dans un nouveau cache
	 * 						5. Enfin, on génère les données de débogage et on returne le résultat final pour affichage 
	 **/
	public function get($sTemp, $aData, $aFuncs = array())
	{
		// Si quelque chose manque, on arrête tout 
		if ( empty($sTemp) ) { return ''; }
		if ( empty($aData) ) { return $sTemp; }

		// Les variables (bool)config de travail 
		$bIncs = $this->aConfs['incs'];
		$bCach = $this->aConfs['cach'];
		$bDbug = $this->aConfs['dbug'];
		$bHide = $this->aConfs['hide'];

		// On récupère le template 
		$sTemp = $this->_getTemp_s($sTemp); 

		// On lance les inclusions statiques si actif 
		if ( $bIncs ) { 
			$sTemp = $this->_getIncs_s($sTemp); 
		}

		// On récupère le cache si actif et s'il existe, si oui :  
		// on retourne le contenu du cache et on arrête tout  
		if ( $bCach ) {
			$sFold = $this->aConfs['fold'].'/'.md5($sTemp);
			$sFile = md5(serialize($aData)).'.cache';
			$sPath = $sFold.'/'.$sFile;
			if ( is_file($sPath) ) {
				return file_get_contents($sPath);
			}
		} 

		// On charge les fonctions-filtres personnalisés 
		if (!empty($aFuncs)) {
			$this->aFuncs = $aFuncs;
		}

		// On mémorise le gabarit original complet (pour débogage)
		$sOrig = ($bDbug) ? $sTemp : '';

		// Initialisation des données temporaires de travail 
		$this->aTemps = array('glob'=>array());
		// $this->aTemps = array('glob'=>array(), 'keys'=>array(), 'bool'=>array());

		// On prépare les données pour les traitements suivants 
		$aData = $this->_getPrep_a($aData);

		// On lance les traitements et les remplacements (parsing)
		$sTemp = $this->_getParse_s($sTemp, $aData);

		// On lance les inclusions dynamiques si actif 
		// et on relance les traitements et les remplacements (parsing) 
		// uniquement si les inclusions ont modifié le contenu du template 
		if ( $bIncs ) { 
			$sIncs = $this->_getIncs_s($sTemp); 
			if ( $sTemp !== $sIncs ) {
				$sTemp = $this->_getParse_s($sIncs, $aData);
			}
		}

		// On masque (on commente) toutes les balises orphélines (sauf celles de débug) 
		if ( $bHide ) {
			$sTemp = $this->_setHide_a($sTemp);
		}
		
		// On enregistre le nouveau cache si actif
		// et on fait un peu de purification au besoin  
		if ( $bCach ) {
			if ( is_dir($sFold) || mkdir($sFold, 0755, true) ) {
				if ( $this->aConfs['pure'] ) { $this->_setPureCaches_n($sFold); }
				file_put_contents($sPath, $sTemp); 
			}
		}

		// On récupère et on affiche les données de débogage si actif
		if ( $bDbug ) { 
			$sTemp = $this->_getDbug_s($sOrig, $aData, $sTemp);
		}

		// Retour du contenu final 
		return $sTemp;
	}

	/*----------------------------------------------------------------------*/

	/**
	 * conf - fonction publique pour régler les paramètres des traitements
	 *  
	 * @version		0120
	 * @param		aConf		array		Configuration par clé => valeur - Configuration by key => value 
	 * @param		bCach		boolean		Faut-il gérer les fichiers cache ? - Should we manage cache files?
	 * @param		bDbug		boolean		Faut-il retourner la liste des données pour débogage ? - Should the data list be returned for debugging?
	 * @param		bEscp		boolean		Faut-il appliquer l'échappement automatique des données ? - Should automatic data escaping be applied?
	 * @param		bPure		boolean		Faut-il purifier (supprimer) les caches obsolètes ? - Should we clear (delete) outdated caches?
	 * @param		bHide		boolean		Faut-il masquer toutes les balises orphélines ? - Should all orphaned tags be hidden?
	 * @param		bIncs		boolean		Faut-il traiter les balises d'inclusion ? - Should we treat the inclusion tags?
	 * @param		bBool		boolean		Faut-il traiter les balises des conditions booléennes ? - Should we treat Boolean condition tags?
	 * @param		bArrs		boolean		Faut-il traiter les balises des boucles sur les tableaux ? - Should loop tags be processed on arrays?
	 * @param		bInfo		boolean		Faut-il générer et insérer les infos des boucles ? - Do we need to generate and insert the loop information?
	 * @param		bGlob		boolean		Faut-il forcer le suffixe '_g' pour les globales dans les boucles ? - Should the '_g' suffix be forced for global variables in loops?
	 * @param		sSort		string		Les indicateurs de tri : ascendant et descendant - Sorting indicators: ascending and descending 
	 * @param		sFold		string		Chemin relatif vers le dossier de cache depuis la racine du site - Relative path to the cache folder from the site root 
	 * @param		sTpls		string		Chemin relatif vers le dossier de gabarits depuis la racine du site - Relative path to the templates folder from the site root 
	 * @return		void				Aucun retour - No return
	 * 
	 * @descriptions	Deux manières de personnaliser la configuration de GABS :
	 * 						1. en fournissant un tableau avec les clés et les valeurs à modifier, sur le 1er argument :
	 * 							$gabs->conf(array('cach'=>true, 'dbug'=>false, 'fold'=>'my_cache', 'pure'=>0, 'incs'=>1)); 
	 * 
	 * 						2. en indiquant la valeur de chaque élément dans le bon ordre, à partir du deuxième argument :
	 * 							$gabs->conf('',true,false,true,1,0,0,true,0,'my_cache','gabs'); 
	 * 
	 * 						L'ordre, ainsi que les clés et leur type, des arguments à utiliser est le suivant :
	 * 							// configuration précise par clé => valeur (laisser vide '' si 2e méthode utilisée)
	 * 							(array)array(), 
	 * 							// configuration des fonctions spéciales 
	 * 							(int)(bool)'cach', (int)(bool)'dbug', (int)(bool)'escp', (int)(bool)'pure', (int)(bool)'hide', 
	 * 							// configuration des traitemens sur les données 
	 * 							(int)(bool)'incs', (int)(bool)'bool', (int)(bool)'arrs', (int)(bool)'info',  (int)(bool)'glob', 
	 * 							// configuration des indicateurs de tri et des chemins 
	 * 							(string)'sort', (string)'fold', (string)'tpls'
	 * 
	 * 						Voici la correspondance entre chaque clé et son rôle : 
	 * 							Clé			Type				Rôle
	 * 							'cach'		(int)(bool) 		activation du cache 
	 * 							'dbug'		(int)(bool) 		activation du mode de débogage 
	 * 							'escp'		(int)(bool) 		activation de la protection auto des données 
	 * 							'pure'		(int)(bool) 		activation de la purification auto des caches 
	 * 							'hide'		(int)(bool) 		activation du masquage des balises orphélines  
	 * 							'incs'		(int)(bool) 		activation des inclusions statiques et dynamiques 
	 * 							'bool'		(int)(bool) 		activation des blocs conditionnels binaires 
	 * 							'arrs'		(int)(bool) 		activation des boucles sur les tableaux 
	 * 							'info'		(int)(bool)			activation des infos des boucles 
	 * 							'glob'		(int)(bool)			activation des globales des boucles avec suffixe '_g' 
	 * 							'sort'		(string)			définition des indicateurs de tri (asc|desc) 
	 * 							'fold'		(string)			définition du chemin du dossier cache 
	 * 							'tpls'		(string) 			définition du chemin du dossier des gabarits 
	 * 
	 * 						Here is the correspondence between each key and its role: 
	 * 							Key 		Type 				Role 
	 * 							'cach' 		(int)(bool) 		Enables cache 
	 * 							'dbug' 		(int)(bool) 		Enables debug mode 
	 * 							'escp' 		(int)(bool) 		Enables automatic data protection 
	 * 							'pure' 		(int)(bool) 		Enables automatic cache purging 
	 * 							'hide' 		(int)(bool) 		Enables of orphan tag masking 
	 * 							'incs' 		(int)(bool) 		Enables static and dynamic includes 
	 * 							'bool' 		(int)(bool) 		Enables binary conditional blocks 
	 * 							'arrs' 		(int)(bool) 		Enables array loops 
	 * 							'info' 		(int)(bool) 		Enables loop information 
	 * 							'glob' 		(int)(bool) 		Enables globals in loops with suffix '_g' 
	 * 							'sort' 		(string) 			Defines the sorting indicators (asc|desc) 
	 * 							'fold' 		(string) 			Defines the cache folder path 
	 * 							'tpls' 		(string) 			Defines the template folder path 
	 * 
	 * 						IMPORTANT : si l'option 'glob' est vraie (true), cela impose une syntaxe stricte pour  
	 * 							que les variables globales puissent être utilisées à l'intérieur des boucles, 
	 * 							en ajoutant le suffixe '_g' à celles qui doivent être disponibles dans les boucles :
	 * 								{s_texte_g} ; {n_nombre_g} ; {b_bool_g} ; {c_code_g} ; {h_html_g} 
	 * 
	 * 							Toutes les valeurs scalaires peuvent être utilisées comme données globales
	 * 							dans les boucles, seuls les tableaux sont exclus comme données globales.
	 * 
	 * 							Si cette option 'glob' est fausse (false), alors toutes les données scalaires seront 
	 * 							disponibles par défaut comme données globales à l'intérieur des boucles. 
	 * 								ATTENTION : ce choix de l'option 'glob' = faux (false) peut produit deux effets :
	 * 									1. une collision potentielle avec les données du tableau de la boucle 
	 * 									2. un ralentissement probable du traitement de chaque boucle 
	 * 						
	 * 						CONSEIL : si vous souhaitez utiliser des données globales dans le boucles, 
	 * 							tout en optimisant au mieux tout ces traitemens (plus rapide et moins de risques), 
	 * 							veuillez opter par l'option 'glob' à vrai (true), en prennant soin de bien suffixer 
	 * 							avec '_g' toutes les valeurs globales nécessaires dans les boucles.  
	 * 						
	 **/
	public function conf(
		$aConf=array(), 
		$bCach=true, $bDbug=true, $bEscp=true, $bPure=true, $bHide=true, 
		$bIncs=true, $bBool=true, $bArrs=true, $bInfo=true, $bGlob=true, 
		$sSort='&#47;&#92;|&#92;&#47;', $sFold='cache', $sTpls='')
	{
		if ( empty($aConf) ) {			
	        $this->aConfs = array(
	            'cach' => (bool)$bCach,					// cache activé - is cache active 
	            'dbug' => (bool)$bDbug,					// dump debug des données - is debug mode 
	            'escp' => (bool)$bEscp,					// échappement auto des données - is auto-escape datas 
		        'pure' => (bool)$bPure, 				// purifier (supprimer) les caches - purify (delete) caches  
		        'hide' => (bool)$bHide, 				// masquer les balises orphélines - hide the orphan tags  
	            'incs' => (bool)$bIncs,					// inclusions activées - is inclusions 
	            'bool' => (bool)$bBool,					// conditions booléennes - is booleans 
	            'arrs' => (bool)$bArrs,					// boucles tableaux - is arrays 
	            'info' => (bool)$bInfo,					// infos des boucles - loop information 
	            'glob' => (bool)$bGlob,					// globales des boucles, suffixe '_g' - globals in loops, suffix '_g' 
	            'sort' => (string)$sSort,				// indicateurs de tri (asc|desc) - sort indicators (asc|desc) 
	            'fold' => rtrim((string)$sFold, '/'),	// dossier cache - cache folder 
	            'tpls' => rtrim((string)$sTpls, '/'),	// dossier des gabarits - templates folder 
	        );
		} else {
			$aKeys = array_keys($this->aConfs);
			$aText = array('sort', 'fold', 'tpls');
			$sText = '_'.implode('_', $aText).'_';
			foreach ( $aConf as $sKey => $uVal ) { 
				if ( in_array($sKey, $aKeys) ) {
					$this->aConfs[$sKey] 
						= ($this->_getIsFind_b($sText, $sKey)) 
						? rtrim((string)$uVal, '/')
						: (bool)$uVal;
				}
			}
		}
	} 

	/*----------------------------------------------------------------------*/

	/**
	 * getTemp - lecture et/ou retour du contenu du gabarit
	 *
	 * @version		0120
	 * @param		sTemp		string		Chemin ou contenu du template - File path or contents code of template
	 * @return		data		string		Contenu du template original - Original template contents
	 **/
	private function _getTemp_s($sTemp)
	{
		return is_file($sTemp) ? file_get_contents($sTemp) : $sTemp;
	}

	/*----------------------------------------------------------------------*/

	/**
	 * getIncs - traite les inclusions et retourne le gabarit brut modifié
	 *
	 * @version		0120
	 * @param		sTemp		string		Contenu du template - Contents code of template
	 * @return		sTemp		string		Le nouveau gabarit avec les inculions - The new template with the incisions 
	 * 
	 * @descriptions	Cette fonction tente de récupérer le contenu des gabarits en inclusion 
	 * 					(selon les balises d'inclusion présentes dans le gabarit principal)
	 * 					pour les insérer dans le gabarit principal à la place des balises.
	 * 
	 * 					La syntaxet des balises à utilser pour effectuer des inclusions est la suivante :
	 * 						{chmein/du/gabarit.gabs}
	 * 					Cette syntaxe est recommandée pour les inclusions statiques.
	 * 					Pour les inclusions dynamiques, c'est-à-dire, comportant un chemin vers le gabarit 
	 * 					construit avec des valeurs issues du tableau de données transmit au gabarit 
	 * 					(pour gérer par exemple des contenus multilingues ou des thèmes graphiques) :
	 * 						{chmein/du/{s_lang}.gabs} ; {chmein/du/{s_theme}.gabs} 
	 * 
	 * 					Dans tous les cas, aussi bien pour les inclusions statiques que dynamiques, 
	 * 					si le gabarit inclu contient des balises GABS, elles seront traités.
	 * 
	 * 					IMPORTANT : les chemins d'accès DOIVENT toucher les délimiteurs (accolades par défaut)
	 * 						toute autre syntaxe fera échouer l'inclusion du gabarit correspondant',
	 * 						Par exemple, les syntaxes suivantes seront ignorées ou produiront un affichage erroné :
	 * 							{ chmein/du/gabarit.gabs} ; {chmein/du/gabarit.gabs } ; { chmein/du/gabarit.gabs }
	 * 
	 * 					La détection des balises d'inclusion et des chemins qu'elles contiennent
	 * 					se fait via un masque RegEx qui a été construit de manière à éviter (limiter) 
	 * 					les éventuelles tentatives de chemins remontant l'arborescence (path-traversal) 
	 * 
	 * 					La fonction effectue une vérification supplémentaire pour s'assurer que 
	 * 					les chemins vers les gabartis à inclure sont vraiment sûrs.
	 **/
	private function _getIncs_s($sTemp)
	{
		if ( !preg_match_all( $this->aTools['incs'], $sTemp, $aRes, PREG_SET_ORDER ))  {
			return $sTemp;
		}
	    $sBase = (empty($this->aConfs['tpls'])) ? realpath('./') : realpath('./'.$this->aConfs['tpls']);
	    if ($sBase === false) { return $sTemp; }
	    foreach ( $aRes as $aItm ) {
	        $sReal = realpath($sBase.'/'.$aItm[1]);
	        if ( ($sReal === false) || (strpos($sReal, $sBase) !== 0) ) { 
	        	continue; 
	        }
			$sTemp = str_replace($aItm[0], $this->_getTemp_s($sReal), $sTemp);
	    }
		return $sTemp;
	}

	/*----------------------------------------------------------------------*/

    /**
     * getPrep - Préparation des données pour les remplacements (parsing) 
     * 
	 * @version		0120
     * @param 	aData		array 		$aData Tableau de données
     * @return 	data 		array 		Les données de $aData correctements triées 
	 * @descriptions	La fonction effectue un tri sur les données pour les préparer dans le bon ordre :
	 * 						1. Tableaux de données 
	 * 						2. Valeurs booléennes 
	 * 						3. Valeurs scalaires générales 
     */
    private function _getPrep_a($aData)
    {
        // Trier par type : arrays → bools → vars
        // Uniquement si les traitements des booléens ET/OU des tableaux sont actifs
        if ( $this->aConfs['bool'] || $this->aConfs['arrs'] ) {
	        uksort($aData, function($sKey1, $sKey2) use ($aData) {
	            $uVal1 = $aData[$sKey1];
	            $uVal2 = $aData[$sKey2];
				$nTyp1 = is_array($uVal1) ? 1 : (is_bool($uVal1) ? 2 : 3);
				$nTyp2 = is_array($uVal2) ? 1 : (is_bool($uVal2) ? 2 : 3);
	            return $nTyp1 - $nTyp2;
	        });
	    }

	    // On retourne les données correctement triées 
	    return $aData;
    }


	/*----------------------------------------------------------------------*/

	/**
	 * getParse - parsage et construction du gabarit final
	 *
	 * @version		0120
	 * @param		sTemp		string		Contenu du template - Contents code of template
	 * @param		aData		array		Tableau de données à insérer dans le template - Array datas to insert into the template
	 * @return		sTemp		string		Nouveau contenu du template parsé - New parsed template contents
	 * @descriptions	On lance une boucle sur chaque donnée fournie, puis on récupère la clé et on vérifie 
	 * 					qu'une balise du même nom existe bien dans le gabarit, si c'est le cas on lance 
	 * 					les différents traitements, dans l'ordre suivant :
	 * 						1. Si la valeur est de type tableau de données (array) : boucles => "_getArrs_s"
	 * 						2. Si la valeur est de type booléen : blocs binaires => "_getBool_s" 
	 * 						3. Tous les autres types de valeurs (alphanumériques) : variables => "_getVars_s"
	 **/
    private function _getParse_s($sTemp, $aData)
    {
        // Traiter dans l'ordre trié : array -> bool -> vars 
        foreach ( $aData as $sTag => $uVal ) {
            if ( !$this->_getIsFind_b($sTemp, $this->aTools['tags']['brce'][0].$sTag) ) { 
                continue; 
            }
            if ( is_array($uVal) && $this->aConfs['arrs'] ) {
                $sTemp = $this->_getArrs_s($sTag, $uVal, $sTemp);
            }
            elseif ( is_bool($uVal) && $this->aConfs['bool'] ) {
                $sTemp = $this->_getBool_s($sTag, $uVal, $sTemp);
            } 
            else {
                $sTemp = $this->_getVars_s($sTag, $uVal, $sTemp);
            }
        }
        
        // Retour du gabarit enrichi 
        return $sTemp;
    }

	/*----------------------------------------------------------------------*/

	/**
	 * getBool - traite les conditions booléenes et retourne le gabarit modifié
	 *
	 * @version		0120
	 * @param		sTag		string		Balise à remplacer - Tag to replace
	 * @param		bVal		bool		Valeur pour choisir le contenu à utiliser - Value to choose content
	 * @param		sTemp		string		Contenu du template - Contents code of template
	 * @return		sTemp		strings		Le gabarit modifié - The modified template
	 * @descriptions	Cette fonction traite tous les blocs booléens binaires pour faire les remplacements 
	 * 					correspondants à la valeur booléenne fournie : si elle est "vrai" (true) on conserve 
	 * 					la première partie du bloc, sinon on conserve la seconde partie du bloc.
	 * 
	 * 					Seules les logiques binaires sont traitées (vrai ou faux), il n'y a pas de gestion 
	 * 					des conditions enchaînées "sinon si" (else if), on peut toutefois imbriquer 
	 * 					plusieurs blocs booléens binaires pour gérer des choix multiples.
	 * 
	 * 					Voici un exemple de syntaxe pour un bloc booléen binaire dans le gabarit :
	 * 						{bool{ … contenu si vrai … }bool{ … contenu si faux … }bool}
	 * 					La balise "bool" correspond à la clé de la valeur booléenne fournie
	 * 
	 * 					Deux syntaxes courtes sont disponibles si on désire n'afficher qu'une valeur vraie (true) 
	 * 					ou une valeur fausse (false) lorsqu'il n'y a rien à afficher dans l'autre partie,
	 * 					en utilisant les crochets '[' et ']' pour définir la partie qu'on souhaite afficher :
	 * 						{bool{[ … contenu si vrai … }bool}
	 * 						{bool{ … contenu si faux … ]}bool}
	 * 					IMPORTANT : ces syntaxes courtes sont à utiliser exclusivement sur une seule ligne 
	 * 								idéales pour les attributs (class, title, alt, data-*, etc.) 
	 * 								et les petites insertions conditionnelles (vrai ou faux) locales 
	 * 
	 * 					IMPORTANT : le nom de la variable booléenne DOIT toucher les délimiteurs (accolades par défaut)
	 * 						toute autre syntaxe fera échouer le remplacement par les données correspondantes,
	 * 						Par exemple, les syntaxes suivantes seront ignorées ou produiront un affichage erroné :
	 * 							{ bool{ … vrai … }bool{ … faux … }bool} ; {bool { … vrai … }bool{ … faux … }bool}
	 * 							{bool{ … vrai … } bool{ … faux … }bool} ; {bool{ … vrai … }bool { … faux … }bool}
	 * 							{bool{ … vrai … }bool{ … faux … } bool} ; {bool{ … vrai … }bool{ … faux … }bool }
	 * 							{ bool { … vrai … }bool{ … faux … }bool} ; etc…
	 * 
	 * 					NOTE : Pour assurer un traitement efficace, séparer les balises de leur contenus par une espace 
	 * 					Par exemple, éviter ceci :
	 * 						{bool{<p>Contenu Si Vrai</p>}bool{<p>Contenu Si Faux</p>}bool}
	 * 					Privilégier plutôt cette écriture :
	 * 						{bool{ <p>Contenu Si Vrai</p> }bool{ <p>Contenu Si Faux</p> }bool}
	 * 						{bool{[ … Contenu Si Vrai … }bool}
	 * 						{bool{ … Contenu Si Faux … ]}bool}
	 * 					Il est aussi recommandé d'écrire les blocs binaires sur plusieurs lignes :
	 * 						{bool{
 	 * 							<p>Contenu Si Vrai</p>
 	 * 						}bool{
	 * 							<p>Contenu Si Faux</p>
	 * 						}bool}
	 * 						
	 **/
    private function _getBool_s($sTag, $bVal, $sTemp)
    {
        if (preg_match_all( implode($sTag, $this->aTools['vrai']), $sTemp, $aRes, PREG_SET_ORDER )) {
            $nNro = ($bVal) ? 1 : 2;
            $sTemp = $this->_getBoolPart_s($aRes, $nNro, $sTemp);
        }
        if (preg_match_all( implode($sTag, $this->aTools['faux']), $sTemp, $aRes, PREG_SET_ORDER )) {
            $nNro = ($bVal) ? 2 : 1;
            $sTemp = $this->_getBoolPart_s($aRes, $nNro, $sTemp);
        }
        if (preg_match_all( implode($sTag, $this->aTools['bool']), $sTemp, $aRes, PREG_SET_ORDER )) {
            $nNro = ($bVal) ? 1 : 2;
            $sTemp = $this->_getBoolPart_s($aRes, $nNro, $sTemp);
        }
        return $sTemp;
    }

	/*----------------------------------------------------------------------*/

	/**
	 * getBoolPart - traitement des parties d'un bloc conditionnel
	 *
	 * @version		0120
	 * @param		aRes		array		Résultat de recherche "getBool" - Result of "getBool" search
	 * @param		nNro		number		Le numéro de la partie à retourner - The part number to return
	 * @param		sTemp		string		Contenu du template - Template contents
	 * @return		data		string		Nouveau contenu du template - New template contents
	 **/
    private function _getBoolPart_s($aRes, $nNro, $sTemp)
    {
        foreach ( $aRes as $aItm ) {
        	$sRemp = (isset($aItm[$nNro])) ? $aItm[$nNro] : '';
            $sTemp = str_replace($aItm[0], $sRemp, $sTemp);
        }
        return $sTemp;
    }

	/*----------------------------------------------------------------------*/

	/**
	 * getArrs - traitement des tableaux (boucles)
	 *
	 * @version		0120
	 * @param		sTag		string		Balise à remplacer - Tag to replace
	 * @param		aVal		array		Variable de remplacement de type array - Replacing array values
	 * @param		sTemp		string		Contenu du template - Template contents
	 * @param		sRemp		string		Les valeurs de remplacement - Sight replacement values
	 * @param		nCnt		integer		Compteur d'itérations - Iteration counter
	 * @return		data		string		Nouveau contenu du template - New template contents
	 *
	 * @description		Cette fonction traite les listes ou tableaux de données (les boucles).
	 *
	 * 					Voici un exemple de syntaxe pour gérer les boucles dans le gabarit :
	 * 						{a_tableau{ … le contenu répétitif à afficher … }a_tableau}
	 * 					Ici la clé du tableau de données se nomme "tableau"
	 * 
	 * 					Si on a un tableau de données des personnes, comme suit :
	 * 						"a_people" => array(
	 * 							("name"=>"John Doe", "city"=>"Londres", "age"=>28),
	 * 							("name"=>"Marie Curie", "city"=>"Paris", "age"=>36),
	 * 							("name"=>"Isaac Newton", "city"=>"Cambridge", "age"=>24)
	 * 						)
	 * 					On construit la balise dans le gabarit comme suit :
	 * 						{a_people{ … le contenu répétitif à afficher … }a_people}
	 * 
	 *					Pour afficher une donnée de chaque item, il suffit de faire une balise avec sa clé :
	 * 						{a_people{ <p>{name} a vécu à {city} à l'âge de {age} ans.</p> }a_people}
	 * 
	 * 					Lors du traitement de ce tableau, les données se récupèrent ainsi :
	 *							{name} = "John Doe" - {city} = "Londres" - {age} = 28
	 *							{name} = "Marie Curie" - {city} = "Paris" - {age} = 36
	 *							{name} = "Isaac Newton" - {city} = "Cambridge" - {age} = 24
	 * 
	 * 					Ce qui va produire le résultat suivant après traitement de la boucle :
	 * 							<p>John Doe a vécu à Londres à l'âge de 28 ans.</p>
	 * 							<p>Marie Curie a vécu à Paris à l'âge de 36 ans.</p>
	 * 							<p>Isaac Newton a vécu à Cambridge à l'âge de 24 ans.</p>
	 * 
	 * 					NOTE : Pour assurer un traitement efficace, séparer les balises de leur contenus par une espace
	 * 					Par exemple, éviter ceci :
	 * 						{a_tableau{<p>le contenu répétitif à afficher</p>}a_tableau}
	 * 					Privilégier plutôt cette écriture :
	 * 						{a_tableau{ <p>le contenu répétitif à afficher</p> }a_tableau}
	 * 					Il est aussi recommandé d'écrire les boucles sur plusieurs lignes :
	 * 						{a_tableau{
 	 * 							<p>le contenu répétitif à afficher</p>
 	 * 						}a_tableau}
	 * 
	 * 					IMPORTANT : le nom du tableau de données DOIT toucher les délimiteurs (accolades par défaut)
	 * 						toute autre syntaxe fera échouer le remplacement par les données correspondantes,
	 * 						Par exemple, les syntaxes suivantes seront ignorées ou produiront un affichage erroné :
	 * 							{ a_tableau{ … contenu … }a_tableau} ; {a_tableau { … contenu … }a_tableau}
	 * 							{a_tableau{ … contenu … } a_tableau} ; {a_tableau{ … contenu … }a_tableau }
	 * 							{ a_tableau { … contenu … }a_tableau} ; {a_tableau{ … contenu … } a_tableau }
	 * 
	 * 					Aussi, il est possible d'afficher une portion du tableau, en indiquant l'index 
	 * 					de départ et le nombre d'items à afficher, avec une syntaxe accolée à celle du tableau,
	 * 					en utilisant la fonction native "array_slice(array,offset,length)".
	 * 
	 * 					Exemple de tableau de données :
	 * 						'nros' => array('un','deux','trois','quatre','cinq','six','sept','huit','neuf','dix');
	 * 
	 * 					La syntaxe à utiliser se compose de deux crochets ouvrants "[[" à l'appel de la boucle, 
	 * 					et de deux crochets fermants "]]" à la fin de la boucle, comme suit :
	 * 						{nros{[numéro d'index de départ[ 
	 * 							… le contenu répétitif à afficher …
	 * 						]le nombre d'items à afficher]}nros}
	 * 
	 * 					Par exemple, pour afficher uniquement le premier item du tableau de données :
	 * 						{nros{[0[ 
 	 * 							… le contenu répétitif à afficher …
	 * 						]1]}nros}
	 * 					"[0[" étant l'index du tout premier élément du tableau (0 = le premier item)
	 * 					"]1]" étant le nombre d'items à afficher (1 = un seul item)
	 * 
	 * 					On peut également utiliser des nombres négatifs pour compter à partir de la fin du tableau.
	 * 					Par exemple, pour afficher uniquement le dernier item du tableau de données :
	 * 						{nros{[-1[ 
 	 * 							… le contenu répétitif à afficher …
 	 * 						]1]}nros}
 	 * 
 	 * 					Pour atteindre la fin du tableau, quelque soit sa longueur, utiliser "]0]" (voir ci-après)
	 * 
	 * 					Quelques exemples d'utilisation dans les gabarits :
	 * 						Uniquement le deuxième item (le premier commence toujours à 0 zéro) :
	 * 							{nros{[1[ 
 	 * 								Le compteur est "{c}" avec la valeur "{v}" 
	 * 							]1]}nros}
	 * 						Uniquement le dernier item :
	 * 							{nros{[-1[ 
 	 * 								Le compteur est "{c}" avec la valeur "{v}" 
	 * 							]1]}nros}
	 * 						Uniquement l'avant-dernier item :
	 * 							{nros{[-2[ 
 	 * 								Le compteur est "{c}" avec la valeur "{v}" 
	 * 							]1]}nros}
	 * 						Les 5 premiers items :
	 * 							{nros{[0[ 
 	 * 								Le compteur est "{c}" avec la valeur "{v}" 
	 * 							]5]}nros}
	 * 						Les 5 derniers items :
	 * 							{nros{[-5[ 
 	 * 								Le compteur est "{c}" avec la valeur "{v}" 
	 * 							]0]}nros}
	 * 						Tous les items sauf les 5 premiers :
	 * 							{nros{[5[ 
 	 * 								Le compteur est "{c}" avec la valeur "{v}" 
	 * 							]0]}nros}
	 * 						Tous les items sauf les 5 derniers :
	 * 							{nros{[0[ 
 	 * 								Le compteur est "{c}" avec la valeur "{v}" 
	 * 							]-5]}nros}
	 *
	 *					On peut également lancer un tri inverse d'un tableau entier ou d'une sélection.
	 * 					Il suffit de placer un point d'éxclamation juste après la balise ouvrante de la boucle
	 * 					ou de la balise ouvrante de la sélection, et un tri inverse se fera, par exemple :
	 * 
	 * 						{tableau{!
 	 * 							<p>tout le tablau à afficher avec un tri inverse</p>
 	 * 						}tableau}
	 * 						{tableau{[0[! 
 	 * 							<p>les 5 premiers éléments avec un tri inverse</p>
	 * 						]5]}tableau}
	 * 
	 * 					La seule contrainte imposée, est de bien coller le signe d'exclamation "!"
	 * 					à la balise précédente pour indiquer au moteur qu'il doit opérer un tri inverse.
	 *
	 *					Il est possible d'afficher des informations concernant le tableau traité 
	 * 					en plaçant des balises hors de la boucle (au dessu ou en dessous par exemple).
	 * 					C'est essentiellement utile lors des affichages avec des sélections, pour 
	 * 					faire une pagination ou lorsqu'on souhaite afficher quelques éléments seulement.
	 * 
	 * 					Il y a 6 informations à récupérer et afficher :
	 * 						'b' = 'begin' (debut) = le numéro de début de l'affichage du tableau
	 * 						'f' = 'finish' (fin) = le numéro de fin de l'affichage du tableau
	 * 						'n' = 'number' (nombre) = le nombre d'éléments affichés (tenant compte des sélections)
	 * 						't' = 'total' = le nombre total d'éléments que contient le tableau
	 * 						'p' = 'page' = le numéro de page correspondant à la sélection affichée 
	 * 						's' = 'sort' (tri) = le type de tri : ascendant '/\' (normal) ou descendant '\/' (inverse)
	 * 							
	 * 						Les indicateurs de tri 's' peuvent être personnalisés via la configuration, clé 'sort' :
	 * 							gabs->conf(array('sort'=>'&#47;&#92;|&#92;&#47;)); : '/\|\/' (&#47 = '/' ; &#92 = '\')
	 * 						
	 * 						La valeur à fournir doit contenir deux éléments séparés par un caractère pipe "|"
	 * 						en premier l'indicateur du tri ascendant, en deuxième le tri descendant : 'asc|desc'
	 * 						
	 * 						Par défaut, les indicateurs utilisées sont composées des simples slash et anti-slash : '/\|\/' 
	 * 						pour assurer un affichage le plus "universel" possible, mais il est possible de fournir 
	 * 						tout indicateur au format texte ou en entité HTML (code ou hexa), voici quelques exemples :
	 * 							'sort'=>'&#9650;|&#9660;' : asc = &#9650; = ▲ = normal ; desc = &#9660; = ▼ = reverse 
	 * 							'sort'=>'&#9206;|&#9207;' : asc = &#9206; = ⏶ = normal ; desc = &#9207; = ⏷ = reverse 
	 * 							'sort'=>'&#128316;|&#128317;' : asc = &#128316; = 🔼 = normal ; desc = &#128317; = 🔽 = reverse 
	 * 							'sort'=>'&#8593;|&#8595;' : asc = &#8593; = ↑ = normal ; desc = &#8595; = ↓ = reverse 
	 * 							'sort'=>'&#8648;|&#8650;' : asc = &#8648; = ⇈ = normal ; desc = &#8650; = ⇊ = reverse 
	 * 							'sort'=>'&#8657;|&#8659;' : asc = &#8657; = ⇑ = normal ; desc = &#8659; = ⇓ = reverse 
	 * 
	 * 					La syntaxe des balises d'infos à utiliser est la suivante :
	 * 						{a_nomBoucle_numeroInstance_codeInfo} = {a_array_1_n} ou {a_array_2_b}
	 * 
	 * 					Pour pouvoir afficher ces informations sur le gabarit, il faut répérer quel est 
	 * 					le numéro d'instance de boucle utilisé avec le même tableau de données, autrement dit :
	 * 						si c'est la première utilisation d'une boucle d'un tableau dans l'ensemble du gabarit 
	 * 						(avec les inclusions statiques), il portera naturellement le numéro d'instance = 1
	 * 						pour les autres boucles du même tableau, leur numéro d'instance sera incrémenté de 1.
	 * 
	 * 					Par exemple, avec un tableau "a_array" :
	 * 						à la première intégration dans le gabarit "{a_array{ … le contenu à répéter … }a_array}"
	 * 						il aura comme numéro d'instance 1, et les informations pourront être récupérées ainsi :
	 * 							{a_array_1_b} ; {a_array_1_f} ; {a_array_1_n} ; {a_array_1_t} ; {a_array_1_p} ; {a_array_1_s}
	 * 						ensuite, à la deuxième intégration dans le gabarit "{a_array{[0[ … le contenu … ]10]}a_array}"
	 * 						les informations à récupérer porteron le numéro d'instance 2 :
	 * 							{a_array_2_b} ; {a_array_2_f} ; {a_array_2_n} ; {a_array_2_t} ; {a_array_2_p} ; {a_array_2_s}
	 * 
	 * 					Avec ces balises, il est simple d'afficher des infos comme : "10 produits : de 20 à 30 sur 50" :
	 * 						{a_array_1_n} produits : de {a_array_1_b} à {a_array_1_f} sur {a_array_1_t}
	 * 
	 * 					IMPORTANT : pour bien définir le numéro d'instance de la boucle correspondante aux informations 
	 * 						qu'on souhaite afficher, il faut considérer TOUTES les boucles traitant le même tableau 
	 * 						de données, même celles qui se trouvent à l'intérieur d'un bloc conditionnel binnaire. 
	 * 						Par exemple, sur ce gabarit :
	 * 							{a_array{ … conetenu … }a_array} … (instance = 1)
	 * 							{b_bool{
 	 * 								{a_array{[0[ … conetenu … ]5]}a_array} … (instance = 2)
 	 * 							}b_bool{
 	 * 								{a_array{[-5[ … conetenu … ]0]}a_array} … (instance = 3)
	 * 							}b_bool}
	 * 							{a_array{! … conetenu … }a_array} … (instance = 4)
	 * 
	 *					Les valeurs globales, issues du tableau de données principal, sont accessibles 
	 * 					à l'intérieur des boucles, suivant le mode choisi dans la configuration 'glob'.
	 * 
	 *					Par ailleurs, notamment pour les tableaux indexés, trois balises sont disponibles
	 *					pour afficher les données (voir "$this->aTools['list']"), à savoir :
	 *						{v} = "v" pour "value", cette balise sert à afficher la valeur
	 *						{k} = "k" pour "key", cette balise sert à afficher la clé
	 *						{c} = "c" pour "counter", cette balise sert à afficher le compteur
	 * 
	 *					Exemple :
	 *						Avec un tableau (array) indexé de données comme suit :
	 *							array("John Doe", "Sarah Connors", "Din Martin")
	 *						Lors du traitement de ce tableau, les balises retournent les données suivantes :
	 *							{v} = "John Doe" ; 			{k} = 0 ; 		{c} = 1
	 *							{v} = "Sarah Connors" ; 	{k} = 1 ; 		{c} = 2
	 *							{v} = "Din Martin" ; 		{k} = 2 ; 		{c} = 3
	 *
	 **/
	private function _getArrs_s($sTag, $aData, $sTemp, $sRemp = '')
	{
		if (!preg_match_all( implode($sTag, $this->aTools['arrs']), $sTemp, $aRes, PREG_SET_ORDER )) {
			return $sTemp;
		}
		// On récupère les éventuelles données globales 
		if (empty($this->aTemps['glob'])) { $this->aTemps['glob'] = $this->_getGlob_a($aData); }
		$nItm = 0;
		foreach ( $aRes as $aItm ) {
			$aVal = $aData;
			$nTot = count($aData);
			$nDbt = 0;
			$nLng = count($aVal);
			$nItm++;
			$nCnt = 1;
			$aRemp = array();
			$bArrSort = $this->_getIsFind_b($aItm[0], $sTag.$this->aTools['tags']['brce'][0].$this->aTools['chng']['sort']);
			if ($bArrSort) { $aVal = array_reverse($aVal); }
			$aItm[1] = ($bArrSort) ? mb_substr($aItm[1], 1) : $aItm[1];
			if(preg_match($this->aTools['itms'], $aItm[0], $aSel)) {
				$bSelSort = $this->_getIsFind_b($aItm[0], $aSel[1].$this->aTools['tags']['brck'][0].$this->aTools['chng']['sort']);
				$nDbt = (int)$aSel[1];
				$nLng = ((bool)(int)$aSel[3]) ? (int)$aSel[3] : $nTot;
				$aItm[1] = ($bSelSort) ? mb_substr($aSel[2], 1) : $aSel[2];
				$aVal = array_slice($aVal, $nDbt, $nLng);
				if ($bSelSort) { $aVal = array_reverse($aVal); }
			}
			foreach ( $aVal as $uKey => $uVal ) { 
				if ( is_array($uVal) ) {
					// On fusionne les éventuelles données globales avec celles de l'item (priorité à ce dernier)
					if (!empty($this->aTemps['glob'])) { $uVal = array_merge($this->aTemps['glob'], $uVal); }
					$aRemp[] = str_replace($this->aTools['list'][1], array($uKey, $nCnt++), $this->_getParse_s($aItm[1], $uVal));
				} else {
					$bTypeRaws = in_array(substr($sTag, 0, 2), $this->aTools['type']['raws']);
					$bChngLoop = $this->_getIsFind_b($aItm[1], $this->aTools['chng']['loop']); 
					if ($this->aConfs['escp'] && !$bTypeRaws && !$bChngLoop) {
						$uVal = $this->_getEscp_s($uVal);
					}
					$aRemp[] = str_replace($this->aTools['list'][0], array($uKey, $uVal, $nCnt++), $aItm[1]);
				}
			}

			// On met à jour le gabarit 
			$sTemp = str_replace($aItm[0], implode('', $aRemp), $sTemp);

			// On construit les infos et on remplace les balises correspondantes dans le gabarit 
			if ( $this->aConfs['info'] ) {				
				$aLabls = array();
				foreach ( $this->aTools['info'] as $sInfo ) { 
					$aLabls[] = implode($sTag.'_'.$nItm.'_'.$sInfo, $this->aTools['tags']['brce']); 
				}
				$aInfos = $this->_getLoopInfos_a($nTot, $nDbt, $nLng);
				$aIndic = explode('|', $this->aConfs['sort']);
				$aInfos[] = ($bArrSort || $bSelSort) ? $aIndic[1] : $aIndic[0];
				$sTemp = str_replace($aLabls, $aInfos, $sTemp);
			}
		}

		// On retourne le gabarit actualisé 
		return $sTemp;
	}

	/*----------------------------------------------------------------------*/

    /**
     * getGlob - Fonction pour récupérer toutes les données scalaires globales 
     * 
	 * @version		0120
     * @param 	aData		array 		Le tableau de données - The data array 
     * @return 	data 		array 		Le tableau de données modifié - The modified data array
     */
	private function _getGlob_a($aData)
	{
		// On repère l'index du début des données scalaires (en excluant les tableaux)
        $nScal = 0;
        foreach ($aData as $key => $val) {
        	if (is_array($val)) { $nScal++; } else { break; }
        }

        // Si aucune donnée scalaire trouvée, on retourne un tableau vide 
        if (!(bool)$nScal) { return array(); }

        // On extrait toutes les données scalaires du tableau de données principal 
    	$aScal = array_slice($aData, $nScal, null, true);

        // Si la syntaxe stricte avec le suffixe '_g' est active,
        // alors on récupère uniquement les données ainsi suffixées 
        // sinon, on retourne la totalité des données scalaires trouvées  
    	if ($this->aConfs['glob']) {
    		$aGlob = array();
    		foreach ($aScal as $k => $v) {
    			if (substr($k, -2, 2) === $this->aTools['glob']) { $aGlob[$k] = $v; }
    		}
    		return $aGlob;
    	} 
		return $aScal;
	}

	/*----------------------------------------------------------------------*/

	/**
	 * getVars - traitement des variables
	 *
	 * @version		0120
	 * @param		sTag		string		Balise à remplacer - Tag to replace
	 * @param		sVal		string		Valeur de remplacement de type string - Replacing string value
	 * @param		sTemp		string		Contenu du template - Template contents
	 * @return		data		string		Nouveau contenu du template - New template contents
	 * @description		Cette fonction traite les valeurs unitaires (les variables)
	 * 
	 * 					La syntaxe à respecter pour utiliser les variables est la suivante :
	 * 						{s_variable} 
	 * 					Les noms des variables correspondent aux clés des données transmises au gabarit.
	 * 
	 * 					NOTE : Si jamais la variable n'existe pas dans le tableau de données fourni, 
	 * 						soit par une suppréssion de la valeur ou par un nommage erroné de la variable, 
	 * 						l'ensemble de la balise sera masquée (commentée) afin de faciliter le débogage.
	 * 
	 * 					IMPORTANT : le nom de la variable DOIT toucher les délimiteurs (accolades par défaut)
	 * 						toute autre syntaxe fera échouer le remplacement par les données correspondantes,
	 * 						Par exemple, les syntaxes suivantes seront ignorées ou produiront un affichage erroné :
	 * 							{ s_variable} ; {s_variable } ; { s_variable }
	 * 						La seule exception autorisée, c'est l'utilisation du changeur de valeur brute "|" :
	 * 							{s_variable|} (voir ci-après)
	 * 
	 * 					Si la configuration 'escp' est en état faux (false), 
	 * 						ou si le "changeur" de valeur brute "raw" = "|" existe au bout de la balise "{s_tag|}"
	 * 						ou si le préfixe de la clé est présent parmi ceux à laisser en l'état ('h_','b_','a_'),
	 * 						alors on retourne la valeur brute, dans tous les autres cas on protège avec "htmlspecialchars()"
	 * 
	 * 					Résumé en algo : 
	 * 							si configuration 'escp' = faux (false) 
	 * 						ou 
	 * 							si prefixe type dans ('h_','b_','a_') 
	 * 						ou 
	 * 							si changeur "|" existe dans la balise '{s_var|}' sur le gabarit
	 * 						alors 	
	 * 							donnée brute (sans échappement)
	 * 						sinon (dans tous les autres cas)
	 * 							donnée protégée (avec échappement)
	 * 
	 * 					Résumé en pseudo-code :
	 * 						if ( conf 'esc' == false || type prfx in ('h_','b_','a_') || chng var "|" exist ) ? raw : escape
	 * 
	 * 					Cette méthode applique également les fonctions-filtres issues des libraireis :
	 * 						"libs/funcs_gabs.php" et/ou "libs/funcs_user.php" 
	 * 						Uniquement si leurs données ont été transmises comme paramètre à GABS. 
	 * 						Les fonctions-filtres de la librairie "funcs_user.php" sont prioritaires (surcharge).
	 * 					
	 * 					Les fonctions-filtres suivent la syntaxe suivante (un seul ou plusieurs enchaînés) :
	 * 						{s_var|f_filtre} ; {s_var|f_filtre_1|f_filtre_2|f_filtre_3}
	 * 
	 * 					CONSEIL : si jamais vous avez souvent besoin d'utiliser plusieurs fonctions-filtres 
	 * 						enchaînés, il est recommandé d'en faire un seul filtre spécifique dans la
	 * 						librairie "funcs_user.php", pour n'appliquer qu'une seule fonction et ainsi optimiser 
	 * 						au mieux ces traitements qui, souvent, sont assez gourmands et qui ont un impact 
	 * 						réel sur les performanes globales de génération du contenu Html final. 
	 * 						De plus, remplacer plusieurs filtres enchaînés par un seul, rend la lecture et 
	 * 						la conception des gabarits bien plus simples et claire.  
	 * 
	 * 					NOTE : Si certains filtres ne sont pas trouvés dans la liste des fonctions diponibles, 
	 * 						un commentaire Html est généré en indiquant le nom des filtres qui ont échoué, 
	 * 						cela permet un débogage plus efficace en regardant le code source de la page.
	 * 
	 * 					IMPORTANT : lors de l'utilisation des fonctions-filtres, pour conserver le changeur 
	 * 						de valeur brute, il est impératif de l'insérer à la fin de la balise, juste avant le 
	 * 						délimiteur fermant, collé à lui : {s_var|filtre_1|filtre_2|} = données brutes filtrées 
	 **/	
	private function _getVars_s($sTag, $sVal, $sTemp)
	{
		if (!preg_match_all( implode($sTag, $this->aTools['vars']), $sTemp, $aRes, PREG_SET_ORDER )) {
			return $sTemp;
		}
		$bEscp = (bool)$this->aConfs['escp'];
		$bTypeRaws = (bool)(in_array(substr($sTag,0,2), $this->aTools['type']['raws'])); 

		// On parcours toutes les balise trouvées dans le gabarit 
		foreach ( $aRes as $aItm ) {
			$sValue = (string)$sVal;
			$aFuncErr = array();
			$bChngRaws = $this->_getIsFind_b($aItm[0], $this->aTools['chng']['vars']); 

			// Si des fonctions-filtres existent dans la balise, on les applique 
			if (isset($aItm[1])) {
				$aFuncs = array_filter(explode($this->aTools['chng']['raws'], $aItm[1]));
				if (empty($this->aFuncs)) {
					if ($this->aConfs['dbug']) { $aFuncErr = $aFuncs; }
				} else {
					foreach ($aFuncs as $sFunc) {
					    if (isset($this->aFuncs[$sFunc])) {
					    	$oFunc = $this->aFuncs[$sFunc];
					        $sValue = $oFunc($sValue);
					    } else {
					    	if ($this->aConfs['dbug']) { $aFuncErr[] = $sFunc; }
					    }
					}
				}
			}

			// On génère un commentaire Html avec le nom des filtres qui ont échoué 
			if ($this->aConfs['dbug'] && !empty($aFuncErr)) {
				$sFuncErr = '<!-- '.implode($this->aTools['chng']['raws'], $aFuncErr).' -->';
				$sTemp = str_replace( $aItm[0], $aItm[0].$sFuncErr, $sTemp );
			}

			// On remplace la balise dans le gabarit par la valeur brute ou échappée 
			if ( !$bEscp || $bTypeRaws || $bChngRaws ) {
				$sTemp = str_replace( $aItm[0], $sValue, $sTemp );
			} else {
				$sTemp = str_replace( $aItm[0], $this->_getEscp_s($sValue), $sTemp );
			}
		}

		// Retour du gabarit modifié 
		return $sTemp;
	}

	/*----------------------------------------------------------------------*/

	/**
	 * getDbug - traitement et insertion des données de débogage dans le gabarit 
	 *
	 * @version		0120
	 * @param		sOrig		string		Le gabarit original complet - The complete original template
	 * @param		aData		array		Le tableau de données - The data table
	 * @param		sTemp		string		Contenu du template - Template contents
	 * @return		data		string		Nouveau contenu du template - New template contents
	 * @description		Cette fonction retourne les données de débogage, protégées "{_}" et brutes "{_|}"
	 * 
	 * 					Les données retournées sont : 
	 * 						- la totalité du tableau de données fournis au gabarit, formatées avec "var_export()"
	 * 						- suivi de l'ensemble des codes du gabarit original utilisé (inclusions statiques comprises)
	 * 
	 * 					Pour pouvoir utiliser toutes ces données de débogage, deux suggestions :
	 * 						- les afficher protégées (échappées) dans une balise de type "pre" = <pre>{_}</pre>
	 * 						- les afficher brutes (non échappées) dans un commentaire html = <!--{_|}-->
	 **/
	private function _getDbug_s($sOrig, $aData, $sTemp)
	{
		$sEscp = implode($this->aTools['dbug']['escp'], $this->aTools['tags']['brce']);
		$sRaws = implode($this->aTools['dbug']['raws'], $this->aTools['tags']['brce']);
		$bEscp = $this->_getIsFind_b($sTemp, $sEscp); 
		$bRaws = $this->_getIsFind_b($sTemp, $sRaws); 
		$sDbug = '';
		if ($bEscp || $bRaws) {
			$sData = var_export($aData, true);
			$sDbug = str_replace(
				array('<!--','-->',$sEscp,$sRaws), 
				array('< !--','-- >','',''), 
				PHP_EOL.$sData.PHP_EOL.'--'.PHP_EOL.$sOrig.PHP_EOL
			);
		}
		if ($bEscp) { $sTemp = str_replace( $sEscp, $this->_getEscp_s($sDbug), $sTemp ); }
		if ($bRaws) { $sTemp = str_replace( $sRaws, $sDbug, $sTemp ); }
		return $sTemp;
	}

	/*----------------------------------------------------------------------*/

    /**
     * setPureCaches - Fonction pour purifier (supprimer) les fichiers et dossiers de cache obsolètes 
     * 
	 * @version		0120
     * @param 	sFold		string 		Chemin vers le dossier des caches - Path to cache folder 
     * @param 	nFiles		integer		Nombre de fichiers à conserver - Number of files to keep 
     * @param 	nProbs		integer		Pourcentage de probabilités de nettoyage - Percentage probability of cleaning 
     * @return 	data 		array 		Toutes les clés trouvées dans les items des tableaux
     */
	private function _setPureCaches_n($sFold, $nFiles=5, $nProbs=100)
	{
		// Tests de traitement 
	    if (!is_dir($sFold)) { return 0; }
	    if (rand(1, $nProbs) !== 1) { return 0; }
	    $aFiles = glob($sFold.'/*.cache');
	    if (count($aFiles) <= $nFiles) { return 0; }
	    
	    // Tri par date des fichiers cache 
	    usort($aFiles, function($a, $b) {
	        return filemtime($b) - filemtime($a);
	    });
	    
	    // Filtre par quantité 
	    $aDel = array_slice($aFiles, $nFiles);
	    $nDel = 0;
	    
	    // Suppression des fichiers 
	    foreach ($aDel as $sFile) {
	        if (@unlink($sFile)) { $nDel++; }
	    }
	    
	    // Suppression du dossier si vide 
        if (count(glob($sFold.'/*')) === 0) {
            @rmdir($sFold);
        }

        // Retour du nombre de fichiers supprimés 
	    return $nDel;
	}

	/*----------------------------------------------------------------------*/

    /**
     * setHide - Fonction pour masquer (commenter) toutes les balises horphélines du gabarit   
     * 
	 * @version		0120
     * @param 	sTemp		string 		La gabarit à nettoyer  
     * @return 	data 		string 		Le gabarit nettoyé  
	 * @description		Cette fonction tente de masquer (commenter) toutes les balises GABS
	 * 					orphélines, c'est à dire, toutes celles qui n'ont pas été remplacées 
	 * 					par des valeurs issues du tableau de données principal.
	 * 
	 * 					Cela permet d'éviter l'affichage des balises non traitées pour l'utilisateur 
	 * 					tout en permettant des les voir dans le code source de la page pour 
	 * 					faciliter le travail de débogage par l'intégrateur.
	 * 
	 * 					Saules les éventuelles balises de débogage '{_}' et/ou '{_|}' présentes 
	 * 					dans la gabarit sont conservées en l'état.
	 *   
	 * 					IMPORTANT : ce masquage s'applique à tout le contenu du gabarit, 
	 * 						si jamais il y avait des textes comportant une syntaxe similaire 
	 * 						aux principales balises de GABS, il est très probable qu'ils soient 
	 * 						aussi masqués (commentés), dans ce cas, plusieurs solutions : 
	 * 							1. Ajouter une espace autour des délimiteurs : "{", "}", "[" et "]"
	 * 								pour les textes qu'on souhaite conserver visibles, par exemple : 
	 * 								"{ mon texte }" ou "[ mon texte ]" 
	 * 							2. Convertir les délimiteurs en entités HTML, comme suit ; 
	 * 								"{" = "&#123;" ; "}" = "&#125;" ; "[" = "&#91;" ; "]" = "&#93;"
	 * 								"{mon texte}" = "&#123;mon texte&#125;" 
	 * 								"[mon texte]" = "&#91;mon texte&#93;" 
	 * 							3. Désactiver cette fonctionnalité via le système de configuration :
	 * 								gabs->conf(array('hide'=>false))
	 * 								ce qui va produire l'affichage des éventuelles balise orphélines.
     */
	private function _setHide_a($sTemp)
	{
		foreach ($this->aTools['hide'] as $sMask) {
			if (preg_match_all( $sMask, $sTemp, $aRes, PREG_SET_ORDER )) {
				foreach ($aRes as $aTag) {
					if (!in_array($aTag[0], $this->aTools['dbug']['test'])) {
						$sTemp = str_replace( $aTag[0], '<!-- '.$aTag[0].' -->', $sTemp );
					}
				}
			}
		}
		return $sTemp;
	}

	/*----------------------------------------------------------------------*/

    /**
     * getLoopInfos - Helper : Informations numériques sur l'affichage des boucles  
     * 
	 * @version		0120
     * @param 	nTot		number 		Le nombre total d'items du tableau 
     * @param 	nDbt		number 		Le numéro d'index de début de séléction  
     * @param 	nLng		number 		Le nombre d'items à sélectionner (longueur)  
     * @return 	data 		array 		Un tableau avec toutes les infos calculée 
     */
	private function _getLoopInfos_a($nTot, $nDbt, $nLng)
	{
	    // Sécurité 
	    $nTot  = max(0, (int)$nTot);
	    $nDbt = (int)$nDbt;
	    $nLng = (int)$nLng;

	    // Début & Fin
	    if ($nDbt >= 0) { $nSta = $nDbt; } else { $nSta = $nTot + $nDbt; }
	    if ($nLng >= 0) { $nEnd = $nSta + $nLng; } else { $nEnd = $nTot + $nLng; }

	    // Bornage & Nombre
	    $nSta = max(0, min($nSta, $nTot));
	    $nEnd   = max($nSta, min($nEnd, $nTot));
	    $nNbr = $nEnd - $nSta;

	    // Calcul de la page
	    $nPge = 1;
	    if ($nNbr > 0 && $nLng > 0) {
	        $nPge = floor($nSta / $nNbr) + 1;
	    }

	    // Retour du tableau d'infos 
	    return array($nSta+1,$nEnd,$nNbr,$nTot,$nPge);
	}

	/*----------------------------------------------------------------------*/

	/**
	 * getEscp - Helper : protège les textes XSS et converti les balises Gabs en entités Html 
	 *
	 * @version		0120
	 * @param		sVal		string		Le texte à protéger - The text to protect
	 * @return		data		string		Vrai si texte existe, sinon faux - True if text exists, otherwise false
	 * @description		Cette méthode protège les valeurs issus du tableau des données, pour éviter surtout 
	 * 					toute faille de sécurité de type XSS (Cross-Site-Scripting), mais elle permet aussi 
	 * 					de protéger les balises utilisées par GABS, pour éviter tout remplacement erronné.
	 **/
	private function _getEscp_s($sVal)
	{
		return str_replace(
			$this->aTools['html']['tags'], 
			$this->aTools['html']['html'], 
			htmlspecialchars((string)$sVal, ENT_QUOTES, 'UTF-8')
		);
	}

	/*----------------------------------------------------------------------*/

	/**
	 * getIsFind - Helper : recherche texte dans texte : est-ce que sFind existe dans sCont ?
	 *
	 * @version		0120
	 * @param		sCont		string		Le texte conteneur - The container text
	 * @param		sFind		string		Le texte à trouver - The text to find
	 * @return		data		boolean		Vrai si texte existe, sinon faux - True if text exists, otherwise false
	 * @description		Cette méthode cherche un texte dans un autre selon sa position, via la fonction "strpos()"
	 * 					Elle retourne une valeur booléene : vrai (true) si le texte est trouvé, sinon faux (false)
	 **/
	private function _getIsFind_b($sCont, $sFind)
	{
		return (bool)(strpos($sCont, $sFind) !== false);
	}

	/*----------------------------------------------------------------------*/

}
