CREATE TABLE tx_hotelierbooking_domain_model_attraction (
  uid INT AUTO_INCREMENT PRIMARY KEY,
  pid INT DEFAULT 0 NOT NULL,

  room INT DEFAULT 0 NOT NULL,

  tstamp INT DEFAULT 0 NOT NULL,
  crdate INT DEFAULT 0 NOT NULL,
  deleted TINYINT(4) DEFAULT 0 NOT NULL,
  hidden TINYINT(4) DEFAULT 0 NOT NULL,

  title VARCHAR(255) DEFAULT '' NOT NULL,
  category VARCHAR(50) DEFAULT '' NOT NULL,
  description TEXT,
  distance_km DOUBLE DEFAULT 0 NOT NULL,
  travel_time VARCHAR(100) DEFAULT '' NOT NULL,
  image INT DEFAULT 0 NOT NULL,
  map_link TEXT,
  suggested_label VARCHAR(255) DEFAULT '' NOT NULL,

  KEY parent (pid),
  KEY room (room)
);

CREATE TABLE tx_hotelierbooking_domain_model_offer (
  uid INT AUTO_INCREMENT PRIMARY KEY,
  pid INT DEFAULT 0 NOT NULL,

  tstamp INT DEFAULT 0 NOT NULL,
  crdate INT DEFAULT 0 NOT NULL,
  cruser_id INT DEFAULT 0 NOT NULL,
  deleted TINYINT(4) DEFAULT 0 NOT NULL,
  hidden TINYINT(4) DEFAULT 0 NOT NULL,

  title VARCHAR(255) DEFAULT '' NOT NULL,
  slug VARCHAR(255) DEFAULT '' NOT NULL,
  short_description TEXT,
  full_description TEXT,
  discount_type VARCHAR(20) DEFAULT 'percentage' NOT NULL,
  discount_value DOUBLE DEFAULT 0 NOT NULL,
  offer_type VARCHAR(50) DEFAULT 'general' NOT NULL,
  valid_from INT DEFAULT 0 NOT NULL,
  valid_until INT DEFAULT 0 NOT NULL,
  apply_globally TINYINT(4) DEFAULT 0 NOT NULL,
  usage_limit INT DEFAULT 0 NOT NULL,
  usage_count INT DEFAULT 0 NOT NULL,
  image INT DEFAULT 0 NOT NULL,

  KEY parent (pid),
  KEY slug (slug)
);

CREATE TABLE tx_hotelierbooking_offer_room_mm (
  uid_local INT DEFAULT 0 NOT NULL,
  uid_foreign INT DEFAULT 0 NOT NULL,
  sorting INT DEFAULT 0 NOT NULL,
  sorting_foreign INT DEFAULT 0 NOT NULL,

  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_hotelierbooking_domain_model_room (
  attractions INT DEFAULT 0 NOT NULL
);
