



<?php  



	//Make arrays of news of each site and then combine then into one arry with merge
	$news_url = 'https://newsapi.org/v1/articles?source=abc-news-au&sortBy=top&apiKey=bcd9846aaac9422aa3a58dcbbed76b90';
	$news_json=file_get_contents($news_url);
	$news_array= json_decode($news_json,true);
	
	
	$news2_url = 'https://newsapi.org/v1/articles?source=bbc-news&sortBy=top&apiKey=bcd9846aaac9422aa3a58dcbbed76b90';
	$news2_json=file_get_contents($news2_url);
	$news2_array= json_decode($news2_json,true);
	#$lat=$maps_array['results'][0]['newssearch']['search']['lat'];
	#$lng= $maps_array['results'][0]['newssearch']['location']['lng'];
	$news3_url = 'https://newsapi.org/v1/articles?source=bbc-sport&sortBy=top&apiKey=bcd9846aaac9422aa3a58dcbbed76b90';
	$news3_json=file_get_contents($news3_url);
	$news3_array= json_decode($news3_json,true);
	
	$news4_url = 'https://newsapi.org/v1/articles?source=daily-mail&sortBy=top&apiKey=bcd9846aaac9422aa3a58dcbbed76b90';
	$news4_json=file_get_contents($news4_url);
	$news4_array= json_decode($news4_json,true);
	
	
	//New york times
//	$nyt_url='https://api.nytimes.com/svc/topstories/v2/home.json?api-key=bb2d5d16e7a446e59f0a11d0a6137a5f';
//	$nyt_json=file_get_contents($nyt_url);
//	$nyt_array= json_decode($nyt_json,true);
	 //nyt sports
	$nytsp_url='https://api.nytimes.com/svc/topstories/v2/sports.json?api-key=bb2d5d16e7a446e59f0a11d0a6137a5f';
	@$nytsp_json=file_get_contents($nytsp_url);
	$nytsp_array= json_decode($nytsp_json,true);
	//nyt books
	$nytbooks_url='https://api.nytimes.com/svc/topstories/v2/books.json?api-key=bb2d5d16e7a446e59f0a11d0a6137a5f';
	@$nytbooks_json=file_get_contents($nytbooks_url);
	$nytbooks_array= json_decode($nytbooks_json,true);
	
	$nytworld_url='https://api.nytimes.com/svc/topstories/v2/world.json?api-key=bb2d5d16e7a446e59f0a11d0a6137a5f';
	@$nytworld_json=file_get_contents($nytworld_url);
	$nytworld_array= json_decode($nytworld_json,true);
	
	$nytbu_url='https://api.nytimes.com/svc/topstories/v2/science.json?api-key=bb2d5d16e7a446e59f0a11d0a6137a5f';
	@$nytbu_json=file_get_contents($nytbu_url);
	$nytbu_array= json_decode($nytbu_json,true);
	
	
	@$nyt_array=array_merge_recursive($nytworld_array,$nytsp_array,$nytbooks_array,$nytbu_array);
	//make nyt like news api
	$nyt_array['articles']=$nyt_array['results'];
	if($nyt_array['articles']){
	foreach($nyt_array['articles'] as $articles){
		
		$articles['description']=$articles['abstract'];
		$articles['publishedAt']=$articles['published_date'];
		
	}
	}
	
	//nyt categories array
	
	
	//echo "<pre>";
	//print_r($nyt_array);
	//echo "</pre>";
	
	

			
	//ptosthetw

	
	//make an array of all the categories af news api 
	$cat_newsapi_url='https://newsapi.org/v1/sources?language=en';
	$cat_newsapi_json=file_get_contents($cat_newsapi_url);
	$cat_newsapi_array=json_decode($cat_newsapi_json,true);
	$source_array=array();
	//first i made an array of sources of the sites
	//prosthetw
	 array_push($source_array,$news_array['source']);	
	 array_push($source_array,$news2_array['source']);
	 array_push($source_array,$news3_array['source']);
	 array_push($source_array,$news4_array['source']);
	
	
	//make an array of categories
	$categories=array();
	foreach($cat_newsapi_array['sources'] as $source){
		if(in_array($source['id'],$source_array)){
			if(!in_array($source['category'],$categories)){
			array_push($categories,$source['category']);
			}
		}
		
	}
	
	//nyt categories array
	if($nyt_array['articles']){
	foreach($nyt_array['articles'] as $articles){
		$cat=strtolower($articles['section']);
		if(!in_array($cat,$categories)){
		array_push($categories,$cat);
	}
	}
	}
	
	#shuffle($allnews_array['articles']);
	#print_r($allnews_array);
	$site_array=array("ABC News","BBC News","BBC Sports","Daily Mail");

