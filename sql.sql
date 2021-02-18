CREATE ROLE quantox WITH PASSWORD 'quantox$123';
ALTER ROLE quantox WITH LOGIN;

CREATE TABLE board(
    id serial PRIMARY KEY,
    board_type TEXT NOT NULL CHECK (board_type='CSM' OR board_type='CSMB'),
    board_name TEXT NOT NULL UNIQUE
);

CREATE TABLE student(
    id serial PRIMARY KEY,
    student_name TEXT NOT NULL,
    board_id INT NOT NULL,
    CONSTRAINT fk_board_id_student
        FOREIGN KEY (board_id)
        REFERENCES board(id)
        ON DELETE RESTRICT
);

CREATE TABLE grade(
    id serial PRIMARY KEY,
    student_id INT NOT NULL,
    grade_value INT NOT NULL,
    CONSTRAINT fk_student_id_grade
        FOREIGN KEY (student_id)
        REFERENCES student(id)
        ON DELETE CASCADE    
);