<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

/**
 * Dumps the default MySQL/MariaDB database to a gzipped SQL file.
 *
 * scripts/deploy.sh runs this before every migration so a deploy can always
 * be undone. The password reaches mysqldump through MYSQL_PWD, never argv.
 */
class BackupDatabase extends Command
{
    protected $signature = 'db:backup
        {--path= : Directory to write the dump to (default: ~/db-backups)}
        {--prefix=just-lite-deploy : File name prefix; only files with this prefix are pruned}
        {--keep=20 : Number of dumps with this prefix to keep, 0 keeps them all}';

    protected $description = 'Dump the database to a gzipped SQL file and prune old dumps';

    public function handle(): int
    {
        $connection = config('database.connections.'.config('database.default'));

        if (! in_array($connection['driver'] ?? null, ['mysql', 'mariadb'], true)) {
            $this->error('db:backup only supports MySQL/MariaDB connections.');

            return self::FAILURE;
        }

        $prefix = (string) $this->option('prefix');

        if (! preg_match('/^[A-Za-z0-9._-]+$/', $prefix)) {
            $this->error('The prefix may only contain letters, digits, dots, dashes and underscores.');

            return self::FAILURE;
        }

        $directory = rtrim((string) ($this->option('path') ?: $this->homeDirectory().'/db-backups'), '/');
        File::ensureDirectoryExists($directory, 0700);

        $file = $directory.'/'.$prefix.'-'.now()->format('Ymd-His').'.sql.gz';

        $result = Process::timeout(600)
            ->env([
                'DB_HOST' => (string) ($connection['host'] ?? '127.0.0.1'),
                'DB_PORT' => (string) ($connection['port'] ?? '3306'),
                'DB_SOCKET' => (string) ($connection['unix_socket'] ?? ''),
                'DB_USER' => (string) $connection['username'],
                'DB_NAME' => (string) $connection['database'],
                'MYSQL_PWD' => (string) $connection['password'],
                'OUT' => $file,
            ])
            ->run(['bash', '-c', <<<'BASH'
                set -euo pipefail
                umask 077
                args=(--single-transaction --quick --routines --triggers --no-tablespaces
                      --default-character-set=utf8mb4 --user="$DB_USER")
                if [ -n "$DB_SOCKET" ]; then
                    args+=(--socket="$DB_SOCKET")
                else
                    args+=(--host="$DB_HOST" --port="$DB_PORT")
                fi
                mysqldump "${args[@]}" "$DB_NAME" | gzip -9 > "$OUT"
                gzip -t "$OUT"
                BASH]);

        if ($result->failed()) {
            File::delete($file);
            $this->error('Database backup failed: '.trim($result->errorOutput() ?: $result->output()));

            return self::FAILURE;
        }

        $this->info("Database backed up to {$file} (".number_format(filesize($file) / 1024, 1).' KB)');

        $this->prune($directory, $prefix, (int) $this->option('keep'));

        return self::SUCCESS;
    }

    private function prune(string $directory, string $prefix, int $keep): void
    {
        if ($keep <= 0) {
            return;
        }

        $dumps = glob($directory.'/'.$prefix.'-*.sql.gz') ?: [];
        rsort($dumps); // Timestamped names: newest first.

        foreach (array_slice($dumps, $keep) as $old) {
            File::delete($old);
            $this->line('Pruned old backup '.basename($old));
        }
    }

    private function homeDirectory(): string
    {
        $home = getenv('HOME') ?: (function_exists('posix_getpwuid') ? (posix_getpwuid(posix_geteuid())['dir'] ?? '') : '');

        return $home !== '' ? $home : storage_path('app');
    }
}
