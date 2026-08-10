USE lost_found_diu;
DELIMITER //
CREATE TRIGGER after_claim_insert
AFTER INSERT ON claims
FOR EACH ROW
BEGIN
 INSERT INTO notifications(user_id,title,message)
 VALUES(NEW.user_id,'Claim submitted','Your ownership claim has been submitted for admin review.');
END//
DELIMITER ;
