<div class="stockContent">
	<table class="tableStock">
		<tr class="lineStock">
		   <th class="idStock">ID</th>
		   <th class="nameStock">Nom</th>
		   <th class="priceStock">Prix</th>
		   <th class="buyStock">Acheter</th>
		   <th class="sotckStock">En Stock</th>
		   <th class="soldeStock">Vendu</th>
		   <th class="TotalbuyStock">Total vente</th>
		   <th class="actionStock">Action</th>
		</tr>
		
		<?php
		for($i=0;$i<=$nbArticle-1;$i++)
		{
		?>
		<tr class="lineStock">
		   <th class="idStock"><?php echo dechex($i+1);?></th>
		   <th class="nameStock"><?php echo''.$nom[$i].'';?></th>
		   <th class="priceStock"><?php echo''.$prix[$i].'€'.$prixsecond[$i].'';?></th>
		   <th class="buyStock"><?php echo''.$total_achete[$i].'';?></th>
		   <th class="sotckStock"><?php echo''.$stock[$i].'';?></th>
		   <th class="soldeStock"><?php echo''.$total_vente[$i].'';?></th>
		   <th class="TotalbuyStock"><?php echo''.$prixVenteTotal[$i].'€'.$prixSecondxVenteTotal[$i].'';?></th>
		   <th class="actionStock">
			<form action="GestionStock.php" method="post">
				<input type="text" name="Ajouter_<?php echo''.$i.'';?>" class="nomberAricleStock">
				<input type="submit" name="Ajouter" VALUE="Ajouter" class="boutonAricleStock">
			</form>
			<form  action="GestionStock.php" method="post">
				<input type="text" name="Enlever_<?php echo''.$i.'';?>" class="nomberAricleStock">
				<input type="submit" name="Enlever" VALUE="Enlever" class="boutonAricleStock">
			</form>
		   </th>
		</tr>
		<?php
		}
		?>
	</table>
</div>