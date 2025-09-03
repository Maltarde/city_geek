<?php

function creerArticleDescription ($bdd, $categorie, $nom, $description, $prix, $prixsecond, $nom_photo, $note, $stock, $total_achete, $total_vente, $en_vente)
{	
$req = $bdd->prepare('INSERT INTO article_description (categorie, nom, description, prix, prixsecond, nom_photo, note, stock, total_achete, total_vente, en_vente, date) 
					VALUES( :categorie, :nom, :description, :prix, :prixsecond, :nom_photo, :note, :stock, :total_achete, :total_vente, :en_vente, NOW())');

$req->execute(array(
    'categorie' => $categorie,
    'nom' => $nom,
    'description' => $description,
	'prix' => $prix,
	'prixsecond' => $prixsecond,
	'nom_photo' => $nom_photo,
	'note' => $note,
	'stock' => $stock,
	'total_achete' => $total_achete,
	'total_vente' => $total_vente,
	'en_vente' => $en_vente));
}

//Fonction recuperation

function recupererArticleDescriptionTout($bdd)
{
	$membre = $bdd->query('SELECT * FROM article_description');
	return $membre;
}

function recupererArticleDescriptionID($bdd, $ID)
{
	$membre = $bdd->query('SELECT * FROM article_description WHERE ID=\''.$ID.'\'');
	return $membre;
}

function recupererArticleDescriptionCategorie($bdd, $categorie)
{
	$membre = $bdd->query('SELECT * FROM article_description WHERE categorie=\''.$categorie.'\'');
	return $membre;
}

function recupererArticlePrixCroissant($bdd)
{
	$membre = $bdd->query('SELECT * FROM article_description ORDER BY prix');
	return $membre;
}

function recupererArticlePrixDecroissant($bdd)
{
	$membre = $bdd->query('SELECT * FROM article_description ORDER BY prix DESC');
	return $membre;
}

function recupererArticlePrixCroissantRecherhce($bdd, $categorie)
{
	$membre = $bdd->query('SELECT * FROM article_description WHERE categorie=\''.$categorie.'\' ORDER BY prix');
	return $membre;
}

function recupererArticlePrixDecroissantRecherhce($bdd, $categorie)
{
	$membre = $bdd->query('SELECT * FROM article_description WHERE categorie=\''.$categorie.'\' ORDER BY prix DESC');
	return $membre;
}

function recupererArticleId($bdd)
{
	$membre = $bdd->query('SELECT * FROM article_description ORDER BY ID');
	return $membre;
}

function recupererArticleIdDecroissant($bdd)
{
	$membre = $bdd->query('SELECT * FROM article_description ORDER BY ID DESC');
	return $membre;
}

//Fonction Changement

function changementArticleDescriptionPrix ($bdd, $ID, $prix)
{
	$req = $bdd->prepare('UPDATE article_description SET prix=:prix WHERE  ID=\''.$ID.'\'');
	
	$req->execute(array(
		'pass' => $pass));	
}

function changementArticleDescriptionTotalAchete($bdd, $ID, $TotalAchete)
{
	$req = $bdd->prepare('UPDATE article_description SET total_achete=:TotalAchete WHERE  ID=\''.$ID.'\'');
	
	$req->execute(array(
		'TotalAchete' => $TotalAchete));
}

function changementArticleDescriptionStock($bdd, $ID, $stock)
{
	$req = $bdd->prepare('UPDATE article_description SET stock=:stock WHERE  ID=\''.$ID.'\'');
	
	$req->execute(array(
		'stock' => $stock));
}
?>