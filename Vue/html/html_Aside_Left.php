<?php
if(!$connect)
{
	if(!$inscription)
	{
	?>
		<form method="post" action="<?php echo''.$action.''; ?>.php" class="menu_connection">
			<input TYPE="text" NAME="login" placeholder="login" class="formulaire_aside">
			<input TYPE="password" NAME="pass" placeholder="pass" class="formulaire_aside">
			<?php
			if($connectNoGood)
			{
			?>
				<p class="loginnofree">Login ou mot de passe incorrecte !</p>
			<?php
			}
			?>
				<input type="submit" name="connection" VALUE="Se connecter" class="formulaire_aside">
				<input type="submit" name="inscription" VALUE="Inscription" class="formulaire_aside">
				<div> 
					<a href="Panier.php"><img src="../Vue/image/image_aisde_left/panier.jpg"></a>
				</div>
				<a href="mailto:toto@hotmail.fr" class="contact"><p class="contact_para">Contact</p></a>
		</form>
			<?php
	}
	elseif($inscription)
	{
	?>
		<form method="post" action="<?php echo''.$action.''; ?>.php" class="menu_connection">
			<input TYPE="text" NAME="login" placeholder="login" class="formulaire_aside">
			<?php
			if($loginNoFree)
			{
			?>
				<p class="loginnofree">Pseudo déjà existant !</p>
			<?php
			}
			else if($loginNoGood)
			{
			?>
				<p class="loginnofree">
					Le pseudo doit commencer par une lettre.<br/>
					Le pseudo doit contunir seulement des caractères alphanumériques.<br/>
					Le taille doit être entre 3 à 30 caractères.
				</p>
			<?php
			}
			?>
			<input TYPE="password" NAME="pass" placeholder="pass" class="formulaire_aside">
			<?php
			if($passNoGood)
			{
			?>
				<p class="loginnofree">
				Le mot de passe doit contenir au moins une minuscule, un majuscule et un chiffre.<br/>
				Le taille doit être entre 6 à 30 caractères.
				</p>
				<?php
			}
			?>
			<input TYPE="text" NAME="mail" placeholder="email" class="formulaire_aside">
			<?php
			if($mailNoGood)
			{
			?>
				<p class="loginnofree">Adresse mail invalide !</p>
			<?php
			}
			?>
				<input type="submit" name="Inscrire" VALUE="S'inscrire" class="formulaire_aside">
				<input type="submit" name="connection" VALUE="Connection" class="formulaire_aside">
				<div> 
					<a href="Panier.php"><img src="../Vue/image/image_aisde_left/panier.jpg"></a>
				</div>
				<a href="mailto:toto@hotmail.fr" class="contact"><p class="contact_para">Contact</p></a>
		</form>
	<?php
	}
}
?>
	<form method="post" action="<?php echo''.$action.''; ?>.php" class="menu_connection">
		<label id="trie" class="lableTrie">Trier :</label>
		<select name="trierPar" class="formulaire_aside" id="trie">
			<option value="croissant">Prix croissant</option>
			<option  value="decroissant">Prix décroissant</option>
		</select>
	<input type="submit" name="trier" VALUE="Trier" class="formulaire_aside">
	</form>