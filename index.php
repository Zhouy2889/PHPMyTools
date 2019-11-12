<?php

    $data = '{
  "resultCode" : "200",
  "resultMsg" : "拒絕訪問，ip:【119.147.47.51】不在白名单内",
  "success" : false,
  "data" : null
} ';
     $re = json_decode($data,1);

     var_dump( $re);



if($re['success']=='false'){
    echo "true";
}

if($re['success']==false){
    echo "true1";
}
echo "<br/>";
echo $re;

?>