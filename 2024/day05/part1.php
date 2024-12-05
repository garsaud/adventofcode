<?php

$db = new SQLite3(':memory:');
$db->exec('CREATE TABLE pages (n INTEGER, rank INTEGER, uid INTEGER)');
$db->exec('CREATE INDEX pi ON pages (uid, rank)');
$db->exec('CREATE TABLE rules (before INTEGER, after INTEGER)');
$db->exec('CREATE INDEX ri ON rules (before, after)');

$input = trim(stream_get_contents(STDIN));
[$rules, $updates] = explode("\n\n", $input);

foreach (explode("\n", $rules) as $rule) {
    [$before, $after] = explode('|', $rule);
    $db->exec("insert into rules (before, after) values ($before, $after)");
}
foreach (explode("\n", $updates) as $uid => $update) {
    $q = "insert into pages (uid, n, rank) values ";
    foreach (explode(',', $update) as $rank => $page_number) {
        $q .= "($uid, $page_number, $rank),";
    }
    $db->exec(trim($q, ','));
}

echo $db->querySingle(<<<SQL
select sum(middle) from (
	select (
	    select n from pages p0
        where upd.uid = p0.uid
        and p0.rank=cast((select avg(rank) from pages where upd.uid = uid) as integer)
    ) middle
	from pages upd
	group by uid
	having uid not in (
		select uid
		from pages p1
		where exists (
			select * from rules where before = n and after in (
				select p2.n from pages p2
				where p2.rank < p1.rank
				and p1.uid = p2.uid
			)
		)
	)
)
SQL);
