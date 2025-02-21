<?php
namespace Lib;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Models\User;
use Services\UserService;

class Security
{
    final public static function encryptPassw(string $passw): string
    {
        return password_hash($passw, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    final public static function validatePassw(string $passw, string $passwhash): bool
    {
        return password_verify($passw, $passwhash);
    }

    public static function getSecretKey(): string
    {
        return $_ENV['SECRET_KEY_SECURITY'];
    }

    final public static function createToken(string $key, array $data): string
    {
        $time = time();
        $token = [
            'iat' => $time,
            'exp' => $time + 3600,
            'data' => $data
        ];
        return JWT::encode($token, $key, 'HS256');
    }

    final public static function getToken(): ?object
    {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            throw new \Exception('Acceso denegado', 403);
        }
        try {
            $authorization = explode(' ', $headers['Authorization']);
            if (count($authorization) < 2) {
                throw new \Exception('Formato de token inválido', 401);
            }
            $token = $authorization[1];
            return JWT::decode($token, new Key(self::getSecretKey(), 'HS256'));
        } catch (\Exception $e) {
            throw new \Exception('Token expirado o inválido', 401);
        }
    }

    final public static function verifyToken(string $token, string $userToken): bool
    {
        return $userToken === $token;
    }

    /**
     * Verifica el token de activación de email.
     *
     * @param string $token
     * @return string|null El email decodificado si el token es válido, null si es inválido.
     */
    public static function verifyEmailToken(string $token): ?string
    {
        try {
            // Decodificar el token
            $decoded = JWT::decode($token, new Key(self::getSecretKey(), 'HS256'));
            // Retornar el email del token decodificado
            return $decoded->data->email ?? null;
        } catch (\Exception $e) {
            // Si el token es inválido o expirado
            throw new \Exception('Token inválido o expirado: ' . $e->getMessage());
        }
    }

    /**
     * Activa la cuenta de usuario a partir del token.
     *
     * @param string $token
     * @param UserService $userService
     * @return bool Devuelve verdadero si el usuario fue activado con éxito, falso si no se encontró.
     */
    public static function activateUserFromToken(string $token, UserService $userService): bool
    {
        $email = self::verifyEmailToken($token);
        if ($email) {
            // Buscar el usuario por el email
            $user = $userService->getUserByEmail($email);
            if ($user) 
            {
                // Activar al usuario
                $userService->activateUser($user);
                return true; // Usuario activado correctamente
            }
        }
        return false; // No se encontró el usuario o el token fue inválido
    }
}
