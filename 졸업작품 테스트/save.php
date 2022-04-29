<?php 
    //로그인
    $servername = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'test';
    $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
    
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    
    if ($contentType === "application/json") {
        $content = trim(file_get_contents("php://input"));
        $data = json_decode($content, true);
        
        //유저 테이블 찾기
        $useridname = $data["userId"];
        $dbLoad = mysqli_query($conn, "SHOW TABLES LIKE '".$useridname."'");
            //컬럼 삭제
            $posDel = mysqli_query($conn, "DELETE FROM ".$useridname);
            //가져온 정보값
            foreach($data['pos'] as $value){
              $x = $value['x'];
              $y = $value['y'];
              $item = $value['item'];
              $zindex = $value['zindex'];
              if($value['y']==NULL){
                $posSave = mysqli_query($conn, "INSERT INTO useridname (itemid,x,zindex) VALUES('".$item."','".$x."','".$zindex."')");
              }else{
                $posSave = mysqli_query($conn, "INSERT INTO useridname (itemid,x,y,zindex) VALUES('".$item."','".$x."','".$y."','".$zindex."')");
              }
            }
    $reply = json_encode($data);
    header("Content-Type: application/json; charset=UTF-8");
    echo $reply;
    }
?>