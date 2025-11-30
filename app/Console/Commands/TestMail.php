<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : The recipient email address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the configured mail credentials by sending a raw email.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = $this->argument('email') ?? config('mail.from.address');

        if (!$recipient) {
            $this->error('No recipient specified and no "from" address configured in config/mail.php.');
            return 1;
        }

        $this->info("---------------------------------------");
        $this->info("  Mail Configuration Check");
        $this->info("---------------------------------------");

        // Display current config (masked for security)
        $transport = config('mail.default');
        $this->line("Transport: <comment>{$transport}</comment>");

        if ($transport === 'smtp') {
            $this->line("Host:      <comment>" . config('mail.mailers.smtp.host') . "</comment>");
            $this->line("Port:      <comment>" . config('mail.mailers.smtp.port') . "</comment>");
            $this->line("Encryption:<comment>" . (config('mail.mailers.smtp.encryption') ?: 'none') . "</comment>");
            $this->line("Username:  <comment>" . (config('mail.mailers.smtp.username') ?: 'null') . "</comment>");
        }

        $this->newLine();
        $this->info("Attempting to send test email to: <comment>{$recipient}</comment>...");

        try {
            // We use Mail::raw to avoid creating a Mailable class for this simple test
            // We force immediate sending (no queue) to catch exceptions
            Mail::raw("This is a test email from your Laravel application.\n\nTimestamp: " . now(), function ($message) use ($recipient) {
                $message->to($recipient)
                    ->subject('Laravel Mail Connection Test');
            });

            $this->newLine();
            $this->info('✅ Success! Email was sent successfully.');
            $this->line('Check your inbox (and spam folder) to verify delivery.');
            return 0;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Connection Failed.');

            $this->newLine();
            $this->line('<bg=red;fg=white> Error Details: </>');
            $this->line($e->getMessage());

            return 1;
        }
    }
}
