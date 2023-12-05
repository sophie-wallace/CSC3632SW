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

//$encrypted = encrypt('hello', '810605936175014107512837821771485828141'); // this yields a binary string

echo decrypt('Mwf8c5KUdcpXKIpFGBGSTiimNQ3Yo2t92JA4uutkn7qTXWMhoVfNHousYfJN+NgcJ36IcXJVwG3azUBILHsvXTrt99D/1P4PrAAoE579le0=
', '810605936175014107512837821771485828141');
// decrypt($encrypted, 'wrong password') === null
