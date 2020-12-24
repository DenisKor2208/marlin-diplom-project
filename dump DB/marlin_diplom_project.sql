-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Дек 24 2020 г., 15:46
-- Версия сервера: 8.0.19
-- Версия PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `marlin_diplom_project`
--

-- --------------------------------------------------------

--
-- Структура таблицы `groups_users`
--

CREATE TABLE `groups_users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `permissions` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups_users`
--

INSERT INTO `groups_users` (`id`, `name`, `permissions`) VALUES
(1, 'Standart user', '{\"standart\":1}'),
(2, 'Administrator', '{\"admin\":1}');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `group_id` int NOT NULL DEFAULT '1',
  `data_register_user` varchar(255) NOT NULL,
  `status_user` varchar(255) NOT NULL DEFAULT 'Здравствуйте! Здесь будет мой статус)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `group_id`, `data_register_user`, `status_user`) VALUES
(1, 'Marlin', '$2y$10$yYad1snEnezI7OLfGz3XSujMjjIVEoShVGbD8w.QJ61c43IuOxrUe', 'marlin@list.ru', 2, '13/12/2020', 'Здравствуйте! Я админ)'),
(2, 'DenisKor', '$2y$10$SR6GO4Iitm2FaVh/7QWnVOqB8cD/4qgKCL8metslEdMrC/t78chei', 'denis@list.ru', 2, '14/12/2020', 'Здравствуйте! Я админ)'),
(3, 'John Doe', '$2y$10$Iv63CU7ebiZk53sfTAPCxeUF4BX330qEbCkc3qyXH4kvAXhnlx.Nq', 'johndoe@list.ru', 1, '15/12/2020', 'Здравствуйте! Я обычный пользователь) Класс!'),
(4, 'Jane Koe', '$2y$10$BNkVZQwQIoL6LoHvu0CmQOVXugxtkX9X5SUQ48kzSmgiSjnyeMaii', 'janekoe@list.ru', 1, '16/12/2020', 'Здравствуйте! Я обычный пользователь)'),
(20, 'avKorotina', '$2y$10$V70kY6q6nrK9vWBj5WkLy.dYWoMDM1baEuT.9Rw.uPJRz6.Xi/qTS', 'avkorotina@gmail.com', 1, '21/12/2020', 'Здравствуйте! Здесь будет мой статус)');

-- --------------------------------------------------------

--
-- Структура таблицы `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `groups_users`
--
ALTER TABLE `groups_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `groups_users`
--
ALTER TABLE `groups_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `user_sessions`
--
ALTER TABLE `user_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