?>





<html>
	<link rel="stylesheet" href="stylenews.css" type="text/css">
<head> 
<title>
News Search
</title>

</head>
<body>
<form action="">
	<div>
Search news
<br>Insert Keywords   <input type="text" name="search"></br>
<br>Select category   <select name="cats">
	<option value="All">All</option>
	<?php
	foreach($categories as $cat){ 
echo "<option value='".$cat."' >".$cat."</option>";
	}
	?>
	</select></br>
<br>Select Website   <select name="site">
		<option value="All">All</options>
			<?php 
				$i=0;
			foreach($source_array as $source ){
			
				echo "<option value='".$source."' >".$site_array[$i]."</option>";
				$i++;
			}
			?>
				<option value="nytimes">New York Times</options>
				<option value="guardian">Guardian</options>
			</select></br>
	<br>	From:
    <input type="date" name="dateFrom" value="<?php echo date('Y-m-d'); ?>" />
    <br/>
  <br>  To:
    <input type="date" name="dateTo" value="<?php echo date('Y-m-d'); ?>" /><br>
<br><button type="submit" name="submit">Submit</button></br>
</div>
</form>
<?php

$allnews_array=null;
$allnews_array=array();
@$word=strtolower($_GET['search']);
$wordArray=explode(' ', $word);

@$cat_selected=$_GET['cats'];
@$site=$_GET['site'];
@$fromdate=$_GET['dateFrom'];
@$todate=$_GET['dateTo'];

//choose which sites reptesent the categories

//guardian array
$gua_cat="&section=".$cat_selected;
if($gua_cat=="&section=All"){
	$gua_cat="";
}

$word1="&q=".rawurlencode($word);
if(empty($_GET['search'])){
	$word1="";
}


$gua_url='http://content.guardianapis.com/search?from-date='.$fromdate.'&to-date='.$todate.''.$gua_cat.''.$word1.'&api-key=6f32f090-0a5b-4bb1-9e9e-421bfd8eaa44';
$gua_json=@file_get_contents($gua_url);
	$gua_array= json_decode($gua_json,true);

$gua_array=$gy=$gua_array['response'];
$gua_array['articles']=$gua_array['results'];



if($site=="All"||$site=="guardian"){
$allnews_array=$gua_array;
}

