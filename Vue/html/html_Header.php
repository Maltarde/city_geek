<img src="../Vue/image/logo/logo.jpg" class="logo">
<div class="reach">
	<form  method="post" action="<?php echo''.$action.''; ?>.php" class="formulaire">
		<select name="rechercheCategorie" placeholder="Catégorie" class="categorie" >
			<option value="tous">Tous</option>
			<option value="nouveaute">Nouveauté</option>
			<option value="console">Console</option>
			<option value="jeuVideo">Jeu vidéo</option>
			<option value="tShirtGeek">T-shirt Geek</option>
			<option value="serieFilm">Série et film</option>
			<option value="dcComicBd">DC comic BD</option>
			<option value="manga">Manga</option>
			<option value="figurine">Figurine</option>
			<option value="replique">Réplique</option>
		</select>
		<input type="text" name="textRecherche" placeholder="Recherche" class="texte">
		<input TYPE="submit" NAME="recherche" VALUE="GO" class="headerGO">
	</form>
</div>
<div class="herderConnect">
<?php
if($connect)
{
?>
	<form  method="post" action="<?php echo''.$action.''; ?>.php" class="formulaireMonCompte">
		<input TYPE="submit" NAME="Mon_compte" VALUE="Mon Compte" class="formulaire_aside">
		<input TYPE="submit" NAME="Deconnection" VALUE="Déconnection" class="formulaire_aside">
	</form>
	<?php
	if($Membre["droit"]=="admin")
	{
	?>
		<a href="Commande.php" class="lienAdministration"><p class="TextAdministration">Administration</p></a>
	<?php
	}
	?>
	<div class="herdeMonPanier">
		<a href="Panier.php">
			<img src="../Vue/image/image_header/panier.jpg">
		</a>
		<div class="herderTextMonPanier">
			<p class="hederMonpanierArticle"><?php echo''.$nbArticleAffichage.' article(s)'; ?></p>
			<p class="hederMonpanierPrix"><?php echo''.$prixPanierAffichage.'€'.$petitPrixPanierAffichage.''; ?></p>
		</div>
	</div>
<?php	
}
?>
</div>