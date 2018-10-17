DELIMITER //

CREATE TRIGGER delete_unlinked_groups
AFTER DELETE ON st_tricks_groups
FOR EACH ROW
BEGIN
    IF NOT EXISTS (
        SELECT trick_id
        FROM st_tricks_groups
        WHERE group_id = OLD.group_id
    )
    THEN
        DELETE FROM st_group WHERE id = OLD.group_id;
    END IF;
END //

DELIMITER ;