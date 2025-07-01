-- UPDATE PLAIN TEXT PASSWORDS TO HASHED

-- ONLY run this if passwords are currently plaintext!
UPDATE users
SET password =
        CASE
            WHEN LENGTH(password) < 60 THEN
                -- Assuming bcrypt hash length is 60
                -- Replace '123456' with the actual plain-text value if needed
                '$2y$10$q3c14l2a9S8yOWYFLVkK5e2JOfdQGSgqzTtPbM9jS6WbuvB.xT/7C' -- hash for '123456'
            ELSE password
            END;
