
CREATE TABLE building_info (
    building VARCHAR(255) NOT NULL,
    floor VARCHAR(255) NOT NULL,
    room_number INT NOT NULL,
    PRIMARY KEY(room_number)
);

INSERT INTO building_info VALUES
('Main Building','1st Floor',101),
('Engineering Building','2nd Floor',201),
('Science Building','3rd Floor',301),
('Library','4th Floor',401),
('Student Center','5th Floor',501);

-- ======================
-- ADMIN
-- ======================
CREATE TABLE admin (
    adminID VARCHAR(255) PRIMARY KEY,
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    pass VARCHAR(255),
    role VARCHAR(255)
);

INSERT INTO admin VALUES
('ADM001','Admin','One','admin.one@example.com','adminpass1','Super Admin'),
('ADM002','Admin','Two','admin.two@example.com','adminpass2','Regular Admin');

-- ======================
-- DEPARTMENT
-- ======================
CREATE TABLE dept_info (
    dept_name VARCHAR(255) PRIMARY KEY,
    designation VARCHAR(255),
    dept_head VARCHAR(255),
    building VARCHAR(255)
);

INSERT INTO dept_info VALUES
('Computer Science','Professor','John Doe','Main Building'),
('Engineering','Associate Professor','Jane Smith','Engineering Building'),
('Physics','Assistant Professor','Michael Johnson','Science Building'),
('Mathematics','Professor','Emily Brown','Library'),
('Chemistry','Associate Professor','William Martinez','Student Center');

-- ======================
-- INSTRUCTOR
-- ======================
CREATE TABLE instructor (
    instructorID VARCHAR(255) PRIMARY KEY,
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    pass VARCHAR(255),
    phone VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    date_of_birth DATE,
    salary INT,
    joining_date DATE,
    update_date DATE,
    dept_name VARCHAR(255),
    room_number INT,
    designation VARCHAR(255),
    ranking VARCHAR(255),
    off_day VARCHAR(255),
    FOREIGN KEY (dept_name) REFERENCES dept_info(dept_name),
    FOREIGN KEY (room_number) REFERENCES building_info(room_number)
);

INSERT INTO instructor VALUES
('INS001','John','Doe','password1','1234567890','john.doe@example.com','1990-05-15',50000,'2023-01-01','2023-01-01','Computer Science',101,'Professor','Senior','Sunday'),
('INS002','Jane','Smith','password2','0987654321','jane.smith@example.com','1988-10-20',60000,'2023-01-01','2023-01-01','Engineering',201,'Associate Professor','Junior','Saturday'),
('INS003','Michael','Johnson','password3','9876543210','michael.johnson@example.com','1975-03-28',70000,'2023-01-01','2023-01-01','Physics',301,'Assistant Professor','Intermediate','Friday'),
('INS004','Emily','Brown','password4','0123456789','emily.brown@example.com','1983-07-10',55000,'2023-01-01','2023-01-01','Mathematics',401,'Professor','Senior','Thursday'),
('INS005','William','Martinez','password5','9876123450','william.martinez@example.com','1992-11-05',48000,'2023-01-01','2023-01-01','Chemistry',501,'Associate Professor','Junior','Wednesday');

-- ======================
-- OFFICE STAFF
-- ======================
CREATE TABLE office_staff (
    staffID VARCHAR(255) PRIMARY KEY,
    firstname VARCHAR(255),
    lastname VARCHAR(255),
    pass VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(255) UNIQUE,
    date_of_birth DATE,
    salary INT,
    joining_date DATE,
    update_date DATE,
    role VARCHAR(255)
);

INSERT INTO office_staff VALUES
('STF001','Olivia','Jones','password1','1234567890','olivia.jones@example.com','1995-02-20',30000,'2023-01-01','2023-01-01','Receptionist'),
('STF002','Sophia','Brown','password2','9876543210','sophia.brown@example.com','1990-07-15',32000,'2023-01-01','2023-01-01','Accountant'),
('STF003','Liam','Johnson','password3','9876123450','liam.johnson@example.com','1992-11-30',31000,'2023-01-01','2023-01-01','Secretary'),
('STF004','Noah','Martinez','password4','0123456789','noah.martinez@example.com','1988-04-05',33000,'2023-01-01','2023-01-01','Assistant'),
('STF005','Ava','Garcia','password5','9876123450','ava.garcia@example.com','1993-09-10',34000,'2023-01-01','2023-01-01','Assistant');

