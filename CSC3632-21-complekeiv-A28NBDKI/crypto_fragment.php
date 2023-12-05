<?php

/**
 * BORROWED FROM https://stackoverflow.com/a/46872528
 */
function encrypt($plaintext, $password) {
    $method = "AES-256-CBC";
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);

    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    $hash = hash_hmac('sha256', $ciphertext, $key, true);

    return base64_encode($iv . $hash . $ciphertext);
}
// ---------------------------------------------------------------------

function decrypt($ivHashCiphertext, $password, $ignore_error = false) {
    $data = base64_decode($ivHashCiphertext, TRUE);
    if ($data === FALSE) {
        die("Unable to understand data");
    }
    $method = "AES-256-CBC";
    $iv = substr($data, 0, 16);
    $hash = substr($data, 16, 32);
    $ciphertext = substr($data, 48);
    $key = hash('sha256', $password, true);

    if (hash_hmac('sha256', $ciphertext, $key, true) !== $hash) {
      if ($ignore_error) {
        return "";
      } else {
        die("Unable to decrypt\n");
      }
    }

    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}

echo decrypt('ZhVDAWoYxpybGkUH8MKc+iROiJ0srkAlCJyqQw1CfcGGsJ+ot3ZaQ6M+sHWaoYLeHA3WHrSxNQUTtQCBaFqQqzxGQveEOr9RAET/VWDQofY=', '199314419368948755112395064631155946018');
// decrypt($encrypted, 'wrong password') === null



