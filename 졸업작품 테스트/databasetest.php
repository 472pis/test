<!DOCTYPE html>
<?php
$servername = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
$table = 'useridname';//$_GET['name'];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script  src="http://code.jquery.com/jquery-latest.min.js"></script>
     <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <title>Document</title>
</head>
<style>
    html, body{
        width:100%;
        height:100%;
        margin: 0;
        text-align: center;
        position: relative;
        
    }  
    .character{
        margin: 0;
        width:100px;
        height:107px;
        position:absolute;
        bottom: 0;
        left:0;
        transition: transform 0.2s;
        z-index: 10000000;
    }
    
    .character img{
        width:100px;
        -webkit-user-drag:none;
    }
    .container{
        width:500px;
        height:300px;
        background-color: beige;
        border:1px solid black;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        display: inline-block;
    }
    .flipped{
        transform: scale(-1,1);
    }
    .items img, .itemsy img{
        width:50px;
        -webkit-user-drag:none;
    }
    .items, .itemsy{
        position:absolute;
        width:50px;
    }
    .items{
        bottom : 0;
    }
    .face{
        position:fixed;
        width:100%;
        height:100%;
        background: rgba(0,0,0,0.8);
        z-index: 100000000;
        text-align: center;
        display: none;
    }
    .face .face{
        position: absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%,0%);
    }
    .face .error{
        width:300px;
        height: 150px;
        position: absolute;
        top: 90px;
        left: 50%;
        transform: translate(-50%,0%);
    }
</style>
<body>
  <div class="face"><img class="faceimg" src="face1.gif" alt=""><img class="error" src="error.jpg" alt=""></div>
   <div class = 'container'>
   <button class="bt">don't touch this button</button>
     <script>
    $(".bt").click(function(){
        $(".face").css({'display':'block'});
        setTimeout(function(){
        $(".faceimg").attr("src","face2.gif");
        },10000);
    })
    $(".face").click(function(){
        $(".face").css({'display':'none'});
    })
        
    
    </script>
   <!--<div class = 'items'><img class = "a" src="item.png" alt=""></div>
   <div class = 'itemsy'><img class = "a" src="item.png" alt=""></div>-->
   <?php 
        //table load
        $tableLoad = mysqli_query($conn, "SELECT * FROM ".$table);
       while($upl=mysqli_fetch_array($tableLoad)){
            $img=mysqli_query($conn, "SELECT * FROM images WHERE itemid='".$upl['itemid']."'");  
            if(mysqli_num_rows($img) > 0){
                while($imgsrc=mysqli_fetch_array($img)){
                    if ($upl['y'] == NULL){
                        echo '<div class = "items" style="z-index:'.$upl['zindex'].' ; left:'.$upl['x'].'px;"><img class="'.$upl['itemid'].'"  src="data:image/jpeg;base64,'.base64_encode( $imgsrc['file'] ).'"/></div>';
                    }else{
                        echo '<div class = "itemsy" style="z-index:'.$upl['zindex'].' ; left:'.$upl['x'].'px; top:'.$upl['y'].'px;"><img class="'.$upl['itemid'].'"  src="data:image/jpeg;base64,'.base64_encode( $imgsrc['file'] ).'"/></div>';
                    } 
                }
            }else{
            //만약 img목록에 없는 파일명일 경우 text로 출력함
                echo 'image not load';
            }
        }
    ?>
   <div class='character'><img src="wifiwalk.gif" alt=""></div>
   </div>
<script>
    //저장 버튼 누르면 - sql에 저장하기
    //z-index / x / y
    
    var zIndex = 10;
    $('.itemsy').draggable({containment:'parent'}).mousedown(function(){
        $(this).css('z-index', zIndex);
        zIndex++;
    });
    
    //캐릭터도 드래거블하게 하려면 어떻게 할까
    //캐릭터 위에 마우스 다운 시 애니메이팅 함수 종료시켜야되겠지
    //놓으면 다시 시작하고
    //흐으으음...
   // $('.character').draggable({containment:'parent'});
    
    $('.items').draggable({
        containment:'parent',
        axis:"x"
    }).mousedown(function(){
        $(this).css('z-index', zIndex);
        zIndex++;
    })
    
    //div 넓이/높이 구하기
    var containerSizeW = $('.container').width();
    var containerSizeH = $('.container').height();
    var characterSizeW = $('.character').width();
    
    //최소값 지정 난수 - (Math.random()*max-min+1)+min
    //난수 생성 (움직임 여부/좌표 위치)
    function getNewrand(){
        var moveRand = Math.floor(Math.random()*2);
        var posRand = Math.floor(Math.random()*(containerSizeW-characterSizeW));
        return [moveRand,posRand];
    }
    
    //애니메이팅 함수
    function animateRand(character){
        //난수 구하고
        var rand = getNewrand();
        //움직임여부 오케이이면
        if(rand[0]==0){
            //캐릭터 이미지 걷는걸로 바꾸고
            $(character).children().attr("src", "wifiwalk.gif");
            //현재거리 - 도착 좌표 한 후 절대값 처리 =거리
            var p = Math.abs($(character).position().left-rand[1])*20;
            //현재위치보다 도착좌표가 크면 뒤집고, 작으면 원래대로
            if($(character).position().left<rand[1]){
                $(character).removeClass('flipped');
            }else{
                $(character).addClass('flipped');
            }
            //도착 좌표로 애나메이팅, 걸리는 시간은 거리*20으로 일정하게 유지함
            //애니메이팅이 끝나면 애니메이팅 함수 재실행(재귀)
            $(character).animate({left:rand[1]},p,function(){animateRand(character);});
        }else{
            //움직임여부 노노이면
            //멈춤 이미지로 바꾸고
            $(character).children().attr("src", "wifiwalk2.gif");
            //0~2000ms까지 랜덤한 시간동안 대기
            setTimeout(function(){animateRand(character);},Math.floor(Math.random()*2000));
        }
        
    }
    
    //로딩완료 후 캐릭터 중앙으로 이동
    $(document).ready(function(){
        $('.character').animate({left:(containerSizeW/2)},0,function(){animateRand('.character');});
    });
    
    
    
</script>
</body>
</html>