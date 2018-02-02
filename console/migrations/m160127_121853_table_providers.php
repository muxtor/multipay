<?php

use yii\db\Schema;
use yii\db\Migration;

class m160127_121853_table_providers extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS providers;

CREATE TABLE providers (
    id            INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY
    COMMENT 'Unique tree node identifier',
    root          INT(11)               DEFAULT NULL
    COMMENT 'Tree root identifier',
    lft           INT(11)      NOT NULL
    COMMENT 'Nested set left property',
    rgt           INT(11)      NOT NULL
    COMMENT 'Nested set right property',
    lvl           SMALLINT(5)  NOT NULL
    COMMENT 'Nested set level / depth',
    name          VARCHAR(60)  NOT NULL
    COMMENT 'The tree node name / label',
    icon          VARCHAR(255)          DEFAULT NULL
    COMMENT 'The icon to use for the node',
    icon_type     TINYINT(1)   NOT NULL DEFAULT '1'
    COMMENT 'Icon Type: 1 = CSS Class, 2 = Raw Markup',
    active        TINYINT(1)   NOT NULL DEFAULT TRUE
    COMMENT 'Whether the node is active (will be set to false on deletion)',
    selected      TINYINT(1)   NOT NULL DEFAULT FALSE
    COMMENT 'Whether the node is selected/checked by default',
    disabled      TINYINT(1)   NOT NULL DEFAULT FALSE
    COMMENT 'Whether the node is enabled',
    readonly      TINYINT(1)   NOT NULL DEFAULT FALSE
    COMMENT 'Whether the node is read only (unlike disabled - will allow toolbar actions)',
    visible       TINYINT(1)   NOT NULL DEFAULT TRUE
    COMMENT 'Whether the node is visible',
    collapsed     TINYINT(1)   NOT NULL DEFAULT FALSE
    COMMENT 'Whether the node is collapsed by default',
    movable_u     TINYINT(1)   NOT NULL DEFAULT TRUE
    COMMENT 'Whether the node is movable one position up',
    movable_d     TINYINT(1)   NOT NULL DEFAULT TRUE
    COMMENT 'Whether the node is movable one position down',
    movable_l     TINYINT(1)   NOT NULL DEFAULT TRUE
    COMMENT 'Whether the node is movable to the left (from sibling to parent)',
    movable_r     TINYINT(1)   NOT NULL DEFAULT TRUE
    COMMENT 'Whether the node is movable to the right (from sibling to child)',
    removable     TINYINT(1)   NOT NULL DEFAULT TRUE
    COMMENT 'Whether the node is removable (any children below will be moved as siblings before deletion)',
    removable_all TINYINT(1)   NOT NULL DEFAULT FALSE
    COMMENT 'Whether the node is removable along with descendants',
    KEY providers_NK1 (root),
    KEY providers_NK2 (lft),
    KEY providers_NK3 (rgt),
    KEY providers_NK4 (lvl),
    KEY providers_NK5 (active)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    AUTO_INCREMENT = 1;");

    }

    public function down()
    {
        $this->dropTable('providers');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
