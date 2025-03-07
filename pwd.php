<?php
// 密钥
$Sw = "qzkj1kjghd=876&*";

// 模拟 U 函数的部分功能（简化实现）
function U($data) {
    if (is_array($data)) {
        $result = [];
        foreach ($data as $key => $value) {
            if (is_string($key) && preg_match('/[^\w$]/', $key)) {
                $result[] = json_encode($key) . ": " . U($value);
            } else {
                $result[] = $key . ": " . U($value);
            }
        }
        return "{" . implode(", ", $result) . "}";
    } elseif (is_object($data)) {
        $properties = get_object_vars($data);
        $result = [];
        foreach ($properties as $key => $value) {
            if (is_string($key) && preg_match('/[^\w$]/', $key)) {
                $result[] = json_encode($key) . ": " . U($value);
            } else {
                $result[] = $key . ": " . U($value);
            }
        }
        return "{" . implode(", ", $result) . "}";
    } elseif (is_string($data)) {
        return json_encode($data);
    } elseif (is_numeric($data)) {
        return (string)$data;
    } elseif (is_bool($data)) {
        return $data ? 'true' : 'false';
    } elseif (is_null($data)) {
        return 'null';
    }
    return json_encode($data);
}

// 加密函数
function encryptPassword($password, $key) {
    // 确保密钥长度符合 AES-128 要求（16 字节）
    $key = substr(str_pad($key, 16, "\0"), 0, 16);

    // 调用 U 函数处理密码
    $processedPassword = U($password);

    // 对密码进行 AES-128-ECB 加密，使用 PKCS7 填充
    $encrypted = openssl_encrypt($processedPassword, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);

    // 对加密结果进行 Base64 编码
    $base64Encoded = base64_encode($encrypted);

    return $base64Encoded;
}

// 示例密码
$password = "123456";

// 调用加密函数
$encryptedPassword = encryptPassword($password, $Sw);



// 输出加密后的密码
echo '加密后的密码：  ';
echo $encryptedPassword;

// 输出加密并Base64 编码后的密码
echo '加密并Base64 编码后的密码：  ';
$pwd = base64_encode($encryptedPassword);
echo $pwd;


?>
