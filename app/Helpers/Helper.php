<?php

function format_uang_with_rp($angka){ 
    $hasil =  number_format($angka,0, ',' , '.'); 
    return "Rp. ".$hasil; 
}

function format_uang($angka){ 
    $hasil =  number_format($angka,0, ',' , '.'); 
    return $hasil; 
}

function encode($password, $plain_text, $iv_len = 16)
	{
        // $password = $this->key;
		$plain_text .= "\x13";
        $n = strlen($plain_text);
        if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
        $i = 0;
        $enc_text = get_rnd_iv($iv_len);
        $iv = substr($password ^ $enc_text, 0, 512);
        while ($i < $n) {
            $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
            $enc_text .= $block;
            $iv = substr($block . $iv, 0, 512) ^ $password;
            $i += 16;
        }
        $hasil = base64_encode($enc_text);
        return str_replace('+', '@', $hasil);
    }

function decode($password, $enc_text, $iv_len = 16)
    {
        // $password = $this->key;
        $enc_text = str_replace('@', '+', $enc_text);
        $enc_text = base64_decode($enc_text);
        $n = strlen($enc_text);
        $i = $iv_len;
        $plain_text = '';
        $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
        while ($i < $n) {
            $block = substr($enc_text, $i, 16);
            $plain_text .= $block ^ pack('H*', md5($iv));
            $iv = substr($block . $iv, 0, 512) ^ $password;
            $i += 16;
        }
        return preg_replace('/\\x13\\x00*$/', '', $plain_text);
    }
    
function get_rnd_iv($iv_len)
    {
        $iv = '';
        while ($iv_len-- > 0) {
            $iv .= chr(mt_rand() & 0xff);
        }
        return $iv;
    }