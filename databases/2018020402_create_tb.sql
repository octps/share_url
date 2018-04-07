create table urls (
    id INTEGER PRIMARY KEY AUTO_INCREMENT
    ,user_id INTEGER
    , url varchar(256) NOT NULL
    , comment varchar(1024)
    , created_at TIMESTAMP NOT NULL DEFAULT 0
    , updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
) ENGINE = InnoDB
DEFAULT CHARACTER SET 'utf8';
ALTER TABLE urls AUTO_INCREMENT = 1001;

create table users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT
    , name varchar(256) NOT NULL
    , email varchar(256) NOT NULL
    , password varchar(256) NOT NULL
    , confirm INTEGER NOT NULL  DEFAULT 0
    , confirm_url varchar(256) NOT NULL
    , created_at TIMESTAMP NOT NULL DEFAULT 0
    , updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
) ENGINE = InnoDB
DEFAULT CHARACTER SET 'utf8';
ALTER TABLE users AUTO_INCREMENT = 1001;

create table comments (
    id INTEGER PRIMARY KEY AUTO_INCREMENT
    , url_id INTEGER NOT NULL
    , comment varchar(1024)
    , created_at TIMESTAMP NOT NULL DEFAULT 0
    , updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
) ENGINE = InnoDB
DEFAULT CHARACTER SET 'utf8';
ALTER TABLE comments AUTO_INCREMENT = 1001;

create table twitter_users (
    id INTEGER PRIMARY KEY
    , screen_name varchar(256) NOT NULL
    , created_at TIMESTAMP NOT NULL DEFAULT 0
    , updated_at TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW()
) ENGINE = InnoDB
DEFAULT CHARACTER SET 'utf8';
ALTER TABLE users AUTO_INCREMENT = 1001;
