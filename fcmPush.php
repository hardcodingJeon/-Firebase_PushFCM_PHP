<?php
    //이 php파일이 push서버에 데이터를 보내줄 third 서버, 디바이스의 토큰키 , 전송할 데이터값, Fcm서버키 3개 필요
    //안드로이드에서 바로 push서버로 데이터 전송이 불가함(본인 안드로이드 토큰 알려주는 데이터 전송이 불가), 가운데 중계소 역할의 Thirdserver를 거처야함
    //B 폰에서 webserver(Third server)로 보내야 하지만 시간상 html에서 Third server로 보낸후 Third server에서 push server로 보내는 코드로 한다.
    //push서버에 데이터 보낼때 json으로 보내야함

    header("Content-Type:text/html; charset=utf-8");

    echo "FCM push서버에 메세지를 전송합니다.";

    //FCM 서버에 보낼 데이터는 크게 2종류
    //1. 메세지를 받을 디바이스의 고유 토큰값들 배열(원래 이 값들은 DB에 있어야함)
    //2. 보낼 데이터

    //안드로이드에서 instanceId()로 얻은 토큰값
    //보낼 토큰들(원래는 DB에 저장시켜야하는데 안다고 가정하고 그냥 안드로이드에서 복붙), 배열로 생성
    // $tokens = array(
    //     'fZn9qKrbRvKax0QcCILpmJ:APA91bGhsM1rsP-IVi04oLzK7T9lk0xivPv1-eYmEZUtRQvaP21-Q8dtSRkJzwrz6YsapFEFUAmk7bp9lRh-EjftIYJPnPDclOe23SOsTK9ocz9-N0vTM9809RV7aVcPfifep5k8f7Oc'
    // );
    $tokens = array(
        'f2IW0CSkSwCCOjsIqL-WEi:APA91bFiT2C0HP9pHPXVPVFTO-aHi08yV9xhUQCnu73cs-wsCIRF_uD58jCw7zKLXsGOD8abG3i0h09VZGQMQ-0R6OZJs0S9TBdccREfbBef4n_69Vne8M_BhhjQPFumjCPTHhdvljDo'
    );


    //보낼 메세지
    $name = $_POST["name"];
    $msg = $_POST["msg"];

    $data = array("name"=>$name, "msg"=>$msg);  //연관배열

    //FCM 서버에 보낼 데이터 2종류를 다시 하나의 연관배열로
    $postData = array(
        "registration_ids"=> $tokens,
        "data"=>$data
    );  //registration_ids 정해져있는 키, 토큰과 데이터를 배열로 묶었음
    
    //FCM서버는 본인에게 보낼 데이터를 json으로 보내도록 되어 있음.
    //연관배열 -> json
    $postDataJson = json_encode($postData);

    //위 데이터를 FCM에 보내려면
    //FCM서버에 접속하는 접속 서버 key가 필요함
    //서버키를 Body에 보내는 것이 아니라 Header정보로 보낸다.
    //FCM서버로 요청할 때 헤더정보 설정 - 배열로 (header : 서버키, json형식이라는 표시) (body : 데이터, 토큰)
    //1.웹 서버키 : FCM에 접속할 수 있는 권한키 (프로젝트 콘솔에서 확인)
    //2.내가 보내는 데이터가 json형식이라고 표시

    $serverKey = "AAAAN_Xky5M:APA91bHqSVTrG66enRVW-O3kINbQpCng3j6MYWvdp3sbZoHNee1cJpoOhWRURmHqgae_k-SbE6AqAfacCb4tWsYd34Lp_FYYgwTNmXxUx5hIl09shePxtEswkSCoI4lEXauzDVD3fVux";
    $headers = array(
        "Authorization:key=" . $serverKey,
        "Content-Type:application/json"
    );  //Authorization:key= :정해져있는값 , 연관배열 아닌 인덱스배열

    //curl을 통해 전송작업

    //CURL 초기화
    $ch = curl_init();

    //옵션들 설정
    //1)요청 URL
    curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");//https://fcm.googleapis.com/fcm/send : firebase의 푸시 서버 주소
    // https://fcm.googleapis.com/v1/projects/ex86firebasecloudemessage/messages:send

    //2)요청 결과가 잘됫는지 확인, 결과 돌려 받기
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //3파라 : 돌려 받겠다 true, 
    //실패 하면 false 리턴됨

    //3)위에서 설정 했던 Header정보 설정
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //4)POST메소드로 보낼 json데이터를 설정
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);

    //실행
    $result = curl_exec($ch);
    if($result === false){
        echo "실패 : " . curl_error($ch);
    }

    //close
    curl_close($ch);
?>