-- Create Users table
CREATE TABLE `Users` (
                         `AccountID` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                         `Username` VARCHAR(50) NOT NULL UNIQUE,
                         `CurrentIP` VARCHAR(45),
                         `LastIP` VARCHAR(45),
                         `Rank` TINYINT UNSIGNED NOT NULL,
                         `CreatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                         `UpdatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                         INDEX `idx_username` (`Username`)
);

-- Create UserLogs table
CREATE TABLE `UserLogs` (
                            `LogID` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                            `AccountID` INT UNSIGNED,
                            `LogType` ENUM('login', 'logout', 'password_change', 'profile_update', 'other') NOT NULL,
                            `Data` TEXT,
                            `IPAddress` VARCHAR(45),
                            `UpdatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            FOREIGN KEY (`AccountID`) REFERENCES `Users`(`AccountID`) ON DELETE SET NULL,
                            INDEX `idx_accountid_logtype` (`AccountID`, `LogType`)
);

-- Create News table
CREATE TABLE `News` (
                        `NewsID` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                        `NewsTitle` VARCHAR(100) NOT NULL,
                        `NewsContent` MEDIUMTEXT NOT NULL,
                        `Author` VARCHAR(50) NOT NULL,
                        `EditedBy` VARCHAR(50),
                        `PostedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `UpdatedOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        `ThumbNail` VARCHAR(255),
                        INDEX `idx_postedon` (`PostedOn`)
);

-- Create Sessions table
CREATE TABLE `Sessions` (
                            `sess_id` VARCHAR(128) PRIMARY KEY,
                            `sess_data` MEDIUMTEXT,
                            `sess_time` INT UNSIGNED,
                            INDEX `idx_sess_time` (`sess_time`)
);

-- Create Slider_Items table
CREATE TABLE `Slider_Items` (
                                `Id` SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                                `Title` VARCHAR(100) NOT NULL,
                                `Description` TEXT,
                                `Button_Text` VARCHAR(50),
                                `Button_URL` VARCHAR(255),
                                `Order_By` TINYINT UNSIGNED NOT NULL,
                                INDEX `idx_order_by` (`Order_By`)
);

-- Create Navbar_Menu table
CREATE TABLE `Navbar_Menu` (
                               `Id` SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                               `Name` VARCHAR(50) NOT NULL,
                               `URL` VARCHAR(255) NOT NULL,
                               `OrderBy` TINYINT UNSIGNED NOT NULL,
                               `Is_Active` BOOLEAN NOT NULL DEFAULT TRUE,
                               INDEX `idx_orderby_isactive` (`OrderBy`, `Is_Active`)
);

-- Create SocialMedia table
CREATE TABLE `SocialMedia` (
                               `Id` SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
                               `Name` VARCHAR(30) NOT NULL,
                               `URL` VARCHAR(255) NOT NULL,
                               `OrderBy` TINYINT UNSIGNED NOT NULL,
                               INDEX `idx_orderby` (`OrderBy`)
);