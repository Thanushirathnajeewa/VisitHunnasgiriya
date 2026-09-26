<?php
require "config/db.php";
if(session_status()===PHP_SESSION_NONE)session_start();
if(!isset($_SESSION['plan']))$_SESSION['plan']=[];
if(isset($_GET['add'])){ $id=(int)$_GET['add']; if($id && !in_array($id,$_SESSION['plan']))$_SESSION['plan'][]=$id; header("Location: planner.php");exit;}
if(isset($_GET['remove'])){$_SESSION['plan']=array_values(array_diff($_SESSION['plan'],[(int)$_GET['remove']]));header("Location: planner.php");exit;}
$all=$pdo->query("SELECT a.*,c.category_name FROM attractions a JOIN categories c ON a.category_id=c.category_id ORDER BY a.distance_km")->fetchAll();
$selected=[]; if($_SESSION['plan']){ $ph=implode(',',array_fill(0,count($_SESSION['plan']),'?'));$s=$pdo->prepare("SELECT a.*,c.category_name FROM attractions a JOIN categories c ON a.category_id=c.category_id WHERE a.attraction_id IN ($ph)");$s->execute($_SESSION['plan']);$selected=$s->fetchAll();usort($selected,function($x,$y){return array_search($x['attraction_id'],$_SESSION['plan'])<=>array_search($y['attraction_id'],$_SESSION['plan']);});}
$pageTitle="Day Planner";include "includes/header.php";
?>
<section class="section container"><h1>One-Day Itinerary Planner</h1><p>Select attractions and arrange your visit order.</p>
<div class="grid"><div class="card"><h2>Your Plan (<?=count($selected)?>)</h2><?php if($selected):?><ol class="planner-list"><?php foreach($selected as $a):?><li><span><strong><?=htmlspecialchars($a['name'])?></strong><br><span class="small"><?=htmlspecialchars($a['estimated_visit_duration'])?> · <?=htmlspecialchars($a['travel_time'])?></span></span><a class="btn danger" href="planner.php?remove=<?=$a['attraction_id']?>">Remove</a></li><?php endforeach;?></ol><?php else:?><div class="notice">No places selected yet.</div><?php endif;?></div>
<div class="card"><h2>Add Places</h2><?php foreach($all as $a):?><div class="checkbox-item"><span style="flex:1"><?=htmlspecialchars($a['name'])?> <span class="meta">(<?=$a['distance_km']?> km)</span></span><?php if(in_array($a['attraction_id'],$_SESSION['plan'])):?><span class="tag">Added</span><?php else:?><a class="btn" href="planner.php?add=<?=$a['attraction_id']?>">Add</a><?php endif;?></div><?php endforeach;?></div></div>
</section>
<?php include "includes/footer.php"; ?>