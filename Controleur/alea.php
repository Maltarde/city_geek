<?php
$un=0;
$deux=0;
$trois=0;
$quatre=0;
$cinq=0;
$six=0;
$sept=0;
$huit=0;
$neuf=0;
$dix=0;
$alea=0;
$nbtest=1000000000;


for($i=1;$i<=$nbtest;$i++)
{	
	$alea = rand(1,10);

	if($alea==1)
	{
		$un++;
	}
	elseif($alea==2)
	{
		$deux++;
	}
	elseif($alea==3)
	{
		$trois++;
	}
	elseif($alea==4)
	{
		$quatre++;
	}
	elseif($alea==5)
	{
		$cinq++; 
	}
	elseif($alea==6)
	{
		$six++;
	}
	elseif($alea==7)
	{
		$sept++;
	}
	elseif($alea==8)
	{
		$huit++;
	}
	elseif($alea==9)
	{
		$neuf++;
	}
	elseif($alea==10)
	{
		$dix++;
	}
}

$Pun=($un/$nbtest)*100;
$Pdeux=($deux/$nbtest)*100;
$Ptrois=($trois/$nbtest)*100;
$Pquatre=($quatre/$nbtest)*100;
$Pcinq=($cinq/$nbtest)*100;
$Psix=($six/$nbtest)*100;
$Psept=($sept/$nbtest)*100;
$Phuit=($huit/$nbtest)*100;
$Pneuf=($neuf/$nbtest)*100;
$Pdix=($dix/$nbtest)*100;

echo'1: '.$Pun.' // '.$un.'<br>';
echo'1: '.$Pdeux.' // '.$deux.'<br>';
echo'1: '.$Ptrois.' // '.$trois.'<br>';
echo'1: '.$Pquatre.' // '.$quatre.'<br>';
echo'1: '.$Pcinq.' // '.$cinq.'<br>';
echo'1: '.$Psix.' // '.$six.'<br>';
echo'1: '.$Psept.' // '.$sept.'<br>';
echo'1: '.$Phuit.' // '.$huit.'<br>';
echo'1: '.$Pneuf.' // '.$neuf.'<br>';
echo'1: '.$Pdix.' // '.$dix.'<br>';
?>