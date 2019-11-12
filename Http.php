<?php
use Log;
class Http
{
    /**
     * @param $url
     * @param $data
     * @param string $proxy
     * @param int $follow
     * @param int $conTimeOut
     * @param int $timeout
     * @return mixed
     */
    public static function post($url, $data, $proxy = '', $follow = 0, $conTimeOut = 3, $timeout = 10)
    {
        $sendData = "";
        foreach ($data as $k => $v) {
            $sendData .= $k . "=" . $v . "&";
        }
        $sendData = rtrim($sendData, '&');
        Log::info("[post]curl -d '{$sendData}' {$url}");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conTimeOut);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow);
        if ($proxy != '') {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        $response = curl_exec($ch);
        Log::info("http info:", curl_getinfo($ch));
        curl_close($ch);
        Log::info("http response:{$response}");
        return $response;
    }
    /**
     * @param $url
     * @param $data
     * @param string $proxy
     * @param int $follow
     * @param int $conTimeOut
     * @param int $timeout
     * @return mixed
     */
    public static function get($url, $data, $proxy = '', $follow = 0, $conTimeOut = 3, $timeout = 10)
    {
        $sendData = "";
        foreach ($data as $k => $v) {
            $sendData .= $k . "=" . $v . "&";
        }
        $sendData = rtrim($sendData, '&');
        $url .= '?' . $sendData;
        Log::info("[get] curl $url");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conTimeOut);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow);
        if ($proxy != '') {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        $response = curl_exec($ch);
        Log::info("http info:", curl_getinfo($ch));
        curl_close($ch);
        Log::info("http response:{$response}");
        return $response;
    }
    /**
     * @param $url
     * @param $data
     * @param $method
     * @param string $proxy
     * @param array $header
     * * @param int $conTimeOut
     * @param int $timeout
     * @return array
     */
    public static function curl($url, $data,  $proxy = '',$method='post', $header = [], $conTimeOut = 3, $timeout = 10)
    {
        Log::info("[curl]http request data:" . json_encode($data) . ", url:{$url}, method:{$method}, proxy:{$proxy}");
        $data = http_build_query($data);
        $ch = curl_init();
        if (strtolower($method)=== 'get') {
            $url .= '?' . $data;
        }else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conTimeOut);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($proxy != '') {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        if (count($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $response = curl_exec($ch);
        if ($response === false) {
            Log::info("http error:" . curl_error($ch));
        }
        $info = curl_getinfo($ch);
        Log::info("http info:", $info);
        curl_close($ch);
        Log::info("http response:{$response}");
        $result = ['response' => $response, 'info' => $info];
        return $result;
    }
    /**
     * @param $url
     * @return mixed
     * 通过url创建跳转html
     * url必须是url_encode后的
     */
    public static function createHtml($url)
    {
        $template = ' 
        <!DOCTYPE html>
        <html>
             <head> 
              <meta charset="UTF-8" /> 
              <title>Redirecting to {url}</title> 
             </head> 
             <body>
                <h2>正在跳转...</h2> 
                <script>window.location.href="{url}";</script> 
             </body>
        </html>
        ';
        $html = str_replace("{url}", $url, $template);
        return $html;
    }
    public static function createImgHtml($url)
    {
        $template = ' 
        <!DOCTYPE html>
        <html>
             <head> 
              <meta charset="UTF-8" /> 
              <title>扫描二维码支付</title> 
             </head> 
             <body>
             <img src="{url}"/>
             </body>
        </html>
        ';
        $html = str_replace("{url}", $url, $template);
        return $html;
    }
    public static function createQr($url, $msg = "支付宝或微信扫码支付")
    {
        $template = '
        <!doctype html>
        <html lang="zh-cn">
            <head>
            <title>扫描二维码支付</title>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
            <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.slim.js"></script>
            <script src="https://cdn.bootcss.com/lrsjng.jquery-qrcode/0.14.0/jquery-qrcode.js"></script>
            </head>
            <body>
                <h2 style="text-align: center;margin-top:3%;">请使用{msg}</h2>
                <div id="qrcode" style="max-height:80%; text-align: center"></div>
                <script type="text/javascript">
                    $("#qrcode").qrcode({"size":200,"text":"{url}"});
                </script>
            </body>
        </html>
        ';
        $html = str_replace("{url}", $url, $template);
        $html = str_replace("{msg}", $msg, $html);
        return $html;
    }
    public static function createFormHtml($url, $data)
    {
        $template = " 
        <!DOCTYPE html>
        <html>
             <head> 
              <meta charset='UTF-8' /> 
             </head> 
             <body>
              {url} 
             </body>
        </html>
        ";
        $html = str_replace("{url}", $url, $template);
        return $html;
    }
    public static function createHtmlV2($url)
    {
        $template = '<html>
    <head>
                <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>pay alipay</title>
        <script type="text/javascript" src="https://cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/jquery-weui/1.2.0/js/jquery-weui.min.js"></script>
        <style>
    *{
      margin: 0;
      padding: 0;
    }
    html,body{
      width: 100%;
      height: 100%;
      font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
    }
    ul{
      list-style: none;
    }
    .newPayInnerBut{
      width: 90%;
      height: 50px;
      margin: 150px auto;
    }
    .newPayInnerBut button{
      display: block;
      width: 100%;
      height: 38px;
      background: #019fe8;
      border: 0;
      color: #ffffff;
      font-size: 20px;
    }
    </style>
    </head>
    <body>
            <script>  
        function Schemepay(payurl){//scheme方式打开 
          var x = window.document;
          _ = x.createElement("iframe");
          _.id = "callapp_iframe_" + Date.now();
          _.frameborder = "0";
          _.style.cssText = "display:none;border:0;width:0;height:0;";
          x.body.appendChild(_);
          _.src = payurl;
          var t = x.createElement("a");
          t.setAttribute("href", payurl), t.style.display = "none", x.body.appendChild(t);
          var o = x.createEvent("HTMLEvents");
          o.initEvent("click", !1, !1), t.dispatchEvent(o);
        }
        function openpay(){
          var payurl="{url}";
          Schemepay(payurl);
        }
        $(document).ready(function(){
        openpay();
        });
                </script>
        <div class="newPayInnerBut">
        <button onclick="openpay()">启动支付宝APP并支付</button>
        </div>
    </body>
</html>';
        $html = str_replace("{url}", $url, $template);
        return $html;
    }
}