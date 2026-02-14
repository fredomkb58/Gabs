<?php
/**
 * Gabs
 * @version		0120 - PHP 5.6+ 
 * @role		Moteur de gabarits Php-Html - Php-Html Template Engine 
 * @slogan		{ logique sans bruit && design sans echo } 
 * 				{ logic without noise && design without echo } 
 * @licence		Projet libre et open-source - Free and open-source project 
 * @github		https://github.com/fredomkb58/Gabs.git
 * @copyright	FredoMkb © 2026 
 * ----------
**/
class Gabs
{
	private $aTools = array();
	private $aConfs = array( 'cach'=>true, 'dbug'=>true, 'escp'=>true, 'pure'=>true, 'hide'=>true, 'incs'=>true, 'bool'=>true, 'arrs'=>true, 'info'=>true, 'glob'=>true, 'sort'=>'&#47;&#92;|&#92;&#47;', 'fold'=>'cache', 'tpls'=>'', ); 
	private $aTemps = array();
	private $aFuncs = array();

	// Constructeur
	public function __construct($sL='{', $sR='}', $sG='[', $sD=']') {
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

	// get - fonction publique principale
	public function get($sTemp, $aData, $aFuncs = array()) {
		if ( empty($sTemp) ) { return ''; }
		if ( empty($aData) ) { return $sTemp; }
		$bIncs = $this->aConfs['incs'];
		$bCach = $this->aConfs['cach'];
		$bDbug = $this->aConfs['dbug'];
		$bHide = $this->aConfs['hide'];
		$sTemp = $this->_getTemp_s($sTemp); 
		if ( $bIncs ) { $sTemp = $this->_getIncs_s($sTemp); }
		if ( $bCach ) {
			$sFold = $this->aConfs['fold'].'/'.md5($sTemp);
			$sFile = md5(serialize($aData)).'.cache';
			$sPath = $sFold.'/'.$sFile;
			if ( is_file($sPath) ) { return file_get_contents($sPath); }
		} 
		if (!empty($aFuncs)) { $this->aFuncs = $aFuncs; }
		$sOrig = ($bDbug) ? $sTemp : '';
		$this->aTemps = array('glob'=>array());
		$aData = $this->_getPrep_a($aData);
		$sTemp = $this->_getParse_s($sTemp, $aData);
		if ( $bIncs ) { 
			$sIncs = $this->_getIncs_s($sTemp); 
			if ( $sTemp !== $sIncs ) { $sTemp = $this->_getParse_s($sIncs, $aData); }
		}
		if ( $bHide ) { $sTemp = $this->_setHide_a($sTemp); }
		if ( $bCach ) {
			if ( is_dir($sFold) || mkdir($sFold, 0755, true) ) {
				if ( $this->aConfs['pure'] ) { $this->_setPureCaches_n($sFold); }
				file_put_contents($sPath, $sTemp); 
			}
		}
		if ( $bDbug ) { $sTemp = $this->_getDbug_s($sOrig, $aData, $sTemp); }
		return $sTemp;
	}

	// conf - fonction publique pour régler les paramètres des traitements
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
					$this->aConfs[$sKey] = ($this->_getIsFind_b($sText, $sKey)) ? rtrim((string)$uVal, '/') : (bool)$uVal;
				}
			}
		}
	} 

	// getTemp - lecture et/ou retour du contenu du gabarit
	private function _getTemp_s($sTemp) {
		return is_file($sTemp) ? file_get_contents($sTemp) : $sTemp;
	}

	// getIncs - traite les inclusions et retourne le gabarit brut modifié
	private function _getIncs_s($sTemp) {
		if ( !preg_match_all( $this->aTools['incs'], $sTemp, $aRes, PREG_SET_ORDER )) { return $sTemp; }
	    $sBase = (empty($this->aConfs['tpls'])) ? realpath('./') : realpath('./'.$this->aConfs['tpls']);
	    if ($sBase === false) { return $sTemp; }
	    foreach ( $aRes as $aItm ) {
	        $sReal = realpath($sBase.'/'.$aItm[1]);
	        if ( ($sReal === false) || (strpos($sReal, $sBase) !== 0) ) { continue; }
			$sTemp = str_replace($aItm[0], $this->_getTemp_s($sReal), $sTemp);
	    }
		return $sTemp;
	}

	// getPrep - Préparation des données pour les remplacements (parsing)
    private function _getPrep_a($aData) {
        if ( $this->aConfs['bool'] || $this->aConfs['arrs'] ) {
	        uksort($aData, function($sKey1, $sKey2) use ($aData) {
	            $uVal1 = $aData[$sKey1];
	            $uVal2 = $aData[$sKey2];
				$nTyp1 = is_array($uVal1) ? 1 : (is_bool($uVal1) ? 2 : 3);
				$nTyp2 = is_array($uVal2) ? 1 : (is_bool($uVal2) ? 2 : 3);
	            return $nTyp1 - $nTyp2;
	        });
	    }
	    return $aData;
    }

    // getParse - parsage et construction du gabarit final
    private function _getParse_s($sTemp, $aData) {
        foreach ( $aData as $sTag => $uVal ) {
            if ( !$this->_getIsFind_b($sTemp, $this->aTools['tags']['brce'][0].$sTag) ) { continue; }
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
        return $sTemp;
    }

    // getBool - traite les conditions booléenes et retourne le gabarit modifié
    private function _getBool_s($sTag, $bVal, $sTemp) {
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

    // getBoolPart - traitement des parties d'un bloc conditionnel
    private function _getBoolPart_s($aRes, $nNro, $sTemp) {
        foreach ( $aRes as $aItm ) {
        	$sRemp = (isset($aItm[$nNro])) ? trim($aItm[$nNro]) : '';
            $sTemp = str_replace($aItm[0], $sRemp, $sTemp);
        }
        return $sTemp;
    }

    // getArrs - traitement des tableaux (boucles)
	private function _getArrs_s($sTag, $aData, $sTemp, $sRemp = '') {
		if (!preg_match_all( implode($sTag, $this->aTools['arrs']), $sTemp, $aRes, PREG_SET_ORDER )) { return $sTemp; }
		if (empty($this->aTemps['glob'])) { $this->aTemps['glob'] = $this->_getGlob_a($aData); }
		$nItm = 0;
		foreach ( $aRes as $aItm ) {
			$aVal = $aData; $nTot = count($aData); $nDbt = 0; $nLng = count($aVal); $nItm++; $nCnt = 1;
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
					if (!empty($this->aTemps['glob'])) { $uVal = array_merge($this->aTemps['glob'], $uVal); }
					$aRemp[] = str_replace($this->aTools['list'][1], array($uKey, $nCnt++), $this->_getParse_s($aItm[1], $uVal));
				} else {
					$bTypeRaws = in_array(substr($sTag, 0, 2), $this->aTools['type']['raws']);
					$bChngLoop = $this->_getIsFind_b($aItm[1], $this->aTools['chng']['loop']); 
					if ($this->aConfs['escp'] && !$bTypeRaws && !$bChngLoop) { $uVal = $this->_getEscp_s($uVal); }
					$aRemp[] = str_replace($this->aTools['list'][0], array($uKey, $uVal, $nCnt++), $aItm[1]);
				}
			}
			$sTemp = str_replace($aItm[0], implode('', $aRemp), $sTemp);
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
		return $sTemp;
	}

	// getGlob - Fonction pour récupérer toutes les données scalaires globales 
	private function _getGlob_a($aData) {
        $nScal = 0;
        foreach ($aData as $key => $val) { if (is_array($val)) { $nScal++; } else { break; } }
        if (!(bool)$nScal) { return array(); }
    	$aScal = array_slice($aData, $nScal, null, true);
    	if ($this->aConfs['glob']) {
    		$aGlob = array();
    		foreach ($aScal as $k => $v) { if (substr($k, -2, 2) === $this->aTools['glob']) { $aGlob[$k] = $v; } }
    		return $aGlob;
    	}
		return $aScal;
	}

	// getVars - traitement des variables nomées 
	private function _getVars_s($sTag, $sVal, $sTemp) {
		if (!preg_match_all( implode($sTag, $this->aTools['vars']), $sTemp, $aRes, PREG_SET_ORDER )) { return $sTemp; }
		$bEscp = (bool)$this->aConfs['escp']; 
		$bTypeRaws = (bool)(in_array(substr($sTag,0,2), $this->aTools['type']['raws'])); 
		foreach ( $aRes as $aItm ) {
			$sValue = (string)$sVal; $aFuncErr = array();
			$bChngRaws = $this->_getIsFind_b($aItm[0], $this->aTools['chng']['vars']); 
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
			if ($this->aConfs['dbug'] && !empty($aFuncErr)) {
				$sFuncErr = '<!-- '.implode($this->aTools['chng']['raws'], $aFuncErr).' -->';
				$sTemp = str_replace( $aItm[0], $aItm[0].$sFuncErr, $sTemp );
			}
			if ( !$bEscp || $bTypeRaws || $bChngRaws ) {
				$sTemp = str_replace( $aItm[0], $sValue, $sTemp );
			} else {
				$sTemp = str_replace( $aItm[0], $this->_getEscp_s($sValue), $sTemp );
			}
		}
		return $sTemp;
	}

	// getDbug - traitement et insertion des données de débogage dans le gabarit
	private function _getDbug_s($sOrig, $aData, $sTemp) {
		$sEscp = implode($this->aTools['dbug']['escp'], $this->aTools['tags']['brce']);
		$sRaws = implode($this->aTools['dbug']['raws'], $this->aTools['tags']['brce']);
		$bEscp = $this->_getIsFind_b($sTemp, $sEscp); 
		$bRaws = $this->_getIsFind_b($sTemp, $sRaws); 
		$sDbug = '';
		if ($bEscp || $bRaws) {
			$sData = var_export($aData, true);
			$sDbug = str_replace( array('<!--','-->',$sEscp,$sRaws), array('< !--','-- >','',''), PHP_EOL.$sData.PHP_EOL.'--'.PHP_EOL.$sOrig.PHP_EOL );
		}
		if ($bEscp) { $sTemp = str_replace( $sEscp, $this->_getEscp_s($sDbug), $sTemp ); }
		if ($bRaws) { $sTemp = str_replace( $sRaws, $sDbug, $sTemp ); }
		return $sTemp;
	}

	// setPureCaches - Fonction pour purifier (supprimer) les fichiers et dossiers de cache obsolètes
	private function _setPureCaches_n($sFold, $nFiles=5, $nProbs=100) {
	    if (!is_dir($sFold)) { return 0; }
	    if (rand(1, $nProbs) !== 1) { return 0; }
	    $aFiles = glob($sFold.'/*.cache');
	    if (count($aFiles) <= $nFiles) { return 0; }
	    usort($aFiles, function($a, $b) { return filemtime($b) - filemtime($a); });
	    $aDel = array_slice($aFiles, $nFiles); $nDel = 0;
	    foreach ($aDel as $sFile) { if (@unlink($sFile)) { $nDel++; } }
        if (count(glob($sFold.'/*')) === 0) {  @rmdir($sFold); }
	    return $nDel;
	}

	// setHide - Fonction pour masquer (commenter) toutes les balises horphélines du gabarit
	private function _setHide_a($sTemp) {
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

	// getLoopInfos - Helper : Informations numériques sur l'affichage des boucles
	private function _getLoopInfos_a($nTot, $nDbt, $nLng) {
	    $nTot  = max(0, (int)$nTot); $nDbt = (int)$nDbt; $nLng = (int)$nLng;
	    if ($nDbt >= 0) { $nSta = $nDbt; } else { $nSta = $nTot + $nDbt; }
	    if ($nLng >= 0) { $nEnd = $nSta + $nLng; } else { $nEnd = $nTot + $nLng; }
	    $nSta = max(0, min($nSta, $nTot));
	    $nEnd   = max($nSta, min($nEnd, $nTot));
	    $nNbr = $nEnd - $nSta; $nPge = 1;
	    if ($nNbr > 0 && $nLng > 0) { $nPge = floor($nSta / $nNbr) + 1; }
	    return array($nSta+1,$nEnd,$nNbr,$nTot,$nPge);
	}

	// getEscp - Helper : protège les textes XSS et converti les balises Gabs en entités Html
	private function _getEscp_s($sVal) {
		return str_replace( $this->aTools['html']['tags'], $this->aTools['html']['html'], htmlspecialchars((string)$sVal, ENT_QUOTES, 'UTF-8') );
	}

	// getIsFind - Helper : recherche texte dans texte : est-ce que sFind existe dans sCont ?
	private function _getIsFind_b($sCont, $sFind) {
		return (bool)(strpos($sCont, $sFind) !== false);
	}
}
