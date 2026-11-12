<?php

namespace Copona\Security;

/**
 * Encrypts individual DB fields (e.g. affiliate bank details) with the key in
 * APP_KEY (.env), so a database dump or SQL injection alone doesn't reveal
 * them. The key must not live in the database.
 *
 * Format: "enc:v1:" . base64(nonce . secretbox(plaintext)) using libsodium
 * (XSalsa20-Poly1305, authenticated). Values without the prefix are legacy
 * plaintext and are returned unchanged, so existing rows keep working until
 * they are re-saved or migrated.
 */
final class FieldCipher
{
    public const PREFIX = 'enc:v1:';

    /**
     * Affiliate payout fields stored encrypted.
     */
    public const AFFILIATE_FIELDS = [
        'bank_name',
        'bank_branch_number',
        'bank_swift_code',
        'bank_account_name',
        'bank_account_number',
    ];

    /**
     * New random key in the APP_KEY format.
     */
    public static function generateKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
    }

    /**
     * Raw 32-byte key from APP_KEY, or null when it is missing or malformed.
     */
    public static function key(): ?string
    {
        $value = $_ENV['APP_KEY'] ?? getenv('APP_KEY');

        if (!is_string($value) || $value === '') {
            return null;
        }

        if (str_starts_with($value, 'base64:')) {
            $value = base64_decode(substr($value, 7), true);
        }

        return (is_string($value) && strlen($value) === SODIUM_CRYPTO_SECRETBOX_KEYBYTES) ? $value : null;
    }

    public static function isEncrypted($value): bool
    {
        return is_string($value) && str_starts_with($value, self::PREFIX);
    }

    /**
     * Encrypts $value. Empty values stay empty. Without a valid APP_KEY the
     * value is stored as-is (legacy behaviour) rather than lost.
     */
    public static function encrypt($value): string
    {
        $value = (string)$value;
        $key = self::key();

        if ($value === '' || $key === null || self::isEncrypted($value)) {
            return $value;
        }

        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        return self::PREFIX . base64_encode($nonce . sodium_crypto_secretbox($value, $nonce, $key));
    }

    /**
     * Decrypts a value from encrypt(). Plaintext (legacy) values are returned
     * unchanged; a value that can't be decrypted (wrong/missing key, tampered)
     * comes back as an empty string.
     */
    public static function decrypt($value): string
    {
        $value = (string)$value;

        if (!self::isEncrypted($value)) {
            return $value;
        }

        $key = self::key();
        $raw = base64_decode(substr($value, strlen(self::PREFIX)), true);

        if ($key === null || $raw === false || strlen($raw) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES) {
            return '';
        }

        $plain = sodium_crypto_secretbox_open(
            substr($raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES),
            substr($raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES),
            $key
        );

        return $plain === false ? '' : $plain;
    }

    /**
     * Decrypts the given fields of a DB row in place (missing fields ignored).
     */
    public static function decryptFields(array $row, array $fields): array
    {
        foreach ($fields as $field) {
            if (array_key_exists($field, $row)) {
                $row[$field] = self::decrypt($row[$field]);
            }
        }

        return $row;
    }
}
