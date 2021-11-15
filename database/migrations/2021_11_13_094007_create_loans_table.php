<?php

use App\Models\Loan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('amount');
            $table->smallInteger('term');
            $table->tinyInteger('frequency')->default(Loan::FREQUENCY['weekly']);
            $table->tinyInteger('process_status')->default(Loan::PROCESS_STATUS['approved']);
            $table->tinyInteger('repayment_completed')->default(Loan::REPAYMENT_COMPLETED['not_yet']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
