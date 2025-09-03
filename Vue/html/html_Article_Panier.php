<div class="PanierContent">
	<table class="tableau">
		<tr class="tableauColonne">
		   <th colspan="2" class="designation">Désignation</th>
		   <th class="prix">Prix</th>
		   <th class="quantite">Quantité</th>
		   <th class="total">Total</th>
		</tr>
		<?php
		for($i=0;$i<=$panierNbArticle-1;$i++)
		{
		?>
			<tr class="tableauColonne">
			   <td><img src="../Vue/image/image_article_panier/1.png" class="PanierImage"></td>
			   <td class="designation"><?php echo''.$panirNom[$i].''; ?></td>
			   <td class="prix"><?php echo''.$panierPrix[$i].'€'.$panierPetitPrix[$i].''; ?></td>
			   <td class="quantite">
					<?php echo''.$PanierNombre[$i].''; ?> 
					<form method="post">
						<div>
							<input type="submit" name="moins<?php echo''.$i.'';?>" VALUE="-">
							<input type="submit" name="plus<?php echo''.$i.'';?>" VALUE="+">
						</div>
						<input type="submit" name="supprime<?php echo''.$i.'';?>" VALUE="Supprimer">
					</form>
			   </td>
			   <td class="total"><?php echo''.$panierPrixarticle[$i].'€'.$panierPrixarticlePetit[$i].'';?></td>
			</tr>
		<?php
		}
		?>
		<tr class="tableauColonne">
		   <th class="panierTotal"><?php echo'Total: '.$prixPanierAffichage.'€'.$petitPrixPanierAffichage.''; ?> </th>
		</tr>
	</table>

	<div class="panierCommender">
		<?php
		if($connect)
		{
		?>
			<form  method="post">
				<input type="submit" name="commande" VALUE="COMMANDER">
			</form>
		<?php
		}
		?>
	</div>
</div>