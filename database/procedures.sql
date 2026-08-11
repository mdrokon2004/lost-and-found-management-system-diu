USE lost_found_diu;

DELIMITER //

DROP PROCEDURE IF EXISTS get_user_report_summary//

CREATE PROCEDURE get_user_report_summary(IN p_user_id INT)
BEGIN
    SELECT
        COALESCE(
            (SELECT COUNT(*)
             FROM lost_items
             WHERE user_id = p_user_id),
            0
        ) AS lost_reports,

        COALESCE(
            (SELECT COUNT(*)
             FROM found_items
             WHERE user_id = p_user_id),
            0
        ) AS found_reports,

        COALESCE(
            (SELECT COUNT(*)
             FROM claims
             WHERE user_id = p_user_id),
            0
        ) AS claims;
END//

DELIMITER ;