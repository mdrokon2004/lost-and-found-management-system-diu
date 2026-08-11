USE lost_found_diu;

DELIMITER //

DROP TRIGGER IF EXISTS after_claim_insert//

CREATE TRIGGER after_claim_insert
AFTER INSERT ON claims
FOR EACH ROW
BEGIN
    INSERT INTO notifications(user_id, title, message)
    VALUES (
        NEW.user_id,
        'Claim Submitted',
        'Your ownership claim has been submitted successfully.'
    );
END//

DELIMITER ;