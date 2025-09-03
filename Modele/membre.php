<?php
// Fonction création

function creerMembre($bdd, $pseudo, $pass, $droit, $mail)
{	
$req = $bdd->prepare('INSERT INTO membre(pseudo, pass, mail, droit, date) 
					VALUES( :pseudo, :pass, :mail, :droit, NOW())');

$req->execute(array(
    'pseudo' => $pseudo,
    'pass' => $pass,
    'mail' => $mail,
	'droit' => $droit));
}

//Fonction Récuperation

function recupererMembre($bdd, $pseudo)
{
	$membre = $bdd->query('SELECT * FROM membre WHERE pseudo=\''.$pseudo.'\'');
	return $membre;
}

function recupererMembreIdCroissant($bdd)
{
	$membre = $bdd->query('SELECT * FROM membre order by ID');
	return $membre;
}

//Fonction de Changement

function changementMembre($bdd, $pseudo, $pass)
{
	$req = $bdd->prepare('UPDATE membre SET pass=:pass 
						  WHERE  pseudo=\''.$pseudo.'\'');
	
	$req->execute(array(
		'pass' => $pass));	
}
?>