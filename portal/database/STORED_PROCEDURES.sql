USE reliefant;

DROP PROCEDURE IF EXISTS Login;
DROP PROCEDURE IF EXISTS GetPatients;
DROP PROCEDURE IF EXISTS GetPatientPreview;
DROP PROCEDURE IF EXISTS GetMedicationIntake;
DROP PROCEDURE IF EXISTS GetPain;
DROP PROCEDURE IF EXISTS GetTotalMedicationWithPain;
DROP PROCEDURE IF EXISTS GenerateForgotPasswordCode;
DROP PROCEDURE IF EXISTS ResetPassword;
DROP PROCEDURE IF EXISTS AveragePain;

DELIMITER $$
CREATE PROCEDURE Login (email varchar(50), password varchar(50))
BEGIN
SELECT UID, first_name, last_name, email
FROM Doctors D WHERE D.email = email AND D.password = PASSWORD(password);
END
$$

CREATE PROCEDURE GetPatients (doc_uid int)
BEGIN
SELECT * 
FROM Patients P
WHERE P.doctor_uid = doc_uid
ORDER BY first_name, last_name;
END
$$

CREATE PROCEDURE GetPatientPreview (doc_uid int, pat_uid int)
BEGIN
SELECT *
FROM Patients P
WHERE P.doctor_uid = doc_uid AND P.UID = pat_uid;
END
$$

CREATE PROCEDURE GetMedicationIntake (pat_uid int)
BEGIN
SELECT P.UID, P.first_name, P.last_name, M.name, M_I.dosage_mg, M_I.datetime
FROM Medication_Intake M_I, Medication M, Patients P
WHERE P.UID = M_I.patient_uid AND M_I.medication_uid = M.uid AND pat_uid = P.UID
ORDER BY M_I.datetime DESC;
END
$$

CREATE PROCEDURE GetPain (pat_uid int, lim int)
BEGIN
SELECT P.UID, P.first_name, P.last_name, P_N.pain_amount, DATE(P_N.datetime) AS datetime
FROM Pain P_N, Patients P
WHERE P.UID = P_N.patient_uid AND P.UID = pat_uid
ORDER BY P_N.datetime DESC
LIMIT 0, lim;
END
$$

-- CHANGE LATER TO CHOOSE DATES 
CREATE PROCEDURE AveragePain (pat_uid int)
BEGIN
SELECT patient_uid, ROUND(AVG(pain_amount), 2) as pain_amount
FROM Pain
WHERE patient_uid = pat_uid
GROUP BY patient_uid;
END
$$

CREATE PROCEDURE GetTotalMedicationWithPain (pat_uid int)
BEGIN
SELECT DATE(P_N.datetime) AS Date, P.UID, P.first_name, P.last_name,
	   GROUP_CONCAT(M.name SEPARATOR ', ') AS 'Medication taken', SUM(M_I.dosage_mg) AS 'Total amount of meds taken in mg',
	   ROUND(AVG(P_N.pain_amount), 2) AS 'Average Pain'
FROM Pain P_N, Patients P, Medication_Intake M_I, Medication M
WHERE P.UID = P_N.patient_uid AND P.UID = M_I.patient_uid AND M_I.medication_uid = M.uid AND pat_uid = P.UID
		AND DATE(M_I.datetime) = DATE(P_N.datetime)
GROUP BY DATE(M_I.datetime)
ORDER BY P_N.datetime DESC;
END
$$

CREATE PROCEDURE GenerateForgotPasswordCode (doc_email varchar(50))
BEGIN
IF EXISTS(
	SELECT * FROM Doctors D WHERE D.email = doc_email
    )
THEN
	DELETE FROM ForgotPassword WHERE email = doc_email;
	INSERT INTO ForgotPassword(email, code, issued_at) VALUES (doc_email, FLOOR(RAND() * 999999) + 100000, NOW());
    SELECT email, code FROM ForgotPassword F_P WHERE F_P.email = doc_email;
END IF;
END
$$

CREATE PROCEDURE ResetPassword (doc_email varchar(50), pass varchar(50), code char(32))
BEGIN
IF EXISTS (
	SELECT * 
    FROM ForgotPassword F_P
    WHERE F_P.code = code AND doc_email = F_P.email
    ) AND LENGTH(pass) >= 8
THEN
	DELETE FROM ForgotPassword WHERE email = doc_email;
	UPDATE Doctors D 
	SET password = PASSWORD(pass)
	WHERE D.email = doc_email;
    SELECT 'success';
END IF;
END
$$

