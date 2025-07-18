USE clockwise;

ALTER TABLE users
  ADD COLUMN cell_phone VARCHAR(20) AFTER username,
  ADD COLUMN home_phone VARCHAR(20) AFTER cell_phone,
  ADD COLUMN nickname VARCHAR(100) NULL AFTER home_phone,
  ADD COLUMN address TEXT AFTER nickname,
  ADD COLUMN employee_id VARCHAR(50) AFTER address,
  ADD COLUMN status BOOLEAN NOT NULL DEFAULT TRUE AFTER employee_id,
  ADD COLUMN location_id INT UNSIGNED AFTER status,
  ADD COLUMN department_id INT UNSIGNED AFTER location_id,
  ADD COLUMN job_role_id INT UNSIGNED AFTER department_id,
  ADD COLUMN deleted BOOLEAN NOT NULL DEFAULT FALSE AFTER job_role_id;

  ADD CONSTRAINT fk_location
    FOREIGN KEY (location_id) REFERENCES locations(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

  ADD CONSTRAINT fk_department
    FOREIGN KEY (department_id) REFERENCES departments(id)
    ON DELETE SET NULL ON UPDATE CASCADE,

  ADD CONSTRAINT fk_job_role
    FOREIGN KEY (job_role_id) REFERENCES job_roles(id)
    ON DELETE SET NULL ON UPDATE CASCADE;