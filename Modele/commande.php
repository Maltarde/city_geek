<?php

//Fonction Récuperation

function recupererCommandeIdClient($bdd, $id_client)
{
	$membre = $bdd->query('SELECT * FROM commande WHERE id_client=\''.$id_client.'\'');
	return $membre;
}

?>