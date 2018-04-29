# dummy data

insert into twitter_users (id, name, screen_name, created_at) values 
(1001,"test1","test1",null),
(1002,"test2","test2",null),
(1003,"test3","test3",null),
(1004,"test4","test4",null),
(1005,"test5","test5",null),
(1006,"test6","test6",null);

insert into urls (id, url, title, created_at) values
(1001, "http://google.com", "グーグル", null),
(1002, "http://yahoo.com", "ヤフー", null),
(1003, "http://bing.com", "bing", null),
(1004, "http://twitter.com", "twitter", null),
(1005, "http://facebook.com", "facebook", null),
(1006, "http://2ch.com", "2チャンネル", null),
(1007, "http://5ch.com", "5チャンネル", null),
(1008, "http://github.com", "github", null),
(1009, "http://youtube.com", "youtube", null),
(1010, "http://hatena.com", "はてな", null);

insert into comments (id, member_id, url_id, comment, created_at) values
(1001, "1001", "1001", "グーグルへのコメントテスト1", null),
(1002, "1001", "1001", "グーグルへのコメントテスト2", null),
(1003, "1001", "1001", "グーグルへのコメントテスト3", null),
(1004, "1001", "1001", "グーグルへのコメントテスト4", null),
(1005, "1002", "1001", "グーグルへのコメントテスト5", null),
(1006, "1002", "1001", "グーグルへのコメントテスト6", null),
(1007, "1002", "1001", "グーグルへのコメントテスト7", null),
(1008, "1002", "1001", "グーグルへのコメントテスト8", null),
(1009, "1003", "1002", "ヤフーへのコメントテスト1", null),
(1010, "1003", "1002", "ヤフーへのコメントテスト2", null),
(1011, "1003", "1002", "ヤフーへのコメントテスト3", null),
(1012, "1003", "1002", "ヤフーへのコメントテスト4", null),
(1013, "1004", "1002", "ヤフーへのコメントテスト5", null),
(1014, "1004", "1002", "ヤフーへのコメントテスト6", null);

insert into comments (member_id, url_id, comment, created_at) values
("1003", "1001", "グーグルへのコメントテスト ユーザー3 時間変更1", null),
("1004", "1001", "グーグルへのコメントテスト ユーザー4 時間変更1", null);


insert into comments (member_id, url_id, comment, created_at) values
("1005", "1001", "グーグルへのコメントテスト ユーザー5 時間変更2", null);
