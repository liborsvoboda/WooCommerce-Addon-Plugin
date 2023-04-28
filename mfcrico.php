<?php

if (strlen(@$_GET['ico']) == 8){
	$xmlDoc = new DOMDocument();
	$xmlDoc->load('http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_std.cgi?ico='.$_GET['ico']);
	//$part= $xmlDoc->documentElement;
	//$xmlDoc->saveXML();
	$xpath = new DOMXPath($xmlDoc);
	if (strlen($xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Obchodni_firma)")) > 0 ){
		echo 'billing_company.value = \''.$xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Obchodni_firma)").'\';';
		echo 'billing_address_1.value = \''.(($xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:Nazev_ulice)"))?$xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:Nazev_ulice)").' '. $xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:Cislo_domovni)") : $xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:Nazev_casti_obce)").' '.$xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:Cislo_domovni)")).(($xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:Cislo_orientacni)"))?'/'.$xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:Cislo_orientacni)"):'').'\';';
		echo 'billing_city.value = \''.$xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:Nazev_obce)").'\';';
		echo 'billing_postcode.value = \''.$xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Identifikace/are:Adresa_ARES/dtt:PSC)").'\';';
		echo 'billing_country.value = document.querySelector(\'#country\\\\ \').value;';
		echo 'billing_state.value = \'CZ\';';
		echo 'icoStatus.value = \'Ok\';';
	} else {
		echo 'icoStatus.value = \'notOk\';';
	}
} else {
	echo 'icoStatus.value = \'notOk\';';
}
	//echo 'document.querySelector("#ico").classList = "input showElement";';
    //echo 'document.querySelector("#skico").classList = "hideElement";';
	echo 'document.querySelector("#ico").style.display = "block";';
	echo 'if (document.getElementById("skico") !=null) {document.querySelector("#skico").style.display = "none";}';
	echo 'if (document.getElementById("skicolink") !=null) {document.querySelector("#skicolink").style.display = "none";}';
	echo 'if (document.getElementById("skdiclbl") !=null) {document.querySelector("#skdiclbl").style.display = "none";}';

	//echo 'ico.placeholder = \'IČO\';';
	//echo 'ico.maxlength = 8;';
	//echo 'var icoDescr = icolabel.outerHTML.toString();';
	//echo 'icolabel.outerHTML = icoDescr.replace("DIČ", "IČO");';
?>
