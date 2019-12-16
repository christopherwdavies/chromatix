<?php


$message = $_POST['message'];
$title = $_POST['title'];
$submittedurl = $_POST['url'];
$topic = '/topics/notifications';
$server_key = 'AAAA8pXC1JE:APA91bGuYqU5SoODQ1gvKHdkUKPTxUzoRoO6gqZHawBD6povPs65Ug2xT7sMmj8r4fVqRkkW8Cg2juupUD93gBBW3qp5XCGgSf4XTU122JFtxv9KYAD05gCDhoW-tm_E8AzuWdTlC7a_';


    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            'to' => $topic,
            'notification' => array (
                    "body" => $message,
                    "title" => $title
            		),
            'data' => array (
	            "url" => $submittedurl
	            )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . $server_key,
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    echo $result . '<br';

    curl_close ( $ch );

    echo 'Title: ' . $title .'<br>';
    echo 'Message: ' .$message . '<br>';
    echo 'URL: ' . $submittedurl;





?>
