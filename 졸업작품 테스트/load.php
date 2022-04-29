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
        
        if($dbLoad->num_rows > 0){
            //가져온 정보값
            $posLoad = mysqli_query($conn, "SELECT * FROM ".$useridname);
            $i=0;
             while($upl=mysqli_fetch_array($posLoad)){
               //이미지 주소 찾기
                $img = mysqli_query($conn, "SELECT * FROM images WHERE itemid='".$upl['itemid']."'");
                //아이템 가져오기
                $data['pos'][$i]['x'] = $upl['x'];
                if($upl['y'] == NULL) { $data['pos'][$i]['y'] = 'NULL'; } 
                else { $data['pos'][$i]['y'] = $upl['y']; };
                $data['pos'][$i]['item'] = $upl['itemid'];
               
               if(mysqli_num_rows($img)>0){
                //이미지 주소 존재 여부 확인
               while($imgsrc=mysqli_fetch_array($img)){
                  //$data['pos'][$i]['itemid']='ffff';
                  $base = base64_encode($imgsrc['file']);
                  $data['pos'][$i]['itemid'] = $base;
                }
               }//없으면 itemid 보내지 않음
               $data['pos'][$i]['zindex'] = $upl['zindex'];
                $i++;
             }
        }
    $reply = json_encode($data);
    header("Content-Type: application/json; charset=UTF-8");
    echo $reply;
    }
?>