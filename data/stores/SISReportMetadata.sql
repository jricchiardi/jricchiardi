CREATE TABLE sis_report_metadata(
    code VARCHAR(50) not null,
    value VARCHAR(255) not null default ''
);
INSERT INTO sis_report_metadata VALUES ('last_exec', CURRENT_TIMESTAMP);
-- INSERT INTO sis_report_metadata VALUES ('is_running', CURRENT_TIMESTAMP);