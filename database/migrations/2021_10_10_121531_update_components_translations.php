<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\ProjectElementComponent;
class UpdateComponentsTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $arr = [];
        $arr["Brief"] = 'brief';
        $arr["Koncept kreatywny"] = 'creative_concept';
        $arr["Scenariusz"] = 'scenario';
        $arr["Storyboard"] = 'storyboard';
        $arr["Nagrania"] = 'recordings';
        $arr["Animacja"] = 'animation';
        $arr["Film"] = 'movie';

        $pec = ProjectElementComponent::all();

        foreach($pec as $p) {
            if(array_key_exists($p->name, $arr)) {
                $p->name = $arr[$p->name];
                $p->save();
            }
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
