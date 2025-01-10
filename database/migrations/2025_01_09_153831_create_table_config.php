<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('config', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); // Clave foránea después del campo id
            $table->string('MoneySymbol', 3); // Campo MoneySymbol de tipo varchar(3)
            $table->decimal('MoneyLowLimitToCreate', 10, 2); // Campo MoneyLowLimitToCreate de tipo decimal(10,2)
            $table->tinyInteger('MoneyAdaptLowValuesOnCreation')->default(0); // Campo MoneyAdaptLowValuesOnCreation de tipo tinyint(1) con valor por defecto 0
            $table->decimal('MoneyLimitThatNeedsAuthorization', 10, 2); // Campo MoneyLimitThatNeedsAuthorization de tipo decimal(10,2)
            $table->decimal('MoneyLimitAbsolute', 10, 2); // Campo MoneyLimitAbsolute de tipo decimal(10,2)
            $table->tinyInteger('MoneyLimitInTypeBets')->default(0); // Campo MoneyLimitInTypeBets de tipo tinyint(1) con valor por defecto 0
            $table->decimal('MoneyDenomination', 10, 2); // Campo MoneyDenomination de tipo decimal(10,2)
            $table->tinyInteger('RoundPartialPrizes')->default(1); // Campo RoundPartialPrizes de tipo tinyint(1) con valor por defecto 1
            $table->decimal('RoundPartialPrizesValue', 10, 2); // Campo RoundPartialPrizesValue de tipo decimal(10,2)
            $table->unsignedTinyInteger('NumberOfDigits')->default(8); // Campo NumberOfDigits de tipo int(2) unsigned con valor por defecto 8
            $table->tinyInteger('NewTicketNumberFormat')->default(0); // Campo NewTicketNumberFormat de tipo tinyint(1) con valor por defecto 0
            $table->string('HeaderOfTicketNumber', 8)->default(' '); // Campo HeaderOfTicketNumber de tipo varchar(8) con valor por defecto ''
            $table->unsignedInteger('HoursBetweenAutopurges')->default(4); // Campo HoursBetweenAutopurges de tipo int(10) unsigned con valor por defecto 4
            $table->unsignedInteger('NumberOfEventsToAutopurge')->default(9999); // Campo NumberOfEventsToAutopurge de tipo int(10) unsigned con valor por defecto 9999
            $table->unsignedInteger('DaysToAutopurgeIfEventOlderThan')->default(14); // Campo DaysToAutopurgeIfEventOlderThan de tipo int(10) unsigned con valor por defecto 14
            $table->timestamp('LastAutopurgeTimestamp')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo LastAutopurgeTimestamp de tipo timestamp con valor por defecto CURRENT_TIMESTAMP
            $table->string('AvatarsCachePath', 1024)->default('e:/'); // Campo AvatarsCachePath de tipo varchar(1024) con valor por defecto 'e:/'
            $table->tinyInteger('AdvancedGUI')->default(1); // Campo AdvancedGUI de tipo tinyint(1) con valor por defecto 1
            $table->tinyInteger('ForceAllowExports')->default(1); // Campo ForceAllowExports de tipo tinyint(1) con valor por defecto 1
            $table->dateTime('ExpirationDate')->default('0001-01-01 00:00:00'); // Campo ExpirationDate de tipo datetime con valor por defecto '0000-00-00 00:00:00'
            $table->timestamp('LastAutoexpireTimestamp')->default(DB::raw('CURRENT_TIMESTAMP')); // Campo LastAutoexpireTimestamp de tipo timestamp con valor por defecto CURRENT_TIMESTAMP
            $table->string('TITOTitle', 64)->default(''); // Campo TITOTitle de tipo varchar(64) con valor por defecto ''
            $table->string('TITOTicketType', 32)->default(''); // Campo TITOTicketType de tipo varchar(32) con valor por defecto ''
            $table->string('TITOStreet', 64)->default(''); // Campo TITOStreet de tipo varchar(64) con valor por defecto ''
            $table->string('TITOPlace', 32)->default(''); // Campo TITOPlace de tipo varchar(32) con valor por defecto ''
            $table->string('TITOCity', 32)->default(''); // Campo TITOCity de tipo varchar(32) con valor por defecto ''
            $table->string('TITOPostalCode', 8)->default(''); // Campo TITOPostalCode de tipo varchar(8) con valor por defecto ''
            $table->string('TITODescription', 8)->default(''); // Campo TITODescription de tipo varchar(8) con valor por defecto ''
            $table->tinyInteger('TITOExpirationType')->default(0); // Campo TITOExpirationType de tipo tinyint(1) con valor por defecto 0
            $table->unsignedInteger('NumberOfItemsPerPage')->default(20); // Campo NumberOfItemsPerPage de tipo int(10) unsigned con valor por defecto 20
            $table->string('BackupDBPath', 1024)->default('e:/MySQL/backup/'); // Campo BackupDBPath de tipo varchar(1024) con valor por defecto 'e:/MySQL/backup/'
            $table->unsignedInteger('HoursBetweenBackupDB')->default(6); // Campo HoursBetweenBackupDB de tipo int(10) unsigned con valor por defecto 6
            $table->unsignedInteger('DaysToKeepBackupDB')->default(7); // Campo DaysToKeepBackupDB de tipo int(10) unsigned con valor por defecto 7
            $table->decimal('Aux1Limit', 10, 2)->default(0.00); // Campo Aux1Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux2Limit', 10, 2)->default(0.00); // Campo Aux2Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux3Limit', 10, 2)->default(0.00); // Campo Aux3Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux4Limit', 10, 2)->default(0.00); // Campo Aux4Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux5Limit', 10, 2)->default(0.00); // Campo Aux5Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux6Limit', 10, 2)->default(0.00); // Campo Aux6Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux7Limit', 10, 2)->default(0.00); // Campo Aux7Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux8Limit', 10, 2)->default(0.00); // Campo Aux8Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux9Limit', 10, 2)->default(0.00); // Campo Aux9Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->decimal('Aux10Limit', 10, 2)->default(0.00); // Campo Aux10Limit de tipo decimal(10,2) con valor por defecto 0.00
            $table->text('HideOnTCFilter'); // Campo HideOnTCFilter de tipo text
            $table->string('ShowCloseOnlyFromIPs', 1024)->default('127.0.0.1'); // Campo ShowCloseOnlyFromIPs de tipo varchar(1024) con valor por defecto '127.0.0.1'
            $table->text('AllowIPs'); // Campo AllowIPs de tipo text
            $table->text('BanIPs'); // Campo BanIPs de tipo text
            $table->tinyInteger('AutoAddIPsToBan')->default(0); // Campo AutoAddIPsToBan de tipo tinyint(1) con valor por defecto 0
            $table->text('AllowMACs'); // Campo AllowMACs de tipo text
            $table->text('BanMACs'); // Campo BanMACs de tipo text
            $table->tinyInteger('AutoAddMACsToBan')->default(0); // Campo AutoAddMACsToBan de tipo tinyint(1) con valor por defecto 0
            $table->text('AllowTicketTypes'); // Campo AllowTicketTypes de tipo text
            $table->text('BanTicketTypes'); // Campo BanTicketTypes de tipo text
            $table->text('OnCloseTicketTypeFilter1'); // Campo OnCloseTicketTypeFilter1 de tipo text
            $table->text('OnCloseTicketTypeAllowIPs1'); // Campo OnCloseTicketTypeAllowIPs1 de tipo text
            $table->text('OnCloseTicketTypeBanIPs1'); // Campo OnCloseTicketTypeBanIPs1 de tipo text
            $table->tinyInteger('OnCloseTicketTypeIPCreation1')->default(0); // Campo OnCloseTicketTypeIPCreation1 de tipo tinyint(1) con valor por defecto 0
            $table->text('OnCloseTicketTypeFilter2'); // Campo OnCloseTicketTypeFilter2 de tipo text
            $table->text('OnCloseTicketTypeAllowIPs2'); // Campo OnCloseTicketTypeAllowIPs2 de tipo text
            $table->text('OnCloseTicketTypeBanIPs2'); // Campo OnCloseTicketTypeBanIPs2 de tipo text
            $table->tinyInteger('OnCloseTicketTypeIPCreation2')->default(0); // Campo OnCloseTicketTypeIPCreation2 de tipo tinyint(1) con valor por defecto 0
            $table->text('OnCloseTicketTypeFilter3'); // Campo OnCloseTicketTypeFilter3 de tipo text
            $table->text('OnCloseTicketTypeAllowIPs3'); // Campo OnCloseTicketTypeAllowIPs3 de tipo text
            $table->text('OnCloseTicketTypeBanIPs3'); // Campo OnCloseTicketTypeBanIPs3 de tipo text
            $table->tinyInteger('OnCloseTicketTypeIPCreation3')->default(0); // Campo OnCloseTicketTypeIPCreation3 de tipo tinyint(1) con valor por defecto 0
            $table->text('OnCloseTicketTypeFilter4'); // Campo OnCloseTicketTypeFilter4 de tipo text
            $table->text('OnCloseTicketTypeAllowIPs4'); // Campo OnCloseTicketTypeAllowIPs4 de tipo text
            $table->text('OnCloseTicketTypeBanIPs4'); // Campo OnCloseTicketTypeBanIPs4 de tipo text
            $table->tinyInteger('OnCloseTicketTypeIPCreation4')->default(0); // Campo OnCloseTicketTypeIPCreation4 de tipo tinyint(1) con valor por defecto 0
            $table->text('OnCloseTicketTypeFilter5'); // Campo OnCloseTicketTypeFilter5 de tipo text
            $table->text('OnCloseTicketTypeAllowIPs5'); // Campo OnCloseTicketTypeAllowIPs5 de tipo text
            $table->text('OnCloseTicketTypeBanIPs5'); // Campo OnCloseTicketTypeBanIPs5 de tipo text
            $table->tinyInteger('OnCloseTicketTypeIPCreation5')->default(0); // Campo OnCloseTicketTypeIPCreation5 de tipo tinyint(1) con valor por defecto 0
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
