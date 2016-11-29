<?php
###########################################
# Paulo Collares Moreira Neto
# 27 de dezembro de 2011
# www.paulocollares.com.br
###########################################

//Esta Funחדo transforma o texto em minתsculo respeitando a acentuaחדo
function str_minuscula($texto) { 
    $texto = strtr(strtolower($texto),"ְֱֲֳִֵֶַָֹÊֻּֽ־ֿ׀ׁׂ׃װױײ׳״ÙÜÚÞßַ","אבגדהוזחטיךכלםמןנסעףפץצקרשüת‏ÿח"); 
    return $texto; 
} 

//Esta Funחדo transforma o texto em maiתsculo respeitando a acentuaחדo
function str_maiuscula($texto) { 
    $texto = strtr(strtoupper($texto),"אבגדהוזחטיךכלםמןנסעףפץצקרשüת‏ÿח","ְֱֲֳִֵֶַָֹÊֻּֽ־ֿ׀ׁׂ׃װױײ׳״ÙÜÚÞßַ"); 
    return $texto; 
} 

//Esta Funחדo transforma a primeira letra do texto em maiתsculo respeitando a acentuaחדo
function primaira_maiuscula($texto) { 
    $texto = strtr(ucfirst($texto),"ְֱֲֳִֵֶַָֹÊֻּֽ־ֿ׀ׁׂ׃װױײ׳״ÙÜÚÞßַ","אבגדהוזחטיךכלםמןנסעףפץצקרשüת‏ÿח"); 
    return $texto; 
} 

//Verifica se a palavra estב toda em maiתscula
function comparaPalavraMaiuscula($palavra){
	
$palavra=str_replace(" ","",$palavra);

if ($palavra=="") return false;
if ($palavra=="[:p:]")  return false;
if (strlen($palavra)<=1) return false;

$palavra=ereg_replace("[^a-zA-Z0-9]", "", strtr($palavra, "באדגיךםףפץתüחְֱֲֳֹÊֽ׃װױÚÜַ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));

if ($palavra == str_maiuscula($palavra))
	return true;

return false;
}


/////////////////////////////////
//Filtro
/////////////////////////////////

function filtro($texto){
	
	//Variבveis
	$pontuacoes=array(",",".","!","?",";");
	
	$array_abreviado=array("vc","tb","jesus","naum","ס","pq");
	$array_abr_certo=array("vocך","tambיm","Jesus","nדo","nדo","porque");

	//Prepara paragrafo
	$texto=str_replace("
","[:p:]",$texto);

	//acerta maiתscula e minתscula e inicia as sentenחas com a primeira letra maiתscula
	$array=explode(" ",$texto);
	$novo_texto="";
	$tam_array=count($array);

	for ($i=0;$i<$tam_array;$i++){
		$palavra=$array[$i];	

		if(comparaPalavraMaiuscula($palavra)) 
			$nova_palavra=str_minuscula($palavra);
		else
			$nova_palavra=$palavra;
	
		$caracter_anterior=substr($array[$i-1],-1);
		$caracter_anterior_paragrafo=substr($array[$i-1],-5);

		if ($caracter_anterior=="." || $caracter_anterior=="!" || $caracter_anterior=="?" || $caracter_anterior_paragrafo=="[:p:]" || $i==0)
			$nova_palavra=primaira_maiuscula($nova_palavra);
	
		$novo_texto.=$nova_palavra." ";
	}

	$texto=$novo_texto;

	//Adicionar espaחoes depois das pontuaחץes e remover antes
	for ($i=0;$i<count($pontuacoes);$i++){
		$ponto=$pontuacoes[$i];
		$texto=str_replace(" ".$ponto." ",$ponto." ",$texto);
		$texto=str_replace(" ".$ponto,$ponto." ",$texto);
		$texto=str_replace($ponto,$ponto." ",$texto);
	}

	//acerta parךnteses
	$texto=str_replace(" ( "," (",$texto);
	$texto=str_replace("( "," (",$texto);
	$texto=str_replace("("," (",$texto);
	$texto=str_replace(" ) ",") ",$texto);
	$texto=str_replace(" )",") ",$texto);
	$texto=str_replace(")",") ",$texto);

	//acerta redicencias
	$texto=str_replace(". . .","...",$texto);

	//remove mais de uma ! e ?
	$texto=str_replace("! ! ! ","!",$texto);
	$texto=str_replace("! ! ","!",$texto);
	$texto=str_replace("!!","!",$texto);
	$texto=str_replace("? ? ? ","?",$texto);
	$texto=str_replace("? ? ","?",$texto);
	$texto=str_replace("??","?",$texto);

	//remover espaחoes em branco extras
	$texto=str_replace("   "," ",$texto);
	$texto=str_replace("  "," ",$texto);
	$texto=str_replace("  "," ",$texto);

	//Adicionar paragrafo
	$texto=str_replace("\n","",$texto);
	$texto=str_replace("\r","",$texto);

	for ($i=0;$i<=10;$i++)
		$texto=str_replace("[:p:][:p:]","[:p:]",$texto);

	$array=explode("[:p:]",$texto);
	$novo_texto="";
	$tam_array=count($array);
	for ($i=0;$i<$tam_array;$i++){
		$paragrafo=$array[$i];	
	
		$paragrafo=trim($paragrafo);
		$paragrafo=trim($paragrafo,",");
		$paragrafo=primaira_maiuscula($paragrafo);
	
		if ($paragrafo=="") break;

		$ultimo_caracter=substr($paragrafo,-1);

		if ($ultimo_caracter!='.' && $ultimo_caracter!='!' && $ultimo_caracter!='?' && $ultimo_caracter!=':' && $ultimo_caracter!=';')
			$paragrafo.=".";

		if ($i!=$tam_array)
			$novo_texto.=$paragrafo."

";

	}

	$texto=$novo_texto;


	//Expandir palavras abreviadas
	$texto=str_replace($array_abreviado,$array_abr_certo,$texto);


	return $texto;

}

?>