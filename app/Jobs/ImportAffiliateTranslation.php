<?php

namespace App\Jobs;

use App\Models\Translate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ImportAffiliateTranslation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $content;
    public $language;
    public $from;
    public $key;
    public function __construct($content,$key, $language, $from)
    {
        $this->content = $content;
        $this->key = $key;
        $this->language = $language;
        $this->from = $from;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checkExist = Translate::where('locale',$this->language)->where('key',$this->key)->first();
        if(!$checkExist)
        {
            $content = GoogleTranslate::trans($this->content, $this->language, 'en');
            Translate::create([
                'locale' => $this->language,
                'key' =>  $this->key,
                'text' => $content
           ]) ;
        }
    }
}
