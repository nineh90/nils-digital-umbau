<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Zweites Preismodell: dieselbe Leistung, monatlich statt auf einmal.
 *
 * price und unit bleiben unverändert und tragen weiter den Festpreis – das
 * Abo tritt daneben, nicht an seine Stelle. Beides zusammen ergibt auf der
 * Leistungsseite die zwei Ansichten desselben Angebots; eine Leistung ohne
 * monthly_price erscheint schlicht nur in der einmaligen Ansicht.
 *
 * Warum vier Spalten und nicht eine Zahl: ein Monatspreis allein ist keine
 * Aussage. Ohne Laufzeit weiss niemand, worauf er sich einlässt, ohne
 * renewal_price sieht es aus, als liefe der Einstiegspreis ewig weiter, und
 * ohne setup_fee liesse sich das grössere Projekt nicht abbilden, bei dem
 * eine Anzahlung nötig ist. Genau diese vier Angaben stehen auch im Vertrag.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('monthly_price')->nullable()->after('unit');
            $table->unsignedSmallInteger('term_months')->nullable()->after('monthly_price');
            $table->unsignedInteger('renewal_price')->nullable()->after('term_months');
            $table->unsignedInteger('setup_fee')->nullable()->after('renewal_price');
            $table->text('subscription_includes')->nullable()->after('setup_fee');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'monthly_price',
                'term_months',
                'renewal_price',
                'setup_fee',
                'subscription_includes',
            ]);
        });
    }
};
