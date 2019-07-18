-- phpMyAdmin SQL Dump
-- version 4.4.15.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 30 2016 г., 05:27
-- Версия сервера: 5.6.28
-- Версия PHP: 5.6.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yii`
--

-- --------------------------------------------------------

--
-- Структура таблицы `downloads`
--

CREATE TABLE IF NOT EXISTS `downloads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `patch` varchar(255) NOT NULL,
  `short_desc` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `downloads`
--

INSERT INTO `downloads` (`id`, `name`, `patch`, `short_desc`, `img`) VALUES
(1, 'Архив логотипа "сделано в Германии" - jpg, esp, png.', '/downloads/logo_mg.rar', 'Логотип "сделано в Германии" в трех популярных графических форматах.', '/downloads/logo_mg.png'),
(2, 'Архив логотипа "сделано в Германии" - jpg, esp, png.', '/downloads/logo_mg.rar', 'Логотип "сделано в Германии" в трех популярных графических форматах.', '/downloads/logo_mg.png'),
(3, 'Архив логотипа "сделано в Германии" - jpg, esp, png.', '/downloads/logo_mg.rar', 'Логотип "сделано в Германии" в трех популярных графических форматах.', '/downloads/logo_mg.png'),
(4, 'Архив логотипа "сделано в Германии" - jpg, esp, png.', '/downloads/logo_mg.rar', 'Логотип "сделано в Германии" в трех популярных графических форматах.', '/downloads/logo_mg.png'),
(5, 'Архив логотипа "сделано в Германии" - jpg, esp, png.', '/downloads/logo_mg.rar', 'Логотип "сделано в Германии" в трех популярных графических форматах.', '/downloads/logo_mg.png'),
(6, 'Архив логотипа "сделано в Германии" - jpg, esp, png.', '/downloads/logo_mg.rar', 'Логотип "сделано в Германии" в трех популярных графических форматах.', '/downloads/logo_mg.png'),
(7, 'Архив логотипа "сделано в Германии" - jpg, esp, png.', '/downloads/logo_mg.rar', 'Логотип "сделано в Германии" в трех популярных графических форматах.', '/downloads/logo_mg.png'),
(8, 'Каталог подарков', '/donwload', 'Крутые майки', '/donwload'),
(9, 'Имиджевая картинка', 'c81e728d9d4c2f636f067f89cc14862c.jpg', 'Красивая картинка', 'd0f88bfbf93f5078ff06490082883764.jpg'),
(10, 'Имиджевая картинка', 'a666587afda6e89aec274a3657558a27.jpg', 'Красивая картинка', 'fed537780f3f29cc5d5f313bbda423c4.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `downloads_file_tag`
--

CREATE TABLE IF NOT EXISTS `downloads_file_tag` (
  `file_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `downloads_file_tag`
--

INSERT INTO `downloads_file_tag` (`file_id`, `tag_id`) VALUES
(1, 1),
(1, 2),
(4, 1),
(4, 3),
(6, 2),
(6, 3),
(6, 4),
(7, 1),
(7, 2),
(8, 2),
(10, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `downloads_hash_tag`
--

CREATE TABLE IF NOT EXISTS `downloads_hash_tag` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `downloads_hash_tag`
--

INSERT INTO `downloads_hash_tag` (`id`, `name`) VALUES
(1, 'логотипы'),
(2, 'учебники'),
(3, 'буклеты'),
(4, 'изображения'),
(5, 'новинки');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `downloads_file_tag`
--
ALTER TABLE `downloads_file_tag`
  ADD PRIMARY KEY (`file_id`,`tag_id`),
  ADD KEY `file_id` (`file_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Индексы таблицы `downloads_hash_tag`
--
ALTER TABLE `downloads_hash_tag`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT для таблицы `downloads_hash_tag`
--
ALTER TABLE `downloads_hash_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
