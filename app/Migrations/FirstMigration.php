<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 12:27
 */

namespace App\Migrations;


use Afw\Component\Migration\Migration;

class FirstMigration extends Migration
{
    public function up()
    {
        $this->addExpression("
            create table users
            (
                id uuid not null
                    constraint users_pk
                        primary key,
                name varchar(255) not null,
                lastname varchar(255),
                email varchar(255) not null,
                nickname varchar(255) not null,
                password text
            )
            ;
        ");
        $this->addExpression("
            create unique index users_email_uindex
                on users (email)
            ;
        ");
        $this->addExpression("
            create unique index users_nickname_uindex
                on users (nickname)
            ;
        ");
    }

    public function down()
    {
        $this->addExpression("DROP TABLE users");
    }
}