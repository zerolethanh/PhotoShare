<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class utils
{
    public static function createFileOrDirIfNotExists($fileOrDir)
    {
        if (file_exists($fileOrDir)) return true;
        else {
            return mkdir($fileOrDir, 0777, true);
        }
    }

    public static function mkdir($fileOrDir)
    {
        if (file_exists($fileOrDir)) return true;
        else {
            return mkdir($fileOrDir, 0777, true);
        }
    }

    public static function qrSave($text, $path, $label, $size = 300)
    {
        $path = $path ?: Auth::user()->userPath();
        $qrCode = new \Endroid\QrCode\QrCode();
        $qrCode
            ->setText($text)
            ->setSize($size)
            ->setPadding(5)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0));

        if ($label) {
            $qrCode
                ->setLabel($label)
                ->setLabelFontSize(16);
        }
        $img = $qrCode->getImage();
        $succ = imagepng($img, $path);
        if (!$succ) die('can not save QrCode for ' . $path);
        imagedestroy($img);
        return $succ;
    }

    public static function showImageDirectToBrowser($filePath)
    {
        $size = getimagesize($filePath);
        $fp = fopen($filePath, "rb");

        if ($size && $fp) {
            header("Content-type: {$size['mime']}");
            fpassthru($fp);
            exit;
        } else {
        }
    }

    public static function downloadCsvPath($addpath = null)
    {
        return storage_path(self::me('email') . '/download/csv/' . ($addpath ?: ''));
    }

    public static function uploadImportPath($addpath = null)
    {
        return storage_path(self::me('email') . '/upload/import/' . ($addpath ?: ''));
    }

    public static function uploadFiles($addpath = null)
    {
        return storage_path(self::me('email') . '/upload/files/' . ($addpath ?: ''));
    }

    public static function publicImages($file = null)
    {
        $dir = storage_path('public/image');
        return $file ? $dir . '/' . $file : $dir;
    }

    public static function uploadFileNamePrefix($bid)
    {
        $uuid = \UUID::v4();
        $time = date('YmdHis');
        return "{$bid}_{$uuid}_{$time}";
    }

    public static function uploadSignDir($addpath = null)
    {
        return storage_path(self::me('email') . '/upload/sign/' . ($addpath ? $addpath : ''));
    }

    public static function uploadSignDirViaBid($billOrBid, $addpath = null)
    {
        if (!($billOrBid instanceof App\Bill)) $billOrBid = App\Bill::find($billOrBid);
        if ($billOrBid) {
            $dir = storage_path($billOrBid->fr_email . '/upload/sign/');
            return $addpath ? ($dir . $addpath) : $dir;
        }
        return null;
    }

    public static function uploadDirViaBid($billOrBid, $addpath = null)
    {
        if (!($billOrBid instanceof App\Bill)) $billOrBid = App\Bill::find($billOrBid);
        if ($billOrBid) {
            $dir = storage_path($billOrBid->fr_email . '/upload/files/');
            return $addpath ? ($dir . $addpath) : $dir;
        }
        return null;
    }

    public static function uploadFileDirOfUser($userIdOrUserEmail, $addpath = null)
    {
        if (is_numeric($userIdOrUserEmail)) $userIdOrUserEmail = User::find($userIdOrUserEmail)->pluck('email');
        return storage_path($userIdOrUserEmail . '/upload/files' . ($addpath ? $addpath : ''));
    }

    public static function isMeSentBill($billorBillid)
    {
        if (!($billorBillid instanceof \App\Bill)) {
            $billorBillid = \App\Bill::find($billorBillid);
        }
        if (!$billorBillid) return false;
        return self::me('id') == $billorBillid->from_id;
    }

    public static function isMeSentMsg($msg)
    {
        if (!$msg || !($msg instanceof \App\Msg)) return false;
        return self::me('id') == $msg->fr_id;
    }

    public static function isBoxInIfBoxName($boxname)
    {
        return in_array($boxname, ['boxin', 'inbox', 'back', 'inpaid', 'confirmed', 'instored']);
    }


    public static function isBoxOutIfBoxName($boxname)
    {
        return in_array($boxname, ['boxout', 'draft', 'sent', 'returned', 'outpaid', 'outstored', 'trashedbox']);
    }

    public static function allBoxTotal()
    {
        $counts = [];
        foreach (self::$boxes as $box) {
            $counts[$box] = App\Bill::$box()->count();
        }
        return $counts;
    }

    static $boxes = ['boxin', 'boxout', 'boxall',
        'inbox', 'back', 'backtrashed', 'inpaid',
        'confirmed', 'instored', 'draft',
        'sent', 'returned', 'outpaid', 'outstored', 'trashedbox'];
    static $recountBoxes = ['boxin', 'boxout', 'boxall',
        'inbox', 'back', 'inpaid',
        'confirmed', 'draft',
        'sent', 'returned', 'outpaid'];

    static $sentBoxNames = ['sent', 'outpaid', 'outstored', 'returned'];
    static $receivedBoxNames = ['inbox', 'confirmed', 'inpaid', 'instored'];
    static $draftBoxNames = ['draft'];

    public static function canControlBox($boxname)
    {
        return in_array($boxname, array_merge(self::$sentBoxNames, self::$receivedBoxNames, self::$draftBoxNames));
    }

    public static function isValidBox($boxname)
    {
        return in_array($boxname, self::$boxes);
    }

    public static function recountBox($boxes)
    {
        if (!is_array($boxes)) $boxes = [$boxes];
        $result = [];
        foreach ($boxes as $boxname) {
            if (static::isValidBox($boxname)) {
                $result[$boxname] = App\Bill::$boxname()->count();
            }
        }
        static::boxname_counts_write_db($result);

        return $result;
    }

    static function boxname_counts_write_db(array $data)
    {
        $data = array_only($data, \Illuminate\Support\Facades\Schema::getColumnListing('boxname_counts'));

        $auth_id = auth()->id();
        $data['user_id'] = $auth_id;
        $rec = \App\BoxnameCount::where('user_id', $auth_id)->first();
        if ($rec) {
            $rec->update($data);
        } else {
            \App\BoxnameCount::create($data);
        }
        return $rec;
    }

    static function auth_id()
    {
        return auth()->id();
    }

    public static function recountAllBox()
    {
//        return static::recountBox(static::$boxes);
        return self::recountBox(self::$recountBoxes);
    }

    public static $me;

    public static function me($need = null)
    {
        $me = self::$me ?: (self::$me = Auth::user());
        return $need ? $me->$need : $me;
    }

    public static function me_update(array $attributes)
    {
        $me = self::me();
        if (!$me) return false;
        return $me->update($attributes);
    }

    public static function maxserial()
    {
        return self::me('maxserial');
    }

    public static function notme($bid, $need = null)
    {
        if ($bid instanceof \App\Bill) {
            $bill = $bid;
        } else {
            $bill = Bill::find($bid)->toArray();
        }
        if (self::me('id') == $bill->id) $isout = true;
        else $isout = false;
        if ($isout) {
            if ($need == 'id' || $need == 'to_id') {
                return $bill->to_id;
            } elseif ($need == 'email' || $need == 'to_email') {
                return $bill->to_email;
            }
        } else {
            if ($need == 'id' || $need == 'from_id') {
                return $bill->from_id;
            } elseif ($need == 'email' || $need == 'fr_email') {
                return $bill->fr_email;
            }
        }
    }

    //emails
    public static function sendNotificationEmailForNewBill($bill)
    {
        if (!$bill) return false;
        $receivedUser = App\User::find($bill->to_id);
        $sentUser = App\User::find($bill->from_id);
        if (!$receivedUser || !$sentUser) return false;

        $sentUser_locale = $sentUser->locale;

        $subject = trans("NewBillEmail.subject", [], null, $sentUser_locale);
        $mailFile = "emails.newBillNotification_" . $sentUser_locale;

        return Mail::send($mailFile,
            ["receivedUser" => $receivedUser,
                "sentUserInfo" => "{$sentUser['fcn']} {$sentUser['fullname']}",
                "receivedUserInfo" => "{$receivedUser['fcn']} {$receivedUser['fullname']}",
                "bill" => $bill,
                "billPaydate" => $bill->paydate,// \Carbon\Carbon::parse($bill->paydate)->format('Y/m/d'),
                "billPaysum" => $bill->paysum
            ],
            function ($msg) use ($receivedUser, $subject, $sentUser) {
                $msg->from($sentUser->email, $sentUser->fcn)
                    ->to($receivedUser->email)
                    ->subject($subject);
            }
        );

    }

    public static function sendNotificationEmailForReturnedBill($bill)
    {
        if (!$bill) return false;
        $receivedUser = App\User::find($bill->to_id);
        $sentUser = App\User::find($bill->from_id);
//        dd($sentUser->email);

        if (!$receivedUser || !$sentUser) return false;//['success' => false];

        $subject = trans("ReturnedBillEmail.subject", [], null, $sentUser->locale);

        Mail::send('emails.billReturnedNotification',
            ["receivedUser" => $receivedUser,
                'sentUser' => $sentUser,
                "sentUserInfo" => "{$sentUser['fcn']} {$sentUser['fullname']}",
                "receivedUserInfo" => "{$receivedUser['fcn']} {$receivedUser['fullname']}",
                "bill" => $bill,
                "billPaydate" => $bill->paydate,
                "billPaysum" => $bill->paysum
            ],
            function ($msg) use ($receivedUser, $subject, $sentUser) {
                $msg->from($receivedUser->email, $receivedUser->fcn)
                    ->to($sentUser->email)
                    ->subject($subject);
            }
        );

    }

    public static function sendNotificationEmailForNewMessage($newmsg)
    {
        $receivedUser = App\User::find($newmsg->to_id);
        $sentUser = App\User::find($newmsg->fr_id);

        $sentUser_locale = $sentUser->locale;

        if (!$receivedUser || !$sentUser) return ['success' => false];

        $msg = $newmsg->msg;
        if (mb_strlen($msg, 'UTF-8') > 17) $msg = str_limit($msg, 17);
        try {
            $time = \Carbon\Carbon::parse($newmsg->time)->format('Y/m/d H:i:s');
        } catch (Exception $e) {
            $time = Carbon::now()->format('Y/m/d H:i:s');
        }
        $sentUserInfo = "{$sentUser['fcn']} {$sentUser['fullname']}";

        $subject = utils::trans("NewMessageEmail.subject");
        $mailFile = "emails.newMessageNotificationMail_" . $sentUser_locale;
        if (!view()->exists($mailFile)) {
            $mailFile = "emails.newMessageNotificationMail_en";
        }

        $receivedUserEmail = $newmsg->toemail;

        $mail = Mail::send($mailFile,
            ["receivedUser" => $receivedUser,
                "msg" => $msg,
                "time" => $time,
                "sentUserInfo" => $sentUserInfo
            ],
            function ($msg) use ($receivedUserEmail, $subject, $sentUser) {
                $msg->from(utils::me("email"), $sentUser->fcn)
                    ->to($receivedUserEmail)
                    ->subject($subject);
//                dd($msg->getSwiftMessage());
            }
        );
//        dd($mail);

    }

    public static function mailAppendFooter()
    {
        return
            "
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
●Bank Invoice（文書データ電子取引システム）
https://www.amadellas.com/bank-invoice

●配信元：「紙をなくす会社。」アマデラス株式会社
https://www.amadellas.com/bank-invoice
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
本メールは、アマデラス株式会社「Bank Invoice」サービスより自動で送信されております。
返信されましても受付できませんのでご了承ください。
";
    }


    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function fNumber($number)
    {
        $number = number_format($number, 5); // 1,200.50
        $number = preg_replace("/\.?0+$/", "", $number); // 1,200.5
        return $number;
    }

    public static function queryLogs($raw = false, $needtype = false, $needtime = false)
    {

        $queries = DB::getQueryLog();
        if ($raw) return $queries;
        $qs = [];
        foreach ($queries as $q) {
            $bindings = $q['bindings'];
            $query = $q['query'];

            $query = explode('?', $query);
            foreach ($query as $k => $val) { //k=0,1,2,...
                if ($k < count($bindings)) {
                    $replace = $bindings[$k];
                    $type = gettype($replace);
                    $query[$k] = "{$val}" . ($needtype ? "($type)" : '') . "{$replace}";
                }
            }
            $qs[] = implode('', $query) . ($needtime ? " ({$q['time']}ms)" : '');
        }
        return $qs;
    }

    public static function queryInfos()
    {
        $quser = self::me('email');
        $qlog = self::queryLogs();
        return compact('quser', 'qlog');
    }

    public static function number_format_drop_zero_decimals($n, $n_decimals)
    {
        return ((floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals));
    }

    public static function timezoneList()
    {
        $idlist = timezone_identifiers_list();
        $UTCOffsets = [];
        foreach ($idlist as $id) {
            $timezone = new DateTimeZone($id);
            $utcnow = new DateTime('now', new DateTimeZone('UTC'));

            $offsetInSeconds = $timezone->getOffset($utcnow);

            $hm = sprintf("%02d:%02d", floor(abs($offsetInSeconds) / 3600), (abs($offsetInSeconds) / 60) % 60);

            $hm = $offsetInSeconds >= 0 ? "+{$hm}" : "-{$hm}";

            $UTCOffsets[] = ["UTC$hm $id", $id];
        }
        return $UTCOffsets;
    }

    public static function offsetFromUTC($id, $utcFormat = false)
    {

        try {
            $timezone = new DateTimeZone($id);
        } catch (Exception $e) {
            $timezone = new DateTimeZone(date_default_timezone_get());
        }
        $utcnow = new DateTime('now', new DateTimeZone('UTC'));
        $offsetInSeconds = $timezone->getOffset($utcnow);
        $hm = sprintf("%02d:%02d", floor(abs($offsetInSeconds) / 3600), (abs($offsetInSeconds) / 60) % 60);

        if ($utcFormat) {
            $hm = $offsetInSeconds >= 0 ? "+{$hm}" : "-{$hm}";
            return "UTC$hm";
        }
        return $hm;
    }

    public static function secondsFromUTC($id)
    {

        try {
            $timezone = new DateTimeZone($id);
        } catch (Exception $e) {
            $timezone = new DateTimeZone(date_default_timezone_get());
        }
        $utcnow = new DateTime('now', new DateTimeZone('UTC'));
        $offsetInSeconds = $timezone->getOffset($utcnow);
        return $offsetInSeconds;
    }

    public static function addTimezone($time, $timezone)
    {

        if (strpos($time, 'UTC') !== false) return $time;//if UTC+timezone

        $timezone = $timezone ?: date_default_timezone_get();
        $date = Carbon::parse($time, $timezone);

        $result = $date->format(DB::table('dateformats')->where('locale', Auth::user()->locale)->pluck('msgmemohistory'));
        return $result . " (UTC" . $date->format('P)');
    }

    public static function messageSendTimeFormat($timezone, $locale = null)
    {
        $date = Carbon::now($timezone);
        $result = $date->format(DB::table('dateformats')
            ->where('locale', $locale ?: Auth::user()->locale)
            ->pluck('msgmemohistory'));
        return $result . " (UTC" . $date->format('P)');
    }


    public static function iotime($time, $timezone)
    {
        $timezone = $timezone ?: date_default_timezone_get();
        $date = Carbon::parse($time, $timezone);
        $result = $date->format(DB::table('dateformats')->where('locale', Auth::user()->locale)->pluck('iotime'));
        return $result;
    }

    public static function iotimeFormat($time, $timezone = null)
    {
        $timezone = $timezone ?: Auth::user()->timezone;
        $date = Carbon::parse($time)
            ->addSeconds(utils::secondsFromUTC($timezone) - utils::secondsFromUTC(date_default_timezone_get()));
        $ret = $date->format(DB::table('dateformats')->where('locale', Auth::user()->locale)->pluck('iotime'));
        return $ret;
    }

    public static function iotime_for_csv($time, $timezone)
    {
        $timezone = $timezone ?: date_default_timezone_get();
        $date = Carbon::parse($time, $timezone);
        $result = $date->format("Y/m/d H:i:s") . "(UTC" . $date->format('P)');
        return $result;
    }

    public static function trans($need, $user_locale = null, $params = [])
    {
        return trans($need, $params, null, $user_locale ?: Auth::user()->locale);
    }

    public static function getColumns($table)
    {
        return Schema::getColumnListing($table);
    }

    public static function isFieldInTable($table, $fieldName)
    {
        return in_array($fieldName, self::getColumns($table));
    }


    public static function getAllNewMessages()
    {
        $query = Auth::user()->receivedMsgs()->where('isnew', 1);
        $hasNewMessages = $query->exists();
        if ($hasNewMessages) {
            $numOfNewMessages = $query->count();
        }
        return compact("hasNewMessages", 'numOfNewMessages');
    }

    public static function whereInString($field, array $in)
    {
        array_walk($in, function (&$val) {
            $val = "'$val'";
        });
        $in = "(" . implode(',', $in) . ")";
        return "$field IN $in";
    }

    public static function defaultTz()
    {
        return date_default_timezone_get();
    }

    public static function tzList()
    {
        return array_map(function ($val) {
            return strtolower($val);
        }, timezone_identifiers_list());
    }

    public static function phpTimezone($fromTimezone)
    {
        if (!$fromTimezone) return static::defaultTz();
        if (strpos($fromTimezone, "/") !== false) {
            $fromTimezone = explode('/', $fromTimezone);
            if (count($fromTimezone) > 2) {
                $fromTimezone = "$fromTimezone[1]/$fromTimezone[2]";
            } else {
                $fromTimezone = $fromTimezone[1];
            }
        }
        $fromTimezone = strtolower(trim($fromTimezone));
        $tzlist = timezone_identifiers_list();

        $tz = array_where($tzlist, function ($k, $val) use ($fromTimezone) {
            $val = strtolower($val);
            return (strpos($val, $fromTimezone) !== false) || (strpos($fromTimezone, $val) !== false);
        });
        if (!empty($tz) && is_array($tz)) {
            return array_values($tz)[0];
        }
        return date_default_timezone_get();
    }

    public static function fixtime($tz, $locale = null)
    {
        return \utils::messageSendTimeFormat(\utils::phpTimezone($tz), $locale);
    }

    public static function formatNumberWithCurIso($number, $iso = 'JPY')
    {
        $f = \App\Currency::select('decimals', 'dec_point', 'thousands_sep')->where('iso', $iso)->first();
        if ($f) {
            return number_format($number, $f['decimals'], $f['dec_point'], $f['thousands_sep']);
        }
        return $number;
    }

    public static $FORMATTED_SUM_FIELDS = ['nosum', 'rsum', 'ssum', 'sum'];

    public static function formattedValuesForBill($bill, array $fields, $values)
    {
        $billcur = is_object($bill) ? $bill->billcur : $bill;
        $f = \App\Currency::where('iso', $billcur)->first();
        $result = [];
        foreach ($fields as $field_name) {
            $v = isset($values[$field_name]) ? $values[$field_name] : null;
            $result[$field_name] =
                is_null($v) ?
                    null :
                    number_format($values[$field_name], $f['decimals'], $f['dec_point'], $f['thousands_sep']);
        }
        return $result;
    }

    public static $billCurrrencyObject;

    public static function billCurrrencyObject($bill)
    {
        if (static::$billCurrrencyObject) {
            return static::$billCurrrencyObject;
        }
        $billcur = is_string($bill) ? $bill : $bill['billcur'];
        return static::$billCurrrencyObject = \App\Currency::where('iso', $billcur)->first();
    }

    public static function enTrans($path, $params = [], $domain = 'messages')
    {
        return trans($path, $params, $domain, 'en');
    }

    public static function isValidUuid($uuid)
    {

        return preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid);

    }
}