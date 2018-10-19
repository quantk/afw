<?php
declare(strict_types=1);

namespace App\Migrations;

class Migration18_18_2018_21_03_23 extends \Afw\Component\Migration\Migration
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
