<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Global Variables
$workingDir=getcwd();
$ffmpeg="ffmpeg/bin/ffmpeg";
$timeStamp="s".time();
$movieTrailerConvertedFile="mainConverted";
$adConvertedFile="adConverted";
$interval="";
$videoFormat=["mp4","avi","flv","mkv","mov","wmv"];
$sessionPath="";
$convertedPathMain="";
$convertedPathAd="";
$outputFile="";
$dummyDateTime="";

//Will be removing after merging the code
$outputFormat="";
$movieTrailerFileName="";
$adFileName="";

//
$format = 'Y-m-d H:i:s';
$date = DateTime::createFromFormat($format, '2016-04-18 12:58:20');
$totalAd=1;
$splitedTrailer= array(); 

function sort_by_date($a, $b) {
   $a = $a["dateObj"];
   $b = $b["dateObj"];
   return ($a < $b) ? -1 : 1;
}

function uploadVideos(){
 global $workingDir,$ffmpeg,$timeStamp,$movieTrailerConvertedFile,$adConvertedFile,$interval,$videoFormat,$sessionPath,$convertedPathMain,$convertedPathAd,$outputFile,$outputFormat,$movieTrailerFileName,$adFileName,$totalAd,$date,$splitedTrailer;
 
    $sessionPath=$workingDir."/Assets/".$timeStamp;
    exec("mkdir ".$sessionPath);
    //validation
   if((($_FILES["trailer"]["type"]!= "video/mp4") && ($_FILES["trailer"]["type"] != "video/avi") && ($_FILES["trailer"]["type"] != "video/x-flv") && 
     ($_FILES["trailer"]["type"] != "video/mkv") && ($_FILES["trailer"]["type"] != "video/mov") &&($_FILES["trailer"]["type"] != "video/wmv"))){
         echo "<script>window.location='index.php?error=2'</script>";
          return false;   
     }
     
    $outputFormat=$_POST['format'];
    
    $movieTrailerFileName="MovieTrailer.".pathinfo($_FILES['trailer']['name'], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES["trailer"]["tmp_name"], $sessionPath."/".$movieTrailerFileName);//Trailer
    $inputPathMain=$sessionPath."/".$movieTrailerFileName;
    
    //Duration of trailer
    exec($ffmpeg.' -i '.$inputPathMain.' 2>&1 | grep "Duration"', $op);     
    $mainVideoduration=explode(".",str_replace("Duration:","",explode(",",$op[0])[0]))[0];
    $mainVideoduration = str_replace(' ', '', $mainVideoduration);
    
    //validation 
    ///Creating dummyTime for trailer
    $trailerTime=clone $date;
    $Position=explode(":",$mainVideoduration);
    $trailerTime->add(new DateInterval("PT".$Position[0]."H".$Position[1]."M".$Position[2]."S")); 

    while ($_POST['position'.$totalAd]!="") {
       
        if((($_FILES["ad".$totalAd]["type"]!= "video/mp4") && ($_FILES["ad".$totalAd]["type"] != "video/avi") && ($_FILES["ad".$totalAd]["type"] != "video/x-flv") && 
        ($_FILES["ad".$totalAd]["type"] != "video/mkv") && ($_FILES["ad".$totalAd]["type"] != "video/mov") &&($_FILES["ad".$totalAd]["type"] != "video/wmv"))){
            echo "<script>window.location='index.php?error=3'</script>";
            return false;   
        }
        
        $eaDummyTime=clone $date;
        $eaPosition=explode(":",$_POST['position'.$totalAd]);
        $eaDummyTime->add(new DateInterval("PT".$eaPosition[0]."H".$eaPosition[1]."M".$eaPosition[2]."S")); 
        if($trailerTime<$eaDummyTime){
            echo "<script>window.location='index.php?error=1'</script>";
            return false; 
        }
        $totalAd++;
    }
    $totalAd=1;
        
    //Conversion    
    $convertedPathMain=$sessionPath."/".$movieTrailerConvertedFile.".".$outputFormat;
    $fileExtenstion=pathinfo($_FILES['trailer']['name'], PATHINFO_EXTENSION);
    if($fileExtenstion!=$outputFormat){
        exec($ffmpeg." -i ".$inputPathMain." ".$convertedPathMain);//Main video
    }else{
        $convertedPathMain=$inputPathMain;
    }
    
    $adArr = array();    
    while (file_exists($_FILES['ad'.$totalAd]['tmp_name'])==1) { //Ad upload
    
        $eaAdFileName="ad".$totalAd;
        $eaWatermarkAd="water".$totalAd;
        $eaDummyTime=clone $date;
        $eaPosition=explode(":",$_POST['position'.$totalAd]);
        $eaDummyTime->add(new DateInterval("PT".$eaPosition[0]."H".$eaPosition[1]."M".$eaPosition[2]."S")); 
        
        $adFileName=$eaAdFileName.".".pathinfo($_FILES[$eaAdFileName]['name'], PATHINFO_EXTENSION);        
        if(move_uploaded_file($_FILES[$eaAdFileName]["tmp_name"], $sessionPath ."/".$adFileName)){
            //conversion
            $inputPathAd=$sessionPath."/".$adFileName;
            
            $convertedPathAd=$sessionPath."/".$eaAdFileName.".".$outputFormat; 
            $convertedPathAdWithWater=$sessionPath."/".$eaWatermarkAd.".".$outputFormat;    
            $adExtension=pathinfo($_FILES[$eaAdFileName]['name'], PATHINFO_EXTENSION);  
           
            if($adExtension!=$outputFormat){
                exec($ffmpeg." -i ".$inputPathAd." ".$convertedPathAd);//Ad Video
                
            }else{
                $convertedPathAd=$inputPathAd;
            }   
                
            exec($ffmpeg.' -i '.$convertedPathAd.' -i Assets/Ad.png -filter_complex "overlay=10:10" '.$convertedPathAdWithWater);
            $convertedPathAd=$convertedPathAdWithWater;
          
            $eaObj["dateObj"]=$eaDummyTime;
            $eaObj["fileName"]=$eaWatermarkAd.".".$outputFormat;
            array_push($adArr,$eaObj);
        }
        
        $totalAd=$totalAd+1;
    }
    
    usort($adArr,'sort_by_date');
    
    for ($i = 0; $i < sizeof($adArr); $i++) {        
        $dteDiff = $adArr[$i]["dateObj"]->diff($date); 
        $adArr[$i]["dateObj"]= $dteDiff->format("%H:%I:%S"); 
    }
 
    
    $startTime ="00:00:00";
    $endAds=array();
    $partNo=1;
    for($j=0;$j<sizeof($adArr);$j++){
        
        //Skip for Ad with 00 and end minute
        if($adArr[$j]["dateObj"]==$startTime){
            array_push($splitedTrailer,$adArr[$j]["fileName"]);
        }else if($adArr[$j]["dateObj"]==$mainVideoduration){
            array_push($endAds,$adArr[$j]["fileName"]);
        }
        else{
            array_push($splitedTrailer,'part'.($partNo).".".$outputFormat);
            array_push($splitedTrailer,$adArr[$j]["fileName"]);        
            exec ($ffmpeg.' -i '.$convertedPathMain.' -ss '.$startTime.' -to '.$adArr[$j]["dateObj"].' '.$sessionPath.'/part'.($partNo).'.'.$outputFormat);
            $startTime =$adArr[$j]["dateObj"];    
            $partNo++;
        }  
}
    
    exec($ffmpeg.' -i '.$convertedPathMain.' -ss '.$startTime.' -to '.$mainVideoduration.' '.$sessionPath.'/part'.($partNo).'.'.$outputFormat);    
    array_push($splitedTrailer,'part'.($partNo).".".$outputFormat);
    
    //Pushing endArs
    $splitedTrailer=array_merge($splitedTrailer,$endAds);

    if($outputFormat=="mp4"){
        Mp4Creation();
    }else{
        otherFormat();
    }
      // delete all file
     $files = glob('Assets/'.$timeStamp.'/*'); // get all file names
    foreach($files as $file){ // iterate files
    if(is_file($file)){
            if($file!="Assets/".$timeStamp."/output.".$outputFormat ){
                  unlink($file); // delete file   
            }
        }
    }
    exec($ffmpeg. " -i  Assets/".$timeStamp."/output.".$outputFormat." -ss 00:00:01 -vframes 1 Assets/".$timeStamp."/output.png");
    echo "<script>window.location='index.php?id=".$timeStamp."&format=".$outputFormat."'</script>";
  
}

