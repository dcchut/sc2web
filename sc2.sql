BEGIN TRANSACTION;

-- kill all the tables which already exist
DROP TABLE IF EXISTS maps;
DROP TABLE IF EXISTS players;
DROP TABLE IF EXISTS replays;
DROP TABLE IF EXISTS players_replays;
DROP TABLE IF EXISTS races;
DROP TABLE IF EXISTS types;

-- create necessary tables
CREATE TABLE maps (
	id INTEGER PRIMARY KEY,
	name varchar(80) UNIQUE NOT NULL
);

CREATE TABLE players (
	id integer PRIMARY KEY,
	name varchar(40) UNIQUE NOT NULL  
);

CREATE TABLE replays (
	filename varchar(200) NOT NULL,
	upload_date integer(8) NOT NULL,
	id integer PRIMARY KEY,
	user_id integer(8) NOT NULL,
	hash varchar(40) UNIQUE NOT NULL,
	downloaded integer(8) NOT NULL,
	map_id integer(8) NOT NULL,
	type_id integer(8) NOT NULL
);

CREATE TABLE players_replays (
	replay_id integer(8),
	player_id integer(8),
	race_id integer(8)
);

CREATE TABLE races (
	id integer PRIMARY KEY,
	name varchar(20) UNIQUE NOT NULL,
	short_name varchar(20) UNIQUE NOT NULL
);

CREATE TABLE types (
	id integer PRIMARY KEY,
	name varchar(20) UNIQUE NOT NULL
);

-- insert our trusty races
INSERT INTO races ('name', 'short_name') VALUES ('Zerg', 'Z');
INSERT INTO races ('name', 'short_name') VALUES ('Protoss', 'P');
INSERT INTO races ('name', 'short_name') VALUES ('Terran', 'T');

COMMIT TRANSACTION;
