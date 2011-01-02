create table spam ( patternid int NOT NULL AUTO_INCREMENT, entrytime DATETIME NOT NULL, pattern varchar(160) NOT NULL, count int DEFAULT '0', PRIMARY KEY (patternid) ); 
