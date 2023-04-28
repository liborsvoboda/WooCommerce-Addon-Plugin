<?php

if (strlen(@$_GET['ico']) == 10){
		$client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");
		$result = $client->checkVat(array(
		  'countryCode' => 'SK',
		  'vatNumber' => @$_GET['ico']
		));
	if ($result->valid == true){
		echo 'billing_company.value = \''.$result->name.'\';';

		preg_match('/.+?([\d{5}])/', $result->address, $matches,PREG_OFFSET_CAPTURE);
		echo 'billing_address_1.value = \''.$matches[0][0].'\';';

		preg_match('/\d{5}/', $result->address, $matches,PREG_OFFSET_CAPTURE);
		echo 'billing_postcode.value = \''.$matches[0][0].'\';';

		preg_match('/(?<=\d{5}.).*/', $result->address, $matches,PREG_OFFSET_CAPTURE);
		echo 'billing_city.value = \''.str_replace(" Slovensko","",$matches[0][0]).'\';';

		echo 'billing_country.value = document.querySelector(\'#country\\\\ \').value;';
		echo 'billing_state.value = \'SK\';';
		echo 'icoStatus.value = \'Ok\';';
	} else {
		echo 'icoStatus.value = \'notOk\';';
	}
} else {
	echo 'icoStatus.value = \'notOk\';';
}
	//echo 'document.querySelector("#ico").classList = "input hideElement";';
	//echo 'document.querySelector("#skico").classList = "showElement";';

	echo 'document.querySelector("#ico").style.display = "none";';
	echo 'if (document.getElementById("skico") !=null) {document.querySelector("#skico").style.display = "flex";}';
	echo 'if (document.getElementById("skicolink") !=null) {document.querySelector("#skicolink").style.display = "block";}';
	echo 'if (document.getElementById("skdiclbl") !=null) {document.querySelector("#skdiclbl").style.display = "block";}';
	//echo 'ico.placeholder = \'DIČ\';';
	//echo 'ico.maxlength = 10;';
	//echo 'var icoDescr = icolabel.outerHTML.toString();';
	//echo 'icolabel.outerHTML = icoDescr.replace("IČO", "DIČ");';
?>
