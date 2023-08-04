<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        /**
        create table wpl_cetateni_accounts
        (
        id_cetatean                   bigint      default nextval('public.cetateni_accounts_seq'::regclass) not null
        constraint cetateni_accounts
        primary key,
        oauth_provider                varchar(255),
        oauth_provider_id             varchar(255),
        name                          varchar(255)                                                          not null,
        email                         varchar(255)                                                          not null,
        active                        smallint    default 1                                                 not null,
        password                      varchar(60),
        avatar                        varchar(255),
        email_verified                varchar(255)                                                          not null,
        email_verification_code       varchar(60),
        remember_token                varchar(100),
        created_at                    timestamp,
        updated_at                    timestamp,
        nume                          text,
        prenume                       text                                                                  not null,
        telefon                       text                                                                  not null,
        notificari                    smallint    default 1,
        phone_verification            timestamp,
        cnp                           text,
        account_validated             smallint    default 0,
        account_validated_user_id     integer,
        account_validated_date        timestamp,
        account_invalidated_user_id   integer,
        account_invalidated_date      timestamp,
        account_invalidated_obs       text,
        f_nume                        varchar(255),
        iso_code                      varchar(30) default 'ro'::character varying,
        dial_code                     varchar(30) default '+40'::character varying,
        international_number          varchar(30),
        allow_blocari_auto            smallint    default 0,
        tip_persoana                  smallint    default 1,
        ci_serie                      varchar(20),
        ci_numar                      varchar(20),
        f_reg_com                     text,
        email_verification            timestamp,
        account_invalidated_user_type smallint    default 0,
        account_invalidated_user_name text
        );

        comment on column wpl_cetateni_accounts.allow_blocari_auto is '0 => not, 1 => yes';

        comment on column wpl_cetateni_accounts.tip_persoana is '1 => PF
        2 => PJ';

        comment on column wpl_cetateni_accounts.account_invalidated_user_type is '0 => not processed
        1 => canceled by agent
        2 => canceled by cetatean/user';

         */
        try {
            Schema::create('wpl_cetateni_accounts', function (Blueprint $table) {
                $table->id('id_cetatean');
                $table->string('oauth_provider')->nullable();
                $table->string('oauth_provider_id')->nullable();
                $table->string('name');
                $table->string('email');
                $table->smallInteger('active')->default(1);
                $table->string('password', 60);
                $table->smallInteger('avatar')->default(1);
                $table->string('email_verified');
                $table->string('email_verification_code', 60)->nullable();
                $table->string('remember_token', 100)->nullable();
                $table->text('nume')->nullable();
                $table->text('prenume')->nullable();
                $table->text('telefon')->nullable();
                $table->smallInteger('notificari')->default(1);
                $table->timestamp('phone_verification')->nullable();
                $table->text('cnp')->nullable();
                $table->smallInteger('account_validated')->default(0);
                $table->integer('account_validated_user_id')->nullable();
                $table->timestamp('account_validated_date')->nullable();
                $table->integer('account_invalidated_user_id')->nullable();
                $table->timestamp('account_invalidated_date')->nullable();
                $table->text('account_invalidated_obs')->nullable();
                $table->string('f_nume')->nullable();
                $table->string('iso_code', 30)->default('ro');
                $table->string('dial_code', 30)->default('+40');
                $table->string('international_number', 30)->nullable();
                $table->smallInteger('allow_blocari_auto')->default(0);
                $table->smallInteger('tip_persoana')->default(1)->comment('1 => PF / 2 => PJ');
                $table->string('ci_serie', 20)->nullable();
                $table->string('ci_numar', 20)->nullable();
                $table->text('f_reg_com')->nullable();
                $table->timestamp('email_verification')->nullable();
                $table->string('account_invalidated_user_type')->default(0)->comment('0 => not processed / 1 => canceled by agent / 2 => canceled by cetatean/user');
                $table->text('account_invalidated_user_name')->nullable();
                $table->timestamps();
            });
        } catch (Exception $e) {
            dump((string)$e);
            $this->down();
        }
    }

    public function down(): void
    {
//        Schema::drop('wpl_citizen_accounts');
    }
};
