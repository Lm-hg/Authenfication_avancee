<?php
class TwoFA {
    // Base32 alphabet
    private static $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public static function generateSecret($length = 16) {
        $chars = self::$alphabet;
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $chars[random_int(0, strlen($chars)-1)];
        }
        return $secret;
    }

    public static function getOtpAuthUrl($issuer, $account, $secret) {
        $issuer = rawurlencode($issuer);
        $account = rawurlencode($account);
        return "otpauth://totp/{$issuer}:{$account}?secret={$secret}&issuer={$issuer}&algorithm=SHA1&digits=6&period=30";
    }

    public static function getQrCodeUrl($issuer, $account, $secret, $size = 200) {
        $otpauth = self::getOtpAuthUrl($issuer, $account, $secret);
        // Use a stable public QR generator (qrserver) as fallback to ensure the image is served
        $chart = 'https://api.qrserver.com/v1/create-qr-code/?size=' . intval($size) . 'x' . intval($size) . '&data=' . rawurlencode($otpauth);
        return $chart;
    }

    private static function base32Decode($secret) {
        $secret = strtoupper($secret);
        $buffer = 0;
        $bitsLeft = 0;
        $output = '';
        for ($i = 0; $i < strlen($secret); $i++) {
            $val = strpos(self::$alphabet, $secret[$i]);
            if ($val === false) continue;
            $buffer = ($buffer << 5) | $val;
            $bitsLeft += 5;
            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $output .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }
        return $output;
    }

    public static function getTotpToken($secret, $time = null) {
        if ($time === null) $time = floor(time() / 30);
        $key = self::base32Decode($secret);
        $msg = pack('N*', 0) . pack('N*', $time);
        $hash = hash_hmac('sha1', $msg, $key, true);
        $offset = ord($hash[19]) & 0x0F;
        $code = (ord($hash[$offset]) & 0x7F) << 24 |
                (ord($hash[$offset+1]) & 0xFF) << 16 |
                (ord($hash[$offset+2]) & 0xFF) << 8 |
                (ord($hash[$offset+3]) & 0xFF);
        $code = $code % 1000000;
        return str_pad($code, 6, '0', STR_PAD_LEFT);
    }

    public static function verifyCode($secret, $code, $window = 1) {
        $timeSlice = floor(time() / 30);
        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals(self::getTotpToken($secret, $timeSlice + $i), str_pad($code, 6, '0', STR_PAD_LEFT))) {
                return true;
            }
        }
        return false;
    }
}
