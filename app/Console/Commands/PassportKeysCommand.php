<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Passport\Passport;

class PassportKeysCommand extends Command
{
    const MAP = [
        'PASSPORT_PRIVATE_KEY' => 'privateKey',
        'PASSPORT_PUBLIC_KEY' => 'publicKey',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:export-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export keys as base64 encoded';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        list($publicKey, $privateKey) = [
            Passport::keyPath('oauth-public.key'),
            Passport::keyPath('oauth-private.key'),
        ];

        if (!(file_exists($publicKey) || !file_exists($privateKey))) {
            $this->error('Keys do not exist');
            return 1;
        }

        $this->output->section('Production env vars:');

        foreach (self::MAP as $envName => $var) {
            $value = base64_encode(file_get_contents($$var));
            $this->line('<fg=yellow>'.$envName.'</>="'.$value.'"' . PHP_EOL);
        }

        return 0;
    }
}
