USE reliefant;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS Doctors;
DROP TABLE IF EXISTS Patients;
DROP TABLE IF EXISTS Medication;
DROP TABLE IF EXISTS Medication_Intake;
DROP TABLE IF EXISTS Pain;
DROP TABLE IF EXISTS ForgotPassword;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE Doctors(
	UID INT NOT NULL AUTO_INCREMENT,
    national_id INT UNIQUE NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    email VARCHAR(50),
    password VARCHAR(50) NOT NULL,
    
    PRIMARY KEY(UID)
    );
    
CREATE TABLE Patients(
	UID INT NOT NULL AUTO_INCREMENT,
    national_id INT NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    doctor_uid INT NOT NULL,
    
    PRIMARY KEY(UID),
    FOREIGN KEY (doctor_uid) REFERENCES Doctors(UID)
    );
    
CREATE TABLE Medication(
	UID INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(40) NOT NULL,
    
    PRIMARY KEY(UID)
    );


CREATE TABLE Medication_Intake(
	patient_uid INT NOT NULL,
    medication_uid INT NOT NULL,
    dosage_mg INT NOT NULL,
    datetime DATETIME NOT NULL,
    
    FOREIGN KEY (patient_uid) REFERENCES Patients(UID),
    FOREIGN KEY (medication_uid) REFERENCES Medication(UID)
    );
    
CREATE TABLE Pain(
	patient_uid INT NOT NULL,
    pain_amount INT NOT NULL,
    datetime DATETIME NOT NULL,
    
	FOREIGN KEY (patient_uid) REFERENCES Patients(UID),
    CONSTRAINT Check_Pain_Level CHECK (pain_amount>=0 AND pain_amount<=10)
    );
    
CREATE TABLE ForgotPassword(
	email VARCHAR(50),
    code CHAR(32),
    issued_at TIMESTAMP
    )