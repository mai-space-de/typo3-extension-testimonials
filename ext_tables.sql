CREATE TABLE tx_maitestimonials_testimonial (
    quote text NOT NULL DEFAULT '',
    author_name varchar(255) NOT NULL DEFAULT '',
    author_role varchar(255) NOT NULL DEFAULT '',
    organisation varchar(255) NOT NULL DEFAULT '',
    portrait int(11) unsigned NOT NULL DEFAULT '0',
    categories int(11) unsigned NOT NULL DEFAULT '0'
);
