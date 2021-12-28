-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 28 Ara 2021, 18:05:53
-- Sunucu sürümü: 10.4.22-MariaDB
-- PHP Sürümü: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `graduation_project`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `commented_post` int(11) NOT NULL,
  `comment_sender` int(11) NOT NULL,
  `comment_detail` varchar(120) COLLATE utf8_turkish_ci NOT NULL,
  `comment_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `comments`
--

INSERT INTO `comments` (`id`, `commented_post`, `comment_sender`, `comment_detail`, `comment_time`) VALUES
(1, 10, 4, 'qwe', '2021-12-28 19:43:36'),
(2, 9, 4, 'asd', '2021-12-28 19:45:14'),
(3, 9, 4, 'asdasd', '2021-12-28 19:48:39'),
(4, 10, 1, 'asd', '2021-12-28 19:55:28'),
(5, 7, 1, 'asd', '2021-12-28 19:56:20'),
(6, 10, 1, 'qweqwe', '2021-12-28 19:58:14');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `content_detail` varchar(450) COLLATE utf8_turkish_ci NOT NULL,
  `content_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `contents`
--

INSERT INTO `contents` (`id`, `publisher_id`, `content_detail`, `content_time`) VALUES
(5, 1, 'Cras sollicitudin lectus turpis, sit amet dapibus dui commodo eu. Ut pulvinar erat in enim vulputate, eu semper est lacinia. Nam ornare molestie quam, ornare condimentum massa gravida et.\r\n\r\nNulla mollis felis eu rutrum mattis. Donec urna ante, laoreet id eros a, elementum fringilla est. Curabitur finibus erat sed nisi molestie cursus. Cras id pretium erat. Donec fringilla dolor aliquet, interdum elit vel, porta odio.', '2021-12-19 23:33:53'),
(6, 1, 'Cras sollicitudin lectus turpis, sit amet dapibus dui commodo eu. Ut pulvinar erat in enim vulputate, eu semper est lacinia. Nam ornare molestie quam, ornare condimentum massa gravida et.\r\n\r\nNulla mollis felis eu rutrum mattis. Donec urna ante, laoreet id eros a, elementum fringilla est. Curabitur finibus erat sed nisi molestie cursus. Cras id pretium erat. Donec fringilla dolor aliquet, interdum elit vel, porta odio.', '2021-12-19 23:34:09'),
(7, 2, 'Morbi rutrum euismod blandit. Quisque scelerisque eros eget mi vehicula laoreet. Nulla quis est at mauris vestibulum commodo vitae ut nibh. Quisque eu ipsum auctor nulla accumsan porta. Curabitur at fringilla nisi, id porttitor enim. Nullam luctus nibh in consequat ornare. Morbi eu enim ligula.', '2021-12-19 23:52:33'),
(9, 2, 'Cras sollicitudin lectus turpis, sit amet dapibus dui commodo eu. Ut pulvinar erat in enim vulputate, eu semper est lacinia. Nam ornare molestie quam, ornare condimentum massa gravida et.\r\n\r\nNulla mollis felis eu rutrum mattis. Donec urna ante, laoreet id eros a, elementum fringilla est. Curabitur finibus erat sed nisi molestie cursus. Cras id pretium erat. Donec fringilla dolor aliquet, interdum elit vel, porta odio.\r\n\r\nCras sollicitudin lectus ', '2021-12-20 20:07:04'),
(10, 4, 'asdasd', '2021-12-26 16:21:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `follower`
--

CREATE TABLE `follower` (
  `id` int(11) NOT NULL,
  `follower_name` varchar(12) COLLATE utf8_turkish_ci NOT NULL,
  `followed_name` varchar(12) COLLATE utf8_turkish_ci NOT NULL,
  `followed_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `follower`
--

INSERT INTO `follower` (`id`, `follower_name`, `followed_name`, `followed_time`) VALUES
(9, 'umitsancakli', 'usertest', '2021-12-19 16:25:41'),
(10, 'umitsancakli', 'usertest5', '2021-12-19 17:08:01'),
(15, 'usertest', 'umitsancakli', '2021-12-20 11:46:43'),
(16, 'usertest5', 'usertest', '2021-12-20 11:47:52'),
(17, 'usertest5', 'umitsancakli', '2021-12-20 11:48:14'),
(18, 'newuser', 'usertest', '2021-12-20 20:12:31');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `liked_contents`
--

CREATE TABLE `liked_contents` (
  `like_id` int(11) NOT NULL,
  `liked_content` int(11) NOT NULL,
  `who_liked` int(11) NOT NULL,
  `when_liked` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `liked_contents`
--

INSERT INTO `liked_contents` (`like_id`, `liked_content`, `who_liked`, `when_liked`) VALUES
(13, 10, 4, '2021-12-28 19:35:01'),
(14, 10, 4, '2021-12-28 19:35:03'),
(15, 10, 4, '2021-12-28 19:35:05');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `message_detail` text COLLATE utf8_turkish_ci NOT NULL,
  `message_getter` varchar(16) COLLATE utf8_turkish_ci NOT NULL,
  `message_sender` varchar(16) COLLATE utf8_turkish_ci NOT NULL,
  `message_time` datetime NOT NULL DEFAULT current_timestamp(),
  `delete_key` int(11) NOT NULL,
  `unique_name` varchar(40) COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `messages`
--

INSERT INTO `messages` (`id`, `message_detail`, `message_getter`, `message_sender`, `message_time`, `delete_key`, `unique_name`) VALUES
(93, 'qwdasdasd', 'usertest', 'usertest5', '2021-12-20 13:07:35', 3, 'usertest31004409'),
(94, 'qwdasdasd', 'usertest', 'usertest5', '2021-12-20 13:07:35', 2, 'usertest31004409'),
(95, 'asd', 'usertest5', 'usertest', '2021-12-20 13:07:51', 2, 'usertest3519260'),
(96, 'asd', 'usertest5', 'usertest', '2021-12-20 13:07:51', 3, 'usertest3519260');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(16) COLLATE utf8_turkish_ci NOT NULL,
  `email` varchar(90) COLLATE utf8_turkish_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_turkish_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `profile_photo` varchar(64) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'profile_default.png',
  `biography` varchar(180) COLLATE utf8_turkish_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `profile_photo`, `biography`) VALUES
(1, 'umitsancakli', 'umitsancakli98@gmail.com', '$2y$10$eZwqSSVuaqBQ8OyHb2xD8eNr1Lgt6WNnKVIlle7IW8hrbo149v1dC', '2021-12-01 15:56:36', '6553258z7lxv9.jpg', 'aaa'),
(2, 'usertest', 'testuser@asd.com', '$2y$10$PCsOiCJKskWuRzRKoYfkMunGTrkIV.AOdWqPpxfr98hLaNafY9BCq', '2021-12-01 18:04:28', '5230277minimal-g246fb5b46_1920.jpg', ''),
(3, 'usertest5', 'asdasd@asdasdasd', '$2y$10$1pJTWeyZjMQg2GamNvDNlu6L0KrkdQnBVxGI2KTPlodzFs9DuYLyS', '2021-12-07 21:43:47', 'profile_default.png', ''),
(4, 'newuser', 'asdasd@asdadwadada', '$2y$10$7p33OiyT8HgCqXjA.w3.I.niol/tOH20.wJiP2.Zjflsgbsswmbwu', '2021-12-20 20:12:18', 'profile_default.png', '');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `follower`
--
ALTER TABLE `follower`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `liked_contents`
--
ALTER TABLE `liked_contents`
  ADD PRIMARY KEY (`like_id`);

--
-- Tablo için indeksler `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `follower`
--
ALTER TABLE `follower`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `liked_contents`
--
ALTER TABLE `liked_contents`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