function Mp4Creation(){
    global $workingDir,$ffmpeg,$timeStamp,$movieTrailerConvertedFile,$adConvertedFile,$interval,$videoFormat,$sessionPath,$convertedPathMain,$convertedPathAd,$outputFile,$outputFormat,$movieTrailerFileName,$adFileName,$splitedTrailer;
    $outputFile=$sessionPath."/output.".$outputFormat;
    $concatCmd="";
    
   //Creating intermediates
    for($i=0;$i<sizeof($splitedTrailer);$i++){
       $intFilename=explode(".",$splitedTrailer[$i])[0].".ts";
        exec($ffmpeg." -i ".$sessionPath."/".$splitedTrailer[$i]." -c copy -bsf:v h264_mp4toannexb -f mpegts ".$sessionPath."/int".$intFilename);    
        $concatCmd=$concatCmd.$sessionPath."/int".$intFilename."|";
    }
    $concatCmd=rtrim($concatCmd,"|");
    exec($ffmpeg.' -i "concat:'.$concatCmd.'" -c copy -bsf:a aac_adtstoasc '.$outputFile);
   
}

function otherFormat(){
    
    global $workingDir,$ffmpeg,$timeStamp,$movieTrailerConvertedFile,$adConvertedFile,$interval,$videoFormat,$sessionPath,$convertedPathMain,$convertedPathAd,$outputFile,$outputFormat,$movieTrailerFileName,$adFileName,$splitedTrailer;
 
    //create input.txt file with file path
     $inputFile=$sessionPath."/input.txt";
     $concatCmd="";
    
     for($i=0;$i<sizeof($splitedTrailer);$i++){
        $concatCmd=$concatCmd."file '".$splitedTrailer[$i]."' \n";
      }
       exec("printf \"".$concatCmd."\" > ".$inputFile);
    //concat videos
    $outputFile=$sessionPath."/output.".$outputFormat;
    exec($ffmpeg." -f concat -i ".$inputFile." -c copy ".$outputFile);
       
}

uploadVideos();
    

?>
