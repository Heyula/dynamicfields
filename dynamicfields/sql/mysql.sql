CREATE TABLE dynamicfields_fields (
    field_id INT(11) NOT NULL AUTO_INCREMENT,
    module_dirname VARCHAR(50) NOT NULL,
    field_name VARCHAR(100) NOT NULL,
    field_label VARCHAR(255) NOT NULL,
    field_type VARCHAR(50) NOT NULL,
    field_default TEXT,
    field_required TINYINT(1) DEFAULT 0,
    PRIMARY KEY (field_id)
) ENGINE=InnoDB;