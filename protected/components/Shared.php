<?php

/**
 * Set of functions for debuging, working with dates and other helpers
 */
class Shared extends CApplicationComponent {

    const day = 86400; // 1 day
    const week = 604800; // 7 day
    const month = 2678400; // 31 days
    const year = 31536000; // 365 days

    // PHP mailer cache

    public static $mailer;
    // file handler for debug file
    static $fh;

    /**
     * Timezones in USA only ... until we expand to europe
     * @var type 
     */
    static $timezones = array(
        -10 => 'US - Hawaii',
        -9 => 'US - Alaska',
        -8 => 'US - Pacific Time (Los Angeles)',
        -7 => 'US - Mountain Time (Denver)',
        -6 => 'US - Central Time',
        -5 => 'US - Eastern Time',
        -4 => 'Canada - Atlantic Time'
    );

    /**
     * Set the flash message to show debug output on the next page
     * Widged ZienDebug is called for displaying the message.
     * @param <type> $object
     * @param <type> $message 
     */
    public static function debug($object, $message = '', $storeTo = null) {

        $trace = debug_backtrace();


        $file = $trace[0]['file'];
        $line = $trace[0]['line'];
        $pos = strpos($file, 'protected');
        $file = substr($file, $pos);
        $output = "";
        $output .= $file . " (" . $line . "): ";
        //$output .= print_r($trace, 1);
        if (strlen($message) > 0)
            $output .= " - $message - ";
        if (is_array($object)) {
            $output .= "<pre>" . print_r($object, 1) . "</pre>";
        } else if (is_object($object)) {
            if (isset($object->attributes)) {
                $output .= "<pre>" . print_r($object->attributes, 1) . "</pre>";
            } else {
                $output .= "<pre>" . print_r($object, 1) . "</pre>";
            }
        } else {
            $output .= $object;
        }

        /* if (is_object(Yii::app()->user)) {
          if (Yii::app()->user->hasFlash('debug')) {
          Yii::app()->user->setFlash('debug', Yii::app()->user->getFlash('debug') . "<br />" . $output);
          } else {
          Yii::app()->user->setFlash('debug', $output);
          }
          } */

        // write it to the file
        $path = YiiBase::getPathOfAlias('application.runtime');
        $debugFile = $path . "/debug.txt";
        if (!self::$fh) {
            self::$fh = fopen($debugFile, 'a');
        }

        // store it to another file
        if ($storeTo != null) {
            // we want to stay inside  runtime directory
            $storeTo = str_replace("/", "", $storeTo);
            $storeTo = str_replace("\\", "", $storeTo);
            $storeTo = $path . "/" . $storeTo;
            $fh = fopen($storeTo, 'a');
            fwrite($fh, $output);
            fclose($fh);
        } else if (self::$fh) {
            $output = self::toDatabase(time()) . ": " . $output . "\r\n";
            fwrite(self::$fh, $output);
            //fclose($fh);
        }
    }

    /**
     * Initiate the mailer component. It is singleton
     * @return type 
     */
    private static function getMailer() {
        if (self::$mailer == null) {
            Yii::import('ext.phpmailer.PHPMailer');
            self::$mailer = new PHPMailer();
            self::$mailer->IsSes();
            self::$mailer->Port = Yii::app()->params['email']['smtpPort'];
            self::$mailer->Host = Yii::app()->params['email']['smtpHost'];
            self::$mailer->SMTPAuth = true;
            self::$mailer->SMTPSecure = 'tls';
            self::$mailer->Port = 25;
            self::$mailer->Username = Yii::app()->params['email']['smtpUser'];
            self::$mailer->Password = Yii::app()->params['email']['smtpPassword'];
        }
        return self::$mailer;
    }

    /**
     * Display success banner. Banner is displayed even though page is redirected to new view.
     * @param <type> $message
     */
    public static function success($message = '') {

        if (!Yii::app()->params['cron']) {
            if (Yii::app()->user->hasFlash('success')) {
                Yii::app()->user->setFlash('success', Yii::app()->user->getFlash('success') . "<br />" . $message);
            } else {
                Yii::app()->user->setFlash('success', $message);
            }
        }
    }

