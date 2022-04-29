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
        
        if(count($data)>0){
            //유저 테이블
            $useridname = "useridname";
            //가져온 정보값
            
            $x = $data['pos'][0]['x'];
            $y = $data['pos'][0]['y'];
            $item = $data['pos'][0]['item'];
            $zindex = $data['pos'][0]['zindex'];
            //유저 테이블 가져오기
            //$posLoad = mysqli_query($conn, "SELECT * FROM ".$useridname);
            //같은 위치의 같은 item이 있으면 삭제 안하고 놔둠
        
            //같은 위치가 없으면 삭제하고 새로 저장
            
            $posSave = mysqli_query($conn, "INSERT INTO useridname (itemid,x,y,zindex) VALUES('".$item."','".$x."','".$y."','".$zindex."')");
            $posLoad = mysqli_query($conn, "SELECT * FROM ".$useridname);
            $i=0;
             while($upl=mysqli_fetch_array($posLoad)){
                 //데이터 값 가져오기 - 로딩용!!
                 $data['pos'][$i]['x'] = $upl['x'];
                 $data['pos'][$i]['y'] = $upl['y'];
                 $data['pos'][$i]['item'] = $upl['itemid'];
                 $data['pos'][$i]['zindex'] = $upl['zindex'];
                 $i++;
             }
        }
    //$decoded['bar'] = "Hello World AGAIN!";    // Add some data to be returned.

    $reply = json_encode($data);
    header("Content-Type: application/json; charset=UTF-8");
    echo $reply;
    }
?>