$sour_array=array();
	if(@$_GET['cats']=="All"){	
		if($_GET['site']=="All"){
		$allnews_array=array_merge_recursive($news_array,$news2_array,$news3_array,$news4_array,$nyt_array,$gua_array);
	//	echo"hello";
		}else{
		if($site==$source_array[0]){
				$allnews_array=array_merge_recursive($allnews_array,$news_array);
		}
		if($site==$source_array[1]){
				$allnews_array=array_merge_recursive($allnews_array,$news2_array);
		}
		if($site==$source_array[2]){
				$allnews_array=array_merge_recursive($allnews_array,$news3_array);
		}
		if($site==$source_array[3]){
				$allnews_array=array_merge_recursive($allnews_array,$news4_array);
		}
		if($site=="nytimes"){
			$allnews_array=array_merge_recursive($allnews_array,$nyt_array);
		}
		}

}elseif(!empty($cat_selected)){

		
			//	echo $cat_selected;
foreach($cat_newsapi_array['sources'] as $sour ){
	if(in_array($sour['id'],$source_array)){
	if($sour['category']==$cat_selected){
		array_push($sour_array,$sour['id']);
		
	}
	}	
}
if($site==$source_array[0]||$site=="All"){
if(in_array($news_array['source'],$sour_array)){
		$allnews_array=array_merge_recursive($allnews_array,$news_array);
	
}
}
if($site==$source_array[1]||$site=="All"){
if(in_array($news2_array['source'],$sour_array)){
	
$allnews_array=array_merge_recursive($allnews_array,$news2_array);
}
}
if($site==$source_array[2]||$site=="All"){
if(in_array($news3_array['source'],$sour_array)){

	$allnews_array=array_merge_recursive($allnews_array,$news3_array);
	}
}
if($site==$source_array[3]||$site=="All"){
if(in_array($news4_array['source'],$sour_array)){

	$allnews_array=array_merge_recursive($allnews_array,$news4_array);
	}
}
foreach($nyt_array['articles'] as $k=>$articles){
	if((strtolower($articles['section'])!=$cat_selected)){
		//echo $k;
		unset($nyt_array['articles'][$k]);
	
//			$allnews_array=array_merge_recursive($allnews_array,$articles);
	}
	
}
if($site=="nytimes"||$site=="All"){
$allnews_array=array_merge_recursive($allnews_array,$nyt_array);
}
}
//echo "<pre>";
//print_r($allnews_array);
//echo "</pre>";
$firstkey=100;
$lastkey=100;

	if(!empty($allnews_array)){
		foreach($wordArray as $word){
	  foreach(@$allnews_array['articles'] as $news){
		@$description=$news['description'];
		@$descriptionl=strtolower($news['description']);
		@$abstract=$news['abstract'];
		@$abstractl=strtolower($news['abstract']);
		@$titlel=strtolower($news['title']);
		@$webtitlel=strtolower($news['webTitle']);
		
		
		
		if(@strpos($descriptionl, $word)|| @strpos($abstractl, $word)|| @strpos($titlel, $word)|| @strpos($webtitlel, $word)){
			#echo "</br><a href='".$news['url']."'>".$news['title']."</a><br>";
			#echo  "</br>".$news['publishedAt'];
			#echo "<br>".$description."</br>";
		#echo $key;
		if(@in_array($news,@$list)){
			foreach($list as $k => $array){
				if($array==$news){
					unset($list[$k]);
					$lastkey=$lastkey+1;
					$list[$lastkey]=$array;
				}
			}
			
		}else{
			$firstkey=$firstkey-1;
			$list[$firstkey]=$news;
		}
		
			
			
		}
		}
		
	}
	if(empty($_GET['search'])){
		
		
		$list=$allnews_array['articles'];
		
	}
	
	#print_r($list);
	@ksort($list);
	#print_r($list);
	$list=@array_reverse($list);
	
//	if(!$cat_selected=="All"){
		
	//	foreach($list as $news){
	//		$id=$news['id']
			
			
	//	}
		
//	}
	if(!empty($list)){
	

		foreach(@$list as $news){
				echo "<div class='res'>";
			echo "<h3><a href='".@$news['url']."'>".@$news['title']."</a></h3>";
			if(@$news['publishedAt']!=null && @$news['description']!=null){
			echo  "(".@str_replace(["T","Z"]," ",$news['publishedAt']).")";
			echo "<br>".@$news['description']."</br>";
			}elseif(@$news['published_date']!=null && @$news['abstract']!=null){
			echo  "(".@str_replace("T"," ",$news['published_date']).")";
			echo "<br>".@$news['abstract']."</br>";
			
			}else{
					echo "<h3><a href='".$news['webUrl']."'>".$news['webTitle']."</a></h3>"."(".@str_replace(["T","Z"]," ",$news['webPublicationDate']).")";
				echo "</br>Latest US news, world news, sports, business, opinion, analysis and reviews from the Guardian, the world's leading liberal voice<br>";
			//echo "<br>".@$news['abstract']."</br>";
			}
			echo "</div>";
		}
		
		#print_r($list);
		#echo "".$firstkey."   ".$lastkey."";
	}else{
		echo "No results found";
	}
	

}


?>





</body>
</html>



