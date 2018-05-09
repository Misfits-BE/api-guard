<?php

namespace Misfits\ApiGuard\Console\Commands;

use Illuminate\Console\Command;
use Misfits\ApiGuard\Models\ApiKey;

/**
 * Class GenerateApiKey
 *
 * @author   Tim Joosten    <https://www.github.com/Tjoosten>
 * @author   Chris Bautista <https://github.com/chrisbjr>
 * @license  https://github.com/Misfits-BE/api-guard/blob/master/LICENSE.md - MIT license
 * @package  Misfits\ApiGuard\Console\Commands
 */
class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api-key:generate
                            {--id= : ID of the model you want to bind to this API key}
                            {--type= : The class name of the model you want to bind to this API key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an API key';

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
        $apiKeyableId = $this->option('id');
        $apiKeyableType = $this->option('type');

        $apiKey = new ApiKey([
            'key'             => ApiKey::generateKey(),
            'apikeyable_id'   => $apiKeyableId,
            'apikeyable_type' => $apiKeyableType,
        ]);

        $apiKey->save();

        $this->info('An API key was created with the following key: ' . $apiKey->key);

        return;
    }
}