    /**
     * Store errors produced in another form
     * @param <type> $errors
     */
    public static function setFormErrors($errors) {
        self::$formErrors = $errors;
    }

    /**
     * Display error message. Banner is displayed even though page is redirected to new view.
     * You can display multiple error messages inside one window.
     * @param <type> $message
     */
    public static function error($message = '') {

        if (!Yii::app()->params['cron']) {
            if (Yii::app()->user->hasFlash('error')) {
                Yii::app()->user->setFlash('error', Yii::app()->user->getFlash('error') . "<br />" . $message);
            } else {
                Yii::app()->user->setFlash('error', $message);
            }
        }
    }

    /**
     * Returns random 6 digits code;
     * @return <type>
     */
    public static function generateCode($lenght = 6) {
        srand((double) microtime() * 1000000);
        // we don't want leading zeros
        $output = rand(1, 9);
        for ($i = 1; $i < $lenght; $i++) {
            $output .= rand(0, 9);
        }
        return $output;
    }

    /**
     * Generate 8 letter long random password.
     */
    static function generateRandomPassword($length = 7) {

        $chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXZ023456789";
        srand((double) microtime() * 1000000);
        $i = 0;
        $pass = '';

        while ($i <= $length) {
            $num = mt_rand() % strlen($chars);
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }

        return $pass;
    }

    /**
     * Produces a little bit better passwords you can remember
     * The lenght is 8 characters, contains two digits and one capital letter
     * @return <type>
     */
    static function generateMnemonicPassword() {
        $numlen = 1;
        $length = 6;
        $output = '';
        $vowels = 'aeioe';
        $consonants = 'bcdfghklmnpqrstvwxzy';
        srand((double) microtime() * 1000000);

        for ($i = 0; $i < $length / 2; $i++) {
            $output .= $vowels[rand() % strlen($vowels)];
            $output .= $consonants[rand() % strlen($consonants)];
        }

        // put there one capital letter
        $rand = rand() % strlen($length - 1);
        $output[$rand] = strtoupper($output[$rand]);

        $pos = rand(2, $length - 2 - $numlen);
        return substr($output, 0, $pos) . rand(10, 99) . substr($output, $pos);
    }

    /**
     * Create an formated string for mailing address
     * from separated fields
     * @param <type> $model
     * @return string
     */
    public static function formatAddress($model) {
        if (is_object($model)) {
            if (strlen($model->street_and_number) > 0) {
                $address = "<p>" . nl2br($model->street_and_number) . "<br />" .
                        $model->city_name . ", " . (isset($model->state_name) ? $model->state_name : "") . (isset($model->country) && $model->country != 'other' ? ", " . $model->country : "") .
                        " <br /> " . $model->zip_code . "</p>";
            } else {
                $address = "no address";
            }
            return $address;
        }
        return "";
    }

    /**
     * Converts everything into the timestamp
     * @param <mixed> $date
     * @return <timestamp>
     */
    public static function toTimestamp($date) {
        if (is_int($date)) {
            return $date;
        }
        if (($time = strtotime($date)) !== false) {
            return $time;
        }

        return false;
    }

    /**
     * Returns number of days in a date range.
     * @param <type> $dateFrom
     * @param <type> $dateTo
     * @return <type>
     */
    public static function numberOfDays($dateFrom, $dateTo) {
        $timeFrom = self::toTimestamp($dateFrom);
        $timeTo = self::toTimestamp($dateTo);
        if ($timeTo > $timeFrom) {

            $time = $timeTo - $timeFrom;
            $time = ceil($time / self::day);
            return $time;
        } else if ($timeTo == $timeFrom) {
            return 1;
        }
        return 0;
    }

    /**
     * Convert the time format to the database format
     */
    public static function toDatabase($date) {
        $time = self::toTimestamp($date);
        $formatted = date('Y-m-d H:i:s', $time);
        return $formatted;
    }

    public static function toDatabaseShort($date) {
        $time = self::toTimestamp($date);
        $formatted = date('Y-m-d', $time);
        return $formatted;
    }

