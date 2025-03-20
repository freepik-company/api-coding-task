ALTER TABLE equipments
ADD COLUMN description TEXT AFTER name,
ADD COLUMN type VARCHAR(50) AFTER description; 