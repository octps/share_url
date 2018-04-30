
create table twitter_users (
    id  INTEGER PRIMARY KEY AUTO_INCREMENT 
    , user_id BIGINT PRIMARY KEY
    , name varchar(256) NOT NULL
    , screen_name varchar(256) NOT NULL
    , created_at TIMESTAMP NOT NULL DEFAULT 0
    , updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
) ENGINE = InnoDB
DEFAULT CHARACTER SET 'utf8';
ALTER TABLE twitter_users AUTO_INCREMENT = 1001;

create table urls (
    id INTEGER PRIMARY KEY AUTO_INCREMENT
    , url varchar(256) NOT NULL
    , title varchar (512) NOT NULL
    , created_at TIMESTAMP NOT NULL DEFAULT 0
    , updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
) ENGINE = InnoDB
DEFAULT CHARACTER SET 'utf8';
ALTER TABLE urls AUTO_INCREMENT = 1001;

create table comments (
    id INTEGER PRIMARY KEY AUTO_INCREMENT
    , member_id BIGINT NOT NULL
    , url_id INTEGER NOT NULL
    , comment varchar(512)
    , created_at TIMESTAMP NOT NULL DEFAULT 0
    , updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
) ENGINE = InnoDB
DEFAULT CHARACTER SET 'utf8';
ALTER TABLE comments AUTO_INCREMENT = 1001;

create table follows (
    id INTEGER PRIMARY KEY AUTO_INCREMENT
    , member_id BIGINT NOT NULL
    , follows_member_id BIGINT NOT NULL
    , created_at TIMESTAMP NOT NULL DEFAULT 0
    , updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
) ENGINE = InnoDB
DEFAULT CHARACTER SET 'utf8';
ALTER TABLE follows AUTO_INCREMENT = 1001;
