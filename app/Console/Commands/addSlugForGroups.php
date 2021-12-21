<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Group;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class addSlugForGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:add_slug';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'them slug cho cac group chua co';

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
     * @return int
     */
    public function handle()
    {
        $groups = Group::get();
        
        foreach($groups as $g){
            if(empty($g->slug)){
                $slug = SlugService::createSlug(Group::class, 'slug', $g->name, ['unique' => true]);
                Group::where('id', $g->id)->update(['slug'=>$slug]);
            }
            
        }
    }
}
