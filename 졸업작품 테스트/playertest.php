<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <?php
        $api_key = "AIzaSyDSPEOYbJITfVCSdWK8PSsCR3TBJbmy5Sk";
        $video_id = "UznGdNT5bPU";
        $url = "https://www.googleapis.com/youtube/v3/videos?id=" . $video_id . "&key=" . $api_key . "&part=snippet";
        $json = file_get_contents($url);
        $getData = json_decode( $json , true);
        foreach((array)$getData['items'] as $key => $gDat){
            $title = $gDat['snippet']['title'];
        }
        // Output title
        echo '<iframe type="text/html" allowtransparency="true" frameborder="0" width="60" height="60" src="https://www.youtube.com/embed/'.$video_id.'?autoplay=1&amp;color=white&amp;iv_load_policy=3&amp;controls=1&amp;rel=0&amp;fs=0&amp;autohide=1"></iframe>';
        echo '<a href="https://youtu.be/'.$video_id.'">'.$title.'
        </a>';

    ?>
</body>
</html>