    /*
     * A generally accepted United States shorthand date format
     */

    public static function formatShortUSDate($date) {
        $time = self::toTimestamp($date);
        if ($time) {
            $formatted = date('M. d, Y', $time);
        } else {
            $formatted = '<span class="label label-important">undefined</span>';
        }
        return $formatted;
    }

    /**
     * day in a week, day month year
     * @param date $date
     * @return string
     */
    public static function formatDateShort($date) {
        $time = self::toTimestamp($date);
        $formatted = date('l, d F Y', $time);
        return $formatted;
    }

    /**
     * day in a week, day month year hour:minute am/pm
     * @param date $date
     * @return string
     */
    public static function formatDateLong($date) {
        $time = self::toTimestamp($date);
        $formatted = date('l, d F Y g:i a', $time);
        return $formatted;
    }

    /**
     * day month year
     * @param date $date
     * @return string
     */
    public static function formatDateLonger($date) {
        $time = self::toTimestamp($date);
        $formatted = date('d F, Y', $time);
        return $formatted;
    }

    /* public function formatDatePicker($date) {
      $time = self::toTimestamp($date);
      $formatted = date('Y-m-d', $time);
      return $formatted;
      }

      public function formatDateTimePicker($date) {
      $time = self::toTimestamp($date);
      $formatted = date('Y-m-d H:i', $time);
      return $formatted;
      }

      public function formatDatePickerArr($date) {
      $time = self::toTimestamp($date);
      $formatted = array();
      $formatted['date'] = date('Y-m-d', $time);
      $formatted['hour'] = date('h', $time);
      $formatted['minute'] = date('i', $time);
      return $formatted;
      } */

    public static function timeNow() {
        return self::toDatabase(time());
    }

    /**
     * Returns array of input date
     * @param <type> $date
     * @return <type>
     */
    public static function splitDate($date) {
        $res = false;
        $timestamp = self::toTimestamp($date);

        if ($timestamp > 0) {
            // not using getdate() as we want to have 2 digits values

            $my_format = 'yyyy-MM-dd HH:mm:ss';
            $my_date_string = Yii::app()->dateFormatter->format($my_format, $timestamp);

            $res = array(
                'date' => substr($my_date_string, 0, 10),
                'time' => substr($my_date_string, 11),
                'datetime' => $my_date_string,
                'year' => substr($my_date_string, 0, 4),
                'yy' => substr($my_date_string, 2, 2),
                'month' => substr($my_date_string, 5, 2),
                'day' => substr($my_date_string, 8, 2),
                'hour' => substr($my_date_string, 11, 2),
                'min' => substr($my_date_string, 14, 2),
                'sec' => substr($my_date_string, 17, 2),
            );
        }
        return $res;
    }

    /**
     * Merge date from date time fields
     * @param <type> $date
     * @param <type> $hour
     * @param <type> $minute
     */
    public static function mergeDate($date, $hour, $minute) {
        $dateArr = self::splitDate($date);
        $time = mktime($hour, $minute, null, $dateArr['month'], $dateArr['day'], $dateArr['year']);
        return self::toDatabase($time);
    }

    /**
     * Returns simple html link. Use it if you want to point to another view
     * from a view. No ajax and attributes are included.
     * @param <string> $url ie 'campaign/create'
     * @return <string> html link code
     */
    public static function createHtmlLink($text, $url, $htmlOptions = array()) {
        return CHtml::link($text, Yii::app()->urlManager->createUrl($url), $htmlOptions);
    }

