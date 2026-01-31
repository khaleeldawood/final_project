-- SQL INSERT statements for Jordanian Universities
-- Execute this in your MySQL database (unihub)

-- Public Universities (Government)
INSERT INTO universities (id, name, description, logo_url, email_domain, created_at, updated_at) VALUES
(1, 'University of Jordan', 'The largest and oldest university in Jordan, established in 1962', NULL, 'ju.edu.jo', NOW(), NOW()),
(2, 'Jordan University of Science and Technology', 'Leading technological university in Jordan', NULL, 'just.edu.jo', NOW(), NOW()),
(3, 'Yarmouk University', 'Major public university located in Irbid', NULL, 'yu.edu.jo', NOW(), NOW()),
(4, 'Hashemite University', 'Public university in Zarqa Governorate', NULL, 'hu.edu.jo', NOW(), NOW()),
(5, 'Al-Balqa Applied University', 'Applied sciences and technical education university', NULL, 'bau.edu.jo', NOW(), NOW()),
(6, 'Mutah University', 'Public university in Karak Governorate', NULL, 'mutah.edu.jo', NOW(), NOW()),
(7, 'Tafila Technical University', 'Technical university in Tafila', NULL, 'ttu.edu.jo', NOW(), NOW()),
(8, 'Al al-Bayt University', 'Public university in Mafraq', NULL, 'aabu.edu.jo', NOW(), NOW()),
(9, 'Al-Hussein Bin Talal University', 'Public university in Ma''an', NULL, 'ahu.edu.jo', NOW(), NOW()),
(10, 'German Jordanian University', 'German-Jordanian collaboration university', NULL, 'gju.edu.jo', NOW(), NOW()),
(11, 'Al-Hussein Technical University', 'Technical university focused on engineering', NULL, 'htu.edu.jo', NOW(), NOW());

-- Private Universities
INSERT INTO universities (id, name, description, logo_url, email_domain, created_at, updated_at) VALUES
(12, 'Princess Sumaya University for Technology', 'Private non-profit IT university', NULL, 'psut.edu.jo', NOW(), NOW()),
(13, 'Amman Arab University', 'Private university for graduate studies', NULL, 'aau.edu.jo', NOW(), NOW()),
(14, 'Applied Science Private University', 'Leading private university in Jordan', NULL, 'asu.edu.jo', NOW(), NOW()),
(15, 'Middle East University', 'Private university in Amman', NULL, 'meu.edu.jo', NOW(), NOW()),
(16, 'Isra University', 'Private university in Amman', NULL, 'iu.edu.jo', NOW(), NOW()),
(17, 'Zarqa University', 'Private university in Zarqa', NULL, 'zu.edu.jo', NOW(), NOW()),
(18, 'Philadelphia University', 'Private university known for engineering', NULL, 'philadelphia.edu.jo', NOW(), NOW()),
(19, 'Jerash Private University', 'Private university in Jerash', NULL, 'jpu.edu.jo', NOW(), NOW()),
(20, 'Irbid National University', 'Private university in Irbid', NULL, 'inu.edu.jo', NOW(), NOW()),
(21, 'Al-Ahliyya Amman University', 'One of the oldest private universities', NULL, 'ammanu.edu.jo', NOW(), NOW()),
(22, 'Al-Zaytoonah University of Jordan', 'Private university in Amman', NULL, 'zuj.edu.jo', NOW(), NOW()),
(23, 'Arab Open University - Jordan', 'Branch of Arab Open University', NULL, 'aou.edu.jo', NOW(), NOW()),
(24, 'Jadara University', 'Private university in Irbid Governorate', NULL, 'jadara.edu.jo', NOW(), NOW()),
(25, 'World Islamic Sciences and Education University', 'Islamic sciences university', NULL, 'wise.edu.jo', NOW(), NOW()),
(26, 'Ajloun National University', 'Private university in Ajloun', NULL, 'anu.edu.jo', NOW(), NOW()),
(27, 'Petra University', 'Private university in Amman', NULL, 'uop.edu.jo', NOW(), NOW());

-- Note: IDs 1 and 2 from your example are replaced with actual Jordanian universities
-- If you want to keep the original examples, use this instead:

-- Alternative: Keep original sample universities + add Jordanian ones
-- INSERT INTO universities (id, name, description, logo_url, email_domain, created_at, updated_at) VALUES
-- (1, 'North Valley University', 'Sample university', NULL, 'northvalley.edu', NOW(), NOW()),
-- (2, 'City Tech Institute', 'Sample university', NULL, 'citytech.edu', NOW(), NOW()),
-- Then start Jordanian universities from ID 3
