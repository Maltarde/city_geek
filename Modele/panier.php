<?php
//Fonction de création

function creerPanier($bdd, $pseudo, $id_article, $nombre)
{	
$req = $bdd->prepare('INSERT INTO panier (pseudo, id_article, nombre, date) 
					VALUES( :pseudo, :id_article, :nombre, NOW())');

$req->execute(array(
    'pseudo' => $pseudo,
    'id_article' => $id_article,
    'nombre' => $nombre));
}

//Fonction Récuperation

function recupererPanierPseudo($bdd, $pseudo)
{
	$membre = $bdd->query('SELECT * FROM panier WHERE pseudo=\''.$pseudo.'\'');
	return $membre;
}

//Fonction Changement

function changementPanierPseudo($bdd, $ID, $nombre)
{
	$req = $bdd->prepare('UPDATE panier SET nombre=:nombre 
						  WHERE  ID=\''.$ID.'\'');
	
	$req->execute(array(
		'nombre' => $nombre));	
}

function changementPanierPseudoIdPanier($bdd, $pseudo, $id_article, $nombre)
{
	$req = $bdd->prepare('UPDATE panier SET nombre=:nombre 
						  WHERE  pseudo=\''.$pseudo.'\' and id_article=\''.$id_article.'\'');
	
	$req->execute(array(
		'nombre' => $nombre));	
}

//Fontion supprimé

function supprimePanier($bdd, $pseudo, $id_article)
{	
$req = $bdd->prepare('DELETE from panier WHERE pseudo=:pseudo and id_article=:id_article');

$req->execute(array(
    'pseudo' => $pseudo,
    'id_article' => $id_article));
}
?>