    /**
     * Replace placeholders in an email or landing page by correct value
     * from given data. Source data can be array or Active Record object.
     * Function search for attribute name or array key in the text and replace it by
     * its value. Placeholder looks like [attribute].
     * @param <string> $text where to replace placeholders
     * @param <mixed> $sourceData where to get source values for the placeholders
     * @return <string> text without placeholders
     */
    public static function replacePlaceholders($text, $sourceData) {
        // arrays

        if (is_array($sourceData)) {
            $text = str_replace('%5B', '[', $text);
            $text = str_replace('%5D', ']', $text);
            foreach ($sourceData as $attribute => $value) {
                $text = str_replace('[' . $attribute . ']', $value, $text);
                // just for sure
                $text = str_replace('[' . str_replace("_", "-", $attribute) . ']', $value, $text);
                $text = str_replace('[' . str_replace("_", " ", $attribute) . ']', $value, $text);
            }
        } else if (is_object($source_data)) {
            if (is_set($sourceData->attributes)) {
                foreach ($sourceData->attributes as $attribute => $value) {
                    $text = str_replace('[' . $attribute . ']', $value, $text);
                }
            }
        }
        return $text;
    }

    /**
     * Send html email to one single recipient
     * @param <string> $subject
     * @param <string> $body text or html message
     * @param <string> $toEmail recipient email address
     * @param <string> $toName recipient name (will be displayed in To: like "Name LastName <myEmail>"
     * @param <string> $fromEmail who sends the email ... default is client admin, but can be anyone from the client company
     * @param <string> $fromName full name of the sender, will be displayed in users email client
     * @param <boolean> $html is it HTML email body?
     */
    public static function sendEmail($subject, $body, $toEmail, $toName = null, $fromEmail = null, $fromName = null, $html = true, $attachment = null) {
        //Yii::import('application.extensions.phpmailer.JPhpMailer');
        $mail = self::getMailer();
        
        Shared::debug("sending email");
        if ($fromEmail == null || !self::isEmailValid($fromEmail)) {
            $fromEmail = Yii::app()->params['email']['noReplySender'];
            $fromName = Yii::app()->params['email']['senderName'];
        }
        // make sure the sender name is correct
        if ($fromName != null) {
            $fromName = preg_replace("/[^A-Za-z0-9-_ ]/", '', $fromName);
        }

        // there is an email subject placeholder inside, lets replace it here
        /* Vk Commented if (strstr($body, '[email_subject]')){
          $body = str_replace('[email_subject]', $subject, $body);
          } */

        $mail->SetFrom($fromEmail, CHtml::encode($fromName));
        $mail->Subject = CHtml::encode($subject);

        // send this if client doesn't support html
        if ($html) {
            //$mail->AltBody = self::stripTags($body);
            $mail->MsgHTML($body);
        } else {
            $mail->MsgHTML(strip_tags($body));
            $mail->IsHTML(false);
        }
        if (is_array($toEmail)) {
            foreach ($toEmail as $emailRecipient) {
                if (self::isEmailValid($emailRecipient))
                    if (self::isEmailValid($emailRecipient))
                        $mail->AddAddress($emailRecipient);
            }
        }else {

            $mail->AddAddress($toEmail, $toName);
        }



        // debug
        Shared::debug("
                    Message title: $subject<br />
                    From: $fromEmail ($fromName)<br />
                    To: $toEmail ($toName)\n<br />
                    ", 'email headers', 'email.txt');
        Shared::debug($body, 'email body', 'email.txt');
        if ($attachment != null) {
            $mail->AddAttachment($attachment);
        }

        if (false/*AUSA_TEST*/) {
            self::debug("test is on");
            // do not send emails on testing server
            if (Yii::app()->params['email']['testEmail']) {
                // send the email anyway
                self::debug("sending testing email");
                $mail->ClearAllRecipients();
                $mail->addAddress('cool.ece.friend@gmail.com', 'Developer');
                $mail->SetFrom('ausaoffers@gmail.com', 'AO Test');
                return $mail->Send();
            }
            return true;
        } else {
            //Shared::debug($mail);
            return $mail->Send();
        }
    }

    /**
     * Strip tags from HTML emails, including header and keeps links inside.
     * Returns formated plain text version of HTML email
     */
    private static function stripTags($html) {

        // replace links
        $plain = preg_replace("/<a .*?href=[\"']?([^\"']*)[\"']?.*?>(.*)<\/a>/", '$2 ($1)', $html);

        $plain = preg_replace(
                array(
            // Remove invisible content
            '@<head[^>]*?>.*?</head>@siu',
            '@<style[^>]*?>.*?</style>@siu',
            '@<script[^>]*?.*?</script>@siu',
            '@<object[^>]*?.*?</object>@siu',
            '@<embed[^>]*?.*?</embed>@siu',
            '@<applet[^>]*?.*?</applet>@siu',
            '@<noframes[^>]*?.*?</noframes>@siu',
            '@<noscript[^>]*?.*?</noscript>@siu',
            '@<noembed[^>]*?.*?</noembed>@siu',
            // Add line breaks before & after blocks
            '@<((br)|(hr))@iu',
            '@</?((address)|(blockquote)|(center)|(del))@iu',
            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
            '@</?((table)|(th)|(td)|(caption))@iu',
            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
            '@</?((frameset)|(frame)|(iframe))@iu',), array(
            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            "\n\$0", "\n\$0",), $plain
        );
// Remove all remaining tags and comments and return.
        $plain = strip_tags($plain);

        // merge the white space

        $plain = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\r\n\r\n", $plain);
        $plain = preg_replace("/ {4,}/", "    ", $plain);

        return $plain;
    }

    public static function normalizeAttribute($name) {
        return ucwords(trim(strtolower(str_replace(array('-', '_', '.'), ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name)))));
    }

