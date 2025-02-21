<?php
namespace Lib;

class ResponseHttp
{
    public static function statusMessage(int $code, string $message): string
    {
        return json_encode([
            'status' => $code,
            'message' => $message
        ]);
    }
}