-- ======================
-- COURSE
-- ======================
CREATE TABLE course (
    courseCode VARCHAR(255) PRIMARY KEY,
    courseName VARCHAR(255),
    time VARCHAR(255),
    instructorID VARCHAR(255),
    FOREIGN KEY (instructorID) REFERENCES instructor(instructorID)
);

INSERT INTO course VALUES
('CSE101','Introduction to Computer Science','9:00 AM - 11:00 AM','INS001'),
('ENG201','Advanced Engineering Mathematics','11:30 AM - 1:30 PM','INS002'),
('PHY301','Quantum Mechanics','2:00 PM - 4:00 PM','INS003'),
('MTH401','Linear Algebra','9:00 AM - 11:00 AM','INS004'),
('CHM501','Organic Chemistry','11:30 AM - 1:30 PM','INS005');

-- ======================
-- SECTION
-- ======================
CREATE TABLE section (
    batch_no VARCHAR(255) PRIMARY KEY,
    sectionName VARCHAR(255),
    courseCode VARCHAR(255),
    instructorID VARCHAR(255),
    FOREIGN KEY (courseCode) REFERENCES course(courseCode),
    FOREIGN KEY (instructorID) REFERENCES instructor(instructorID)
);

INSERT INTO section VALUES
('B001','A','CSE101','INS001'),
('B002','B','ENG201','INS002'),
('B003','C','PHY301','INS003'),
('B004','D','MTH401','INS004'),
('B005','E','CHM501','INS005');

-- ======================
-- DESIGNATION
-- ======================
CREATE TABLE designation (
    designationID VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255),
    instructorID VARCHAR(255),
    FOREIGN KEY (instructorID) REFERENCES instructor(instructorID)
);

INSERT INTO designation VALUES
('DES001','Professor','INS001'),
('DES002','Associate Professor','INS002'),
('DES003','Assistant Professor','INS003'),
('DES004','Professor','INS004'),
('DES005','Associate Professor','INS005');

-- ======================
-- STAFF WORK SCHEDULE
-- ======================
CREATE TABLE staff_workschedule (
    taskID VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255),
    description VARCHAR(255),
    assigned_to VARCHAR(255),
    email VARCHAR(255),
    building VARCHAR(255),
    shift VARCHAR(255),
    entry VARCHAR(255),
    exit_time VARCHAR(255),
    FOREIGN KEY (assigned_to) REFERENCES office_staff(staffID)
);

INSERT INTO staff_workschedule VALUES
('TSK001','Cleaning','Daily cleaning','STF001','olivia.jones@example.com','Main Building','Morning','7:00 AM','3:00 PM'),
('TSK002','Security','Night patrol','STF002','sophia.brown@example.com','Science Building','Night','10:00 PM','6:00 AM');

-- ======================
-- INSTRUCTOR SCHEDULE
-- ======================
CREATE TABLE instructor_WorkSchedule (
    taskID VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255),
    description VARCHAR(255),
    assigned_to VARCHAR(255),
    courseCode VARCHAR(255),
    batch_no VARCHAR(255),
    email VARCHAR(255),
    FOREIGN KEY (assigned_to) REFERENCES instructor(instructorID),
    FOREIGN KEY (courseCode) REFERENCES course(courseCode),
    FOREIGN KEY (batch_no) REFERENCES section(batch_no)
);

INSERT INTO instructor_WorkSchedule VALUES
('TSK101','Grading','Check assignments','INS001','CSE101','B001','john.doe@example.com'),
('TSK102','Lab','Lab supervision','INS002','ENG201','B002','jane.smith@example.com');

-- ======================
-- TRACK TABLES
-- ======================
CREATE TABLE instructor_WorkTrack (
    entryID VARCHAR(255) PRIMARY KEY,
    instructorID VARCHAR(255),
    taskID VARCHAR(255),
    StartTime TIMESTAMP,
    EndTime TIMESTAMP,
    Duration INT,
    status VARCHAR(255),
    email VARCHAR(255),
    FOREIGN KEY (instructorID) REFERENCES instructor(instructorID),
    FOREIGN KEY (taskID) REFERENCES instructor_WorkSchedule(taskID)
);

INSERT INTO instructor_WorkTrack VALUES
('ENT001','INS001','TSK101','2023-01-01 09:00:00','2023-01-01 11:00:00',2,'Completed','john.doe@example.com');

-- ======================
-- COMPLAIN
-- ======================
CREATE TABLE complain_table (
    ComplainID VARCHAR(255) PRIMARY KEY,
    senderID VARCHAR(255),
    accusedID VARCHAR(255),
    subject VARCHAR(255),
    description VARCHAR(255)
);

INSERT INTO complain_table VALUES
('CP001','STF001','INS001','Absent','Instructor absent');
