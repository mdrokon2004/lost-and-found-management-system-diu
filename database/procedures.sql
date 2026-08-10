USE lost_found_diu;
DELIMITER //
CREATE PROCEDURE get_user_report_summary(IN p_user_id INT)
BEGIN
 SELECT
   (SELECT COUNT(*) FROM lost_items WHERE user_id=p_user_id) lost_reports,
   (SELECT COUNT(*) FROM found_items WHERE user_id=p_user_id) found_reports,
   (SELECT COUNT(*) FROM claims WHERE user_id=p_user_id) claims;
END//
DELIMITER ;
