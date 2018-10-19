<?php
declare(strict_types=1);

namespace App\Migrations;

class Migration19_19_2018_7_31_31 extends \Afw\Component\Migration\Migration
{
    public function up()
    {
        $this->addExpression("
            create table test
            (
                id uuid not null
                    constraint test_pk
                        primary key,
                name varchar(255) not null,
                lastname varchar(255),
                email varchar(255) not null,
                nickname varchar(255) not null,
                password text
            )
            ;
        ");
    }


    public function down()
    {
        $this->addExpression("DROP TABLE test");
    }
}
