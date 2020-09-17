<?php
    header("Content-Type:text/html; charset=utf-8");

    echo "client URL library를 통해 post메세지를 다른 서버에 전송<br><br>";

    //다른 서버의 test.php에 보낼 데이터들 배열(연관배열)로 만듬
    $postData = array("name"=>"SAM", "msg"=>"Hello android"); //"키"=>"값"

    //curl 라이브러리 객체 생성(PHP에서 다른 서버에 요청하는 라이브러리)
    $ch = curl_init();    //initial 이니셜

    //curl에 설정하는 옵션들
    
    //1.요청할 서버 주소 URL
    curl_setopt($ch, CURLOPT_URL, "http://jeondh9971.dothome.co.kr/FCM/test.php"); 
     //option, 2파라 : CURLOPT_URL로 요청한다(옵션설정), URL 설정을 한다 다음 파라미터엔 URL을 적어준다. 
     //3파라 : 다른 서버(push서버)로 보낸다, 다른 서버 주소작성, 근데 다른 서버로 보내기 전에 내 서버의 다른 php문서로 보내고 연습먼저 한다

     //2.요청의 응답을 받겠다는 설정
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //3파라 : 요청받겠다 (true)

     //3. POST로 보낼 데이터들 설정
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  //2파라 : 포스트 방식, POSTFIELDS 값 데이터들, 3파라 : 연관배열 보낸다 FIELDS(필드들)SAM, Hello android 값 날아감

     //설정했으니 curl 작업 실행
     $result = curl_exec($ch);  //실행된 서버에서 응답한 결과(에코한 결과)가 리턴

     if($result == false){  // === : 자료형까지 비교연산한다
        echo "실패 : ".curl_error($ch)."<br>";
     }else{
        echo "성공 : ".$result."<br>";
     }

     curl_close($ch);


?>