    /**
     * Remove accents from the name
     * @param <type> $string
     * @return <type> 
     */
    public static function removeDiacritics($string) {
        $search = explode(",", "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,ř,ý,č,ň,");
        $replace = explode(",", "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u,r,y,c,n");
        return str_replace($search, $replace, $string);
    }

    /**
     * Format phone number to make it look nicer
     */
    public static function formatPhone($number) {
        $number = preg_replace("/[^0-9]/","",$number);
        if (strlen($number) == 10) {
            return "(" . substr($number, 0, 3) . ") " . substr($number, 3, 3) . "-" . substr($number, 6);
        }
        if (strlen($number) == 11) {
            return substr($number, 0, 1) . "-(" . substr($number, 1, 3) . ") " . substr($number, 4, 3) . "-" . substr($number, 7);
        }
        return $number;
    }

    /**
     * Check that DNS MX record exists for a given domain
     */
    public static function isEmailValid($email) {
        if (!preg_match("/^[_a-zA-Z0-9-_]+(\.[_a-zA-Z0-9-_]+)*@[a-zA-Z0-9-_]+(\.[a-zA-Z0-9-_]+)*(\.[a-zA-Z]{2,3})$/", $email)) {
            return false;
        }
        return true;
        // not sure if the following one works
        /*
          list($userid, $domain) = explode("@", $email);

          // just most common ones to speed up lookup
          $validDomains = array(
          "gmail.com" => true,
          "ausatest.com" => true, // domain used for unit tests
          "us.army.mil" => true,
          "yahoo.com" => true,
          "hotmail.com" => true,
          "aol.com" => true,
          "msn.com" => true,
          "miners.utep.edu" => true,
          "utep.edu" => true
          );
          if (isset($validDomains[$domain])) {
          return true;
          }

          // check its DNS record
          if (checkdnsrr($domain, "MX")) {
          return true;
          } else {
          return false;
          } */
    }

    /**
     * Removes special characters from string, so it can be used in url or folder name
     * Spaces are replaced by hyphens
     * @param type $string 
     */
    public static function urlize($string) {
        return preg_replace("/[^a-z0-9-_]/", '', str_replace(" ", "-", strtolower($string)));
    }

    /**
     * Renders a png image inside images folder
     * @param type $file 
     */
    /* public static function renderImageFromFile($file){
      $path = YiiBase::getPathOfAlias('webroot.images') . '/' . $file;
      header("Content-type: image/png");
      header("Content-Disposition: inline; filename=" . $file);
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      readfile($path);
      } */

    /**
     * Make sure there are no line breaks and special characters in the text
     * we are going to transmit through JSON to the mobile phone
     * @param type $text
     * @return type
     */
    public static function textToPhone($text) {
        return str_replace(array("\r\n", "\r", "\n", "\t"), "", nl2br($text));
    }

}

?>
