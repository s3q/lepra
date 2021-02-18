<?php


class Userinfo
{

    private static function get_user_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    protected static function preg(array $array, string $subject, string $resval = "")
    {
        $result = $resval;
        foreach ($array as $regex => $value) {
            if (preg_match($regex, $subject)) {
                $result = $value;
            }
        }
        return $result;
    }

    public static function get_ip()
    {
        $mainIp = '';
        if (getenv('HTTP_CLIENT_IP'))
            $mainIp = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $mainIp = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $mainIp = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $mainIp = getenv('REMOTE_ADDR');
        else
            $mainIp = 'UNKNOWN';
        return $mainIp;
    }

    public static function get_os()
    {
        $user_agent = self::get_user_agent();
        $os_array       =   array(
            '/windows nt 10/i'         =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        return self::preg($os_array, $user_agent, "Unknown OS Platform");
    }

    public static function get_browser()
    {
        $user_agent = self::get_user_agent();
        $browser_array  =   array(
            '/msie/i'       =>  'Internet Explorer',
            '/Trident/i'    =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/edge/i'       =>  'Edge',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/ubrowser/i'   =>  'UC Browser',
            '/mobile/i'     =>  'Handheld Browser'
        );

        return self::preg($browser_array, $user_agent, "Unknown Browser");
    }

    public static function  get_device()
    {

        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr(self::get_user_agent(), 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-'
        );

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower(self::get_user_agent()), 'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }

        if ($tablet_browser > 0) {
            // do something for tablet devices
            return 'Tablet';
        } else if ($mobile_browser > 0) {
            // do something for mobile devices
            return 'Mobile';
        } else {
            // do something for everything else
            return 'Computer';
        }
    }
}


class Uploadfile
{

    private static $fileArr;
    private static $uploadfiledir;
    private static $fileArrAllow;

    private static $newfilename;
    private static $newdirname;

    function __construct(array $fileArr, string $uploadfiledir, array $fileArrAllow = array())
    {
        self::$fileArr = $fileArr;
        self::$fileArrAllow = $fileArrAllow;

        if (dir($uploadfiledir)) {
            self::$uploadfiledir = $uploadfiledir;
        } else {
            self::$uploadfiledir = false;
        }
    }


    public static function print()
    {
        if (is_array(self::$fileArr) && count(self::$fileArr) !== 0 && self::$fileArr["name"]) {
            echo "<pre>";
            $array_keys = array_keys(self::$fileArr);
            $ik = 0;
            foreach (self::$fileArr as $item) {
                echo $array_keys[$ik] . " : " . $item . "<br>";
                $ik++;
            }
            echo self::$uploadfiledir . "<br>";
            print_r(self::$fileArrAllow);
            echo "</pre>";
        }
    }

    public static function preparation($adpf = "")
    {
        if (is_array(self::$fileArr) && count(self::$fileArr) !== 0 && self::$fileArr["name"]) {
            if (self::$uploadfiledir) {

                $filenamecmps = explode(".", self::$fileArr["name"]);
                $fileextension = strtolower($filenamecmps[1]);

                self::$newfilename = md5($adpf . self::$fileArr["name"]) . "." . $fileextension;
                self::$newdirname = self::$uploadfiledir . "/" . self::$newfilename;

                if (is_array(self::$fileArrAllow) && count(self::$fileArrAllow) !== 0)
                    if (in_array($fileextension, self::$fileArrAllow))
                        return true;
                    else
                        return false;
                else
                    return true;
            }
        } else {
            return false;
        }
    }

    public static function uploaded_file()
    {
        if (self::preparation() && self::$uploadfiledir) {
            if (move_uploaded_file(self::$fileArr["tmp_name"], self::$newdirname)) {
                echo "<pre>" . "done ..." . "</pre>";
            }
        }
    }
}

class Sendmail
{

    private static $to;
    private static $subject;
    private static $message;
    private static $headers;

    // 'From: webmaster@example.com' . "\r\n" .
    // 'Reply-To: webmaster@example.com' . "\r\n" .
    // 'X-Mailer: PHP/' . phpversion();

    function __construct($to, $subject, $message, $headers = "")
    {
        if (filter_var($to, FILTER_VALIDATE_EMAIL) && trim($to) !== "" && trim($subject) !== "" && trim($message) !== "") {

            filter_var($subject, FILTER_SANITIZE_STRING);
            filter_var($message, FILTER_SANITIZE_STRING);

            self::$to = htmlspecialchars($to);
            self::$subject = htmlspecialchars($subject);
            self::$message = htmlspecialchars($message);
            self::$headers = htmlspecialchars($headers);
        }
    }

    public static function send()
    {
        if (trim(self::$headers) === "") {
            if (mail(self::$to, self::$subject, self::$message))
                echo "<div class='alert bgct-green'> done. </div>";
        } else {
            if (mail(self::$to, self::$subject, self::$message . self::$headers))
                echo "<div class='alert bgct-green'> done. </div>";
        }
    }

    public static function print()
    {
        echo "<pre class='p-10 bo-dark m-center color-light' style='white-space: break-spaces; overflow: hidden;'>";
        echo "To : " . self::$to . "<br>";
        echo "Subject : " . self::$subject . "<br>";
        echo "Message : " . self::$message . "<br>";
        echo "</pre>";
    }
}
