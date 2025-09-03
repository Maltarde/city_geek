<div class="contentMembre">
	<p class="titleMembre">Liste des Administrateur</p>
	<table class="tableMembre">
		<tr class="lineMembre">
			<th class="idMembre">ID</th>
			<th class="pseudoMembre">Pseudo</th>
			<th class="mailMembre">Mail</th>
			<th class="commandMembre">Commande</th>
			<th class="articleMembre">Article</th>
			<th class="buyMembre">Total achat</th>
			<th class="dateMembre">Date</th>
		</tr>
		<?php
		for($i=0;$i<=$nbLembreAdmin-1;$i++)
		{
		?>
		<tr class="lineMembre">
			<th class="idMembre"><?php echo''.$idAdmin[$i].'';?></th>
			<th class="pseudoMembre"><?php echo''.$pesudoAdmin[$i].'';?></th>
			<th class="mailMembre"><?php echo''.$mailAdmin[$i].'';?></th>
			<th class="commandMembre"><?php echo''.$commandeAdmin[$i].'';?></th>
			<th class="articleMembre"><?php echo''.$articleMembreAdmin[$i].'';?></th>
			<th class="buyMembre"><?php echo''.$totalAchatAdmin[$i].'€'.$totalAchatpetitAdmin[$i].'';?></th>
			<th class="dateMembre"><?php echo''.$dateAdmin[$i].'';?></th>
		</tr>
		<?php
		}
		?>
	</table>

	<p class="titleMembre">Liste des membres</p>
	<table class="tableMembre">
		<tr class="lineMembre">
			<th class="idMembre">ID</th>
			<th class="pseudoMembre">Pseudo</th>
			<th class="mailMembre">Mail</th>
			<th class="commandMembre">Commande</th>
			<th class="articleMembre">Article</th>
			<th class="buyMembre">Total achat</th>
			<th class="dateMembre">Date</th>
		</tr>
		<?php
		for($i=0;$i<=$nbmembre-1;$i++)
		{
		?>
		<tr class="lineMembre">
			<th class="idMembre"><?php echo''.$id[$i].'';?></th>
			<th class="pseudoMembre"><?php echo''.$pesudo[$i].'';?></th>
			<th class="mailMembre"><?php echo''.$mail[$i].'';?></th>
			<th class="commandMembre"><?php echo''.$commande[$i].'';?></th>
			<th class="articleMembre"><?php echo''.$articleMembre[$i].'';?></th>
			<th class="buyMembre"><?php echo''.$totalAchat[$i].'€'.$totalAchatpetit[$i].'';?></th>
			<th class="dateMembre"><?php echo''.$date[$i].'';?></th>
		</tr>
		<?php
		}
		?>
	</table>
</div>