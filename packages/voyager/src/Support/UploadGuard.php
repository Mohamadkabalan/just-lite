<?php

namespace TCG\Voyager\Support;

use Symfony\Component\Mime\MimeTypes;

/**
 * PATCH 1.8.0.3 (CVE-2024-55417): uploads land in the public storage disk,
 * which the web server serves directly. A file whose name carries a
 * server-side script extension anywhere (x.php, x.php.jpg with Apache's
 * AddHandler, x.phtml, ...) could be executed there.
 */
class UploadGuard
{
    private const SCRIPT_EXTENSION = '/\.(php\d*|phtml|phar|pht|phps|pl|py|cgi|sh|asp|aspx|jsp|shtml|htaccess|htpasswd|user\.ini)(\.|$)/i';

    public static function isScriptFileName(string $fileName): bool
    {
        $baseName = basename($fileName);

        // Dotfiles such as .htaccess or .user.ini change how the server treats the folder.
        return str_starts_with($baseName, '.') || (bool) preg_match(self::SCRIPT_EXTENSION, $baseName);
    }

    /**
     * Whether the extension is one that the allowed MIME types use.
     *
     * @param  array<int, string>  $allowedMimeTypes
     */
    public static function extensionMatchesMimeTypes(string $extension, array $allowedMimeTypes): bool
    {
        $mimeTypes = MimeTypes::getDefault();

        foreach ($allowedMimeTypes as $mimeType) {
            if (in_array(strtolower($extension), $mimeTypes->getExtensions($mimeType), true)) {
                return true;
            }
        }

        return false;
    }
}
