<?php

use App\Preference;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->unique();
            $table->timestampsTz();
        });

        $preferences = [
            'projectId', 'projectName', 'projectOwnerName', 'status', 'projectType', 'LastModifiedDate',
            'TagTherapeuticArea', 'TagProduct', 'projectDescription', 'plannedExecutionYear', 'plannedYear',
            'projectDueDate', 'projectTeam', 'TagClientTactic', 'TagBUStakeholder', 'TagResearchType', 'TagMarket',
            'TagRespondentType', 'TagMethodology', 'hashtag', 'ProjectStage', 'projectStartDate', 'projectEndDate',
            'projectCloseDate', 'sapGrossPurchaseOrderTotal', 'sapActualSpend', 'estimatedProjectSpendInUSD',
            'perPage', 'sortBy', 'sortDesc'
        ];

        foreach ($preferences as $preference) {
            Preference::create([
                'value' => $preference,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preferences');
    }
}
