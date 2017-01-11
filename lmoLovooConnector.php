<?php
/**
 * Created by PhpStorm.
 * User: Swoosh
 * Date: 26.04.16
 * Time: 16:57
 */

require_once "lmoLovooCredentials.php";

class lmoLovooConnector
{

    private $_cred;
    private $_cookies;

    function __construct(lmoLovooCredentials $cred)
    {

        $this->_cred = $cred;

    }

    function GetAuthCookies()
    {
        $data = $this->GetWebResource("http://lovoo.com/", "");
        $this->_cookies = $data["cookies"];
        return $this->_cookies;
    }

    function AttemptAuth()
    {
        $fields = array(
            '_username' => urlencode($this->_cred->GetUsername()),
            '_password' => urlencode($this->_cred->GetPassword()),
            '_remember_me' => urlencode("true")
        );

        $auth_result = $this->GetWebResource("https://www.lovoo.com/login_check", $this->_cookies, $fields);
        $this->_cookies = $auth_result["cookies"];
        return $auth_result;
    }

    function GetUserDetails($user_hash){

        $request_result = $this->GetWebResource("https://www.lovoo.com/api_web.php/users/".$user_hash."/details", $this->_cookies);
        $raw_json = $request_result["content"];
        $obj = json_decode($raw_json);
        return $obj;

    }

    function GetUserInfo($user_hash){

        $request_result = $this->GetWebResource("https://www.lovoo.com/api_web.php/users/".$user_hash, $this->_cookies);
        $raw_json = $request_result["content"];
        $obj = json_decode($raw_json);
        return $obj;

    }

    function GetUsers($from_age, $to_age, $online, $page){
        $request_result = $this->GetWebResource("https://www.lovoo.com/api_web.php/users?ageFrom=$from_age&ageTo=$to_age&gender=2&genderLooking=1&isOnline=$online&latitude=52.1485281&longitude=10.5624961&orderBy=distance&radiusTo=50&resultPage=$page&userQuality[0]=pic", $this->_cookies);
        //$request_result = $this->GetWebResource("https://www.lovoo.com/api_web.php/users?ageFrom=$from_age&ageTo=$to_age&gender=2&genderLooking=1&isOnline=$online&latitude=52.520150&longitude=13.405990&orderBy=distance&radiusTo=50&resultPage=$page&userQuality[0]=pic", $this->_cookies);
        $raw_json = $request_result["content"];
        $obj = json_decode($raw_json);
        return $obj;
    }

    function GetWebResource($url, $cookiesIn = '', $post_fields = array())
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER         => true, //return headers in addition to content
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING       => "", // handle all encodings
            CURLOPT_AUTOREFERER    => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT        => 120, // timeout on response
            CURLOPT_MAXREDIRS      => 10, // stop after 10 redirects
            CURLINFO_HEADER_OUT    => true,
            CURLOPT_SSL_VERIFYPEER => false, // Disabled SSL Cert checks
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_COOKIE         => $cookiesIn,

            CURLOPT_USERAGENT      => "User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:49.0) Gecko/20100101 Firefox/49.0"
        );



        $ch = curl_init($url);


        if (count($post_fields) > 0) {
            $fields_string = "";
            foreach ($post_fields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }

            rtrim($fields_string, '&');

            curl_setopt($ch, CURLOPT_POST, count($post_fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        }


        curl_setopt_array($ch, $options);
        $rough_content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header_content = substr($rough_content, 0, $header['header_size']);
        $body_content = trim(str_replace($header_content, '', $rough_content));
        $pattern = "#Set-Cookie:\\s+(?<cookie>[^=]+=[^;]+)#m";
        preg_match_all($pattern, $header_content, $matches);
        $cookiesOut = implode("; ", $matches['cookie']);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['headers'] = $header_content;
        $header['content'] = $body_content;
        $header['cookies'] = $cookiesOut;
        return $header;
    }

} 