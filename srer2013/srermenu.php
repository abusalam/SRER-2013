<div style="position:absolute; left:50%; margin-left:-405px; height: 40px; width:908px;">
    <ul>
    <li class="<?php echo ($_SERVER['PHP_SELF']=='/srer2013/dataentry.php')?'SelMenuitems':'Menuitems';?>">
							<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/srer2013/dataentry.php">Data Entry</a></li>
    <li class="Menuitems">|</li>
    <li class="<?php echo ($_SERVER['PHP_SELF']=='/srer2013/reports.php')?'SelMenuitems':'Menuitems';?>">
							<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>/srer2013/reports.php">Reports</a></li>
<?php if($_SERVER['PHP_SELF']!='/srer2013/reports.php'){ ?>
	<li class="Menuitems">|</li>
		<li class="<?php echo($_SERVER['PHP_SELF']=='')?'SelMenuitems':'Menuitems';?>"> <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?LogOut=1">Log Out!</a></li>
<?php }?>
    </ul>
 </div>
<span style="float:right;color:#996600;" class="SelMenuitems"><?php echo isset($_SESSION['UserName'])?$_SESSION['UserName']:"";?></span>
