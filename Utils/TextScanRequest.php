<?php

include_once 'D:\wamp64\www\basic\aliyuncs\aliyun-php-sdk-core\Config.php';

//检测评论内容是否包含不合法内容
function testConent($content){
    date_default_timezone_set("PRC");
   // $ak = parse_ini_file("aliyun.ak.ini");
// 请替换成您自己的accessKeyId、accessKeySecret
    $iClientProfile = DefaultProfile::getProfile("cn-shanghai", 'LTAIiY3klMjraJmZ', 'c0A5dWVuOgaNNmiw3Szp69wLY1dVMe'); // TODO
    DefaultProfile::addEndpoint("cn-shanghai", "cn-shanghai", "Green", "green.cn-shanghai.aliyuncs.com");
    $client = new DefaultAcsClient($iClientProfile);
    $request = new Green\Request\V20170825\TextScanRequest;
    $request->setMethod("POST");
    $request->setAcceptFormat("JSON");
    $task1 = array('dataId' =>  uniqid(),
        'content' => $content
    );
    $request->setContent(json_encode(array("tasks" => array($task1),
        "scenes" => array("antispam"))));
    try {
        $response = $client->getAcsResponse($request);
        //print_r($response);
        if(200 == $response->code){
            $taskResults = $response->data;
            foreach ($taskResults as $taskResult) {
                if(200 == $taskResult->code){
                    $sceneResults = $taskResult->results;
                    foreach ($sceneResults as $sceneResult) {
                        $scene = $sceneResult->scene;
                        $suggestion = $sceneResult->suggestion;
                        $label = $sceneResult->label;
                        //根据scene和suggetion做相关处理
                        //do something
                        //print_r($scene);
                        return array('suggestion'=>$suggestion,'label'=>$label);
                    }
                }else{
                    print_r("task process fail:" + $response->code);
                }
            }
        }else{
            print_r("detect not success. code:" + $response->code);
        }
    } catch (Exception $e) {
        print_r($e);
    }
}

