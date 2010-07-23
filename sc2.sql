Begin Transaction;

Create  TABLE MAIN.[maps](
[name] varchar(80) UNIQUE NOT NULL
,[id] integer PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL
   
);

Create  TABLE MAIN.[players](
[name] varchar(40) UNIQUE NOT NULL
,[id] integer PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL
   
);

Create  TABLE MAIN.[players_replays](
[replay_id] integer(8)
,[player_id] integer(8)
,[race_id] integer(8)
   
);

Create  TABLE MAIN.[races](
[name] varchar(20) UNIQUE NOT NULL
,[id] integer PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL
   
);

INSERT INTO MAIN.[races] ('name') VALUES ('Zerg');
INSERT INTO MAIN.[races] ('name') VALUES ('Protoss');
INSERT INTO MAIN.[races] ('name') VALUES ('Terran');

Create  TABLE MAIN.[replays](
[filename] varchar(200) NOT NULL
,[upload_date] integer(8) NOT NULL
,[id] integer PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL
,[user_id] integer(8) NOT NULL
,[hash] varchar(40) UNIQUE NOT NULL
,[downloaded] integer(8) NOT NULL
,[map_id] integer(8) NOT NULL
);

Commit Transaction;