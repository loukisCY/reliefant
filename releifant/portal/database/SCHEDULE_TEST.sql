use reliefant;

DROP EVENT IF EXISTS clean_forogt_password_table;

CREATE EVENT clean_forogt_password_table
ON SCHEDULE EVERY 5 MINUTE
ON COMPLETION PRESERVE
DO
   DELETE FROM ForgotPassword WHERE TIMESTAMPDIFF(HOUR, issued_at, NOW()) >= 1;