<?php

if (strlen(@$_GET['ico']) == 8){
	$xmlDoc = new DOMDocument();
	$xmlDoc->load('http://wwwinfo.mfcr.cz/cgi-bin/ares/darv_std.cgi?ico='.$_GET['ico']);
	//$part= $xmlDoc->documentElement;
	//$xmlDoc->saveXML();
	$xpath = new DOMXPath($xmlDoc);
	if (strlen($xpath->evaluate("string(/are:Ares_odpovedi/are:Odpoved/are:Zaznam/are:Obchodni_firma)")) > 0 ){
		echo 'document.querySelector("#ico").style.color = "white";';
		echo 'document.querySelector("#submitButton").disabled = false;';
	} else {
		echo 'document.querySelector("#ico").style.color = "red";';
		echo 'document.querySelector("#submitButton").disabled = true;';
	}
} else {
	echo 'document.querySelector("#ico").style.color = "red";';
	echo 'document.querySelector("#submitButton").disabled = true;';
}
	//echo 'document.querySelector("#ico").classList = "input showElement";';
    //echo 'document.querySelector("#skico").classList = "hideElement";';
	echo 'document.querySelector("#ico").style.display = "block";';
	echo 'if (document.getElementById("skico") !=null) {document.querySelector("#skico").style.display = "none";}';
	//echo 'ico.placeholder = \'IČO\';';
	//echo 'ico.maxlength = 8;';
	//echo 'var icoDescr = icolabel.outerHTML.toString();';
	//echo 'icolabel.outerHTML = icoDescr.replace("DIČ", "IČO");';
?>
