<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailSetupPassword;
use App\Models\User;
use Illuminate\Console\Command;

class SendSetupEmailsToImportedUsers extends Command
{
    protected $signature = 'users:send-setup-emails
                            {--dry-run : Preview without sending}
                            {--ids=* : Target specific user IDs only}';

    protected $description = 'Send password setup emails to users who have not set a password yet';

    public function handle(): int
    {
        $query = User::whereNull('password_set_at');

        if ($this->option('ids')) {
            $query->whereIn('id', $this->option('ids'));
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->info('No users found without a password.');
            return self::SUCCESS;
        }

        $this->info("Found {$users->count()} user(s) without a password set.");
        $this->newLine();

        $this->table(
            ['ID', 'Name', 'Email', 'Created At'],
            $users->map(fn ($u) => [
                $u->id,
                $u->name,
                $u->email,
                $u->created_at->format('d M Y'),
            ])
        );

        if ($this->option('dry-run')) {
            $this->warn('Dry run — no emails sent.');
            return self::SUCCESS;
        }

        if (!$this->confirm("Send setup emails to all {$users->count()} user(s)?")) {
            $this->info('Aborted.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $failed = [];

        foreach ($users as $user) {
            try {
                SendEmailSetupPassword::dispatch($user);
            } catch (\Throwable $e) {
                $failed[] = "{$user->email}: {$e->getMessage()}";
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $dispatched = $users->count() - count($failed);
        $this->info("✓ Dispatched {$dispatched} email(s) to the queue.");

        if (!empty($failed)) {
            $this->warn('Failed:');
            foreach ($failed as $msg) {
                $this->line("  - {$msg}");
            }
        }

        return self::SUCCESS;
    }
}
