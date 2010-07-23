Begin Transaction;

Create  TABLE maps (
	id INTEGER PRIMARY KEY,
	name varchar(80) UNIQUE NOT NULL
);

Create  TABLE players (
	id integer PRIMARY KEY,
	name varchar(40) UNIQUE NOT NULL  
);

Create  TABLE players_replays (
replay_id integer(8),
player_id integer(8),
race_id integer(8)
);

Create  TABLE races (
id integer PRIMARY KEY,
name varchar(20) UNIQUE NOT NULL
);

INSERT INTO races ('name') VALUES ('Zerg');
INSERT INTO races ('name') VALUES ('Protoss');
INSERT INTO races ('name') VALUES ('Terran');

Create  TABLE replays (
filename varchar(200) NOT NULL,
upload_date integer(8) NOT NULL,
id integer PRIMARY KEY,
user_id integer(8) NOT NULL,
hash varchar(40) UNIQUE NOT NULL,
downloaded integer(8) NOT NULL,
map_id integer(8) NOT NULL
);

Commit